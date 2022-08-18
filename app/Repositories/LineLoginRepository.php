<?php

namespace App\Repositories;


use App\Interfaces\LineLoginInterface;
use App\Models\Player;
use App\Models\LineAccount;
use App\Models\LineMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;
use Throwable;

class LineLoginRepository implements LineLoginInterface
{
    private $line_account = null;

    /**
     * Lineログイン後に認証情報を取得しDBへ保存する.
     *
     * @param array $validated_data
     * @return LineMember|null
     */
    public function authenticate(array $validated_data = []): ?LineMember
    {
        try {
            // line_account_idから実行中のLINEアプリケーションを取得
            $this->line_account = LineAccount::with([])
                ->findOrFail($validated_data["line_account_id"]);

            // 認可コードおよびclient_id,client_secretを使ってaccess_tokenを要求する
            $line_info = $this->fetchAccessToken($validated_data);
            if ($line_info === null) {
                throw new Exception("access_tokenの要求リクエストに失敗しました");
            }

            // ----------------------------------------------------------------------
            // (2).LINEプラットフォームから取得したid_tokenを解析してユーザー情報を取得する
            // ※JWTの解析処理
            // ----------------------------------------------------------------------
            $response = Http::asForm()
                ->post(Config("const.line_login.verify"), [
                    "id_token" => $line_info["id_token"],
                    "client_id" => $this->line_account->channel_id,
                ]);
            $response->throw();

            // httpリクエストが成功したかどうかを検証
            if ($response->successful() !== true) {
                throw new Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            $response = $response->json();

            // 当該LINEアプリケーションのLINE_IDを個別に取得
            $line_id = $response["sub"];
            $line_info["name"] = $response["name"];
            $line_info["picture"] = $response["picture"];
            $line_info["email"] = $response["email"];
            $line_info["sub"] = $response["sub"];
            $line_info["aud"] = $response["aud"];
            $line_info["line_account_id"] = $validated_data["line_account_id"];
            $line_info["api_token"] = $validated_data["api_token"];

            try {
                DB::beginTransaction();
                $line_member = LineMember::where([
                    "sub" => $line_id,
                    "line_account_id" => $validated_data["line_account_id"],
                ])
                    ->get()
                    ->first();

                // Today, I have just taken an interview of major system company online.
                if ($line_member === null) {
                    // 当該のLINEアプリケーションへのログインが始めての場合
                    // line_membersテーブルへ新規insert
                    $line_member = LineMember::create($line_info);
                    if ($line_member === null) {
                        throw new Exception("Failed registering new line member info.");
                    }
                    $new_end_user = [
                        "line_member_id" => $line_member->id,
                        "given_name" => $line_member->name,
                        "email" => $line_member->email,
                    ];
                    // 新規end_userレコードを登録
                    $player = Player::create($new_end_user);
                    if ($player === null) {
                        throw new Exception("Failed registering new end user info.");
                    }
                } else {
                    // nullでない場合はアップデートを行う
                    // 二度目以降のログイン
                    $result = $line_member->update($line_info);
                    if ($result !== true) {
                        throw new Exception("LINEユーザー情報のアップデートに失敗しました");
                    }
                    $player = Player::where([
                        "line_member_id" => $line_member->id,
                    ])
                        ->get()
                        ->first();
                    if ($player === null) {
                        throw new Exception("Could not find end user info which you specified.");
                    }
                    $result = $player->update([
                        "given_name" => $line_member->name,
                        "email" => $line_member->email,
                    ]);
                    if ($result !== true) {
                        throw new Exception("Failed updating existing line member info.");
                    }
                }
                DB::commit();
                return $line_member;
            } catch (Throwable $e) {
                DB::rollback();
                logger()->error($e);
                throw new Exception("Failed the query to database.");
            }
        } catch (Throwable $e) {
            logger()->error($e);
            return null;
        }
    }


    /**
     * (1)Lineサーバーからaccess_tokenを取得する
     *
     * @param array $validated_data
     * @return array|null
     */
    public function fetchAccessToken(array $validated_data = []): ?array
    {
        try {
            if ($this->line_account === null) {
                throw new Exception(__CLASS__ . "型インスタンスの初期化に失敗しています");
            }
            // 引数のvalidation_dataのキーチェック
            if (isset($validated_data["code"]) !== true || isset($validated_data["api_token"]) !== true) {
                throw new Exception("認可コードあるいは自サービス側のAPIトークンが欠損しています");
            }
            // ----------------------------------------------------------------------
            // (1).LINEプラットフォームから取得した認可コードを使ってaccess_tokenをリクエスト
            // ----------------------------------------------------------------------
            $response = Http::asForm()
                ->post(Config("const.line_login.token"), [
                    "grant_type" => "authorization_code",
                    "code" => $validated_data["code"],
                    "redirect_uri" => route("line.callback.index", [
                        "line_account_id" => $this->line_account->id,
                        "api_token" => $validated_data["api_token"],
                    ]),
                    "client_id" => $this->line_account->channel_id,
                    "client_secret" => $this->line_account->channel_secret,
                ]);

            $response->throw();

            // httpリクエストが成功したかどうかを検証
            if ($response->successful() !== true) {
                throw new Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            $response = $response->json();


            return [
                "access_token" => $response["access_token"],
                "token_type" => $response["token_type"],
                "refresh_token" => $response["refresh_token"],
                "expires_in" => $response["expires_in"],
                "id_token" => $response["id_token"],
            ];
        } catch (Throwable $e) {
            logger()->error($e);
            return null;
        }
    }
}
