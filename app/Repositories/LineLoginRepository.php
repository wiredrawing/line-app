<?php

namespace App\Repositories;


use App\Interfaces\LineLoginInterface;
use App\Libraries\RandomToken;
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

    private $api_token = null;


    /**
     * Lineログイン後に認証情報を取得しDBへ保存する.
     *
     * @param array $validated_data
     * @return LineMember|null
     */
    public function authenticate(array $validated_data = []): ?LineMember
    {
        try {
            // APIコール用のトークンを生成する(※LINEログインの度に)
            $token = RandomToken::MakeRandomToken(128);
            if (strlen($token) !== 128) {
                throw new \Exception("api_tokenの作成に失敗しました");
            }
            $this->api_token = $token;

            // line_account_idから実行中のLINEアプリケーションを取得
            $this->line_account = LineAccount::findOrFail($validated_data["line_account_id"]);

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

                if ($line_member === null) {
                    // 新規player情報を追加
                    $line_member = $this->createLineMember($line_info);
                } else {
                    // 二度目のログインの場合情報のアップデートを実行
                    $line_member = $this->updateLineMember($line_info, $line_member);
                }
                DB::commit();
                return $line_member;
            } catch (Throwable $e) {
                DB::rollback();
                var_dump($e->getMessage());
                logger()->error($e);
                throw new Exception("Failed the query to database.");
            }
        } catch (Throwable $e) {
            var_dump($e->getMessage());
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


    /**
     * @param array $new_line_info
     * @return LineMember
     * @throws Exception
     */
    private function createLineMember(array $new_line_info): \App\Models\LineMember
    {
        // 当該のLINEアプリケーションへのログインが始めての場合
        // line_membersテーブルへ新規insert
        $line_member = LineMember::create($new_line_info);
        if ($line_member === null) {
            throw new Exception("Failed registering new line member info.");
        }
        $new_end_user = [
            "line_member_id" => $line_member->id,
            "nickname" => $line_member->name,
            "email" => $line_member->email,
            "api_token" => $this->api_token,
        ];
        // 新規end_userレコードを登録
        $player = Player::create($new_end_user);
        if ($player === null) {
            throw new Exception("Failed registering new end user info.");
        }
        return LineMember::findOrFail($line_member->id);
    }

    /**
     * 指定した line member情報を更新する
     * @param array $update_line_info
     * @param LineMember $line_member
     * @return LineMember
     * @throws Exception
     */
    private function updateLineMember(array $update_line_info, LineMember $line_member): LineMember
    {
        // nullでない場合はアップデートを行う
        // 二度目以降のログイン
        $result = $line_member->update($update_line_info);
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
            "email" => $line_member->email,
            // api_tokenはログインの度に更新する
            "api_token" => $this->api_token,
        ]);
        if ($result !== true) {
            throw new Exception("Failed updating existing line member info.");
        }
        return LineMember::findOrFail($line_member->id);
    }
}
