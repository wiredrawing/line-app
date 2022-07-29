<?php

namespace App\Repositories;


use App\Interfaces\LineLoginInterface;
use App\Models\LineAccount;
use App\Models\LineMember;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class LineLoginRepository implements LineLoginInterface {


    /**
     * Lineログイン後に認証情報を取得しDBへ保存する.
     *
     * @param array $validated_data
     * @return bool
     */
    public function authenticate(array $validated_data = []): bool
    {
        try {
            // line_account_idから実行中のLINEアプリケーションを取得
            $line_account = LineAccount::with([])->findOrFail($validated_data["line_account_id"]);

            // ----------------------------------------------------------------------
            // (1).LINEプラットフォームから取得した認可コードを使ってaccess_tokenをリクエスト
            // ----------------------------------------------------------------------
            $response = Http::asForm()
                ->post(Config("const.line_login.token"), [
                    "grant_type" => "authorization_code",
                    "code" => $validated_data["code"],
                    "redirect_uri" => route("line.callback.index", [
                        "line_account_id" => $line_account->id,
                        "api_token" => $validated_data["api_token"],
                    ]),
                    "client_id" => $line_account->channel_id,
                    "client_secret" => $line_account->channel_secret,
                ]);

            $response->throw();

            // httpリクエストが成功したかどうかを検証
            if ($response->successful() !== true) {
                throw new Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            $response = $response->json();


            $line_info = [
                "access_token" => $response["access_token"],
                "token_type" => $response["token_type"],
                "refresh_token" => $response["refresh_token"],
                "expires_in" => $response["expires_in"],
                "id_token" => $response["id_token"],
            ];

            // ----------------------------------------------------------------------
            // (2).LINEプラットフォームから取得したid_tokenを解析してユーザー情報を取得する
            // ※JWTの解析処理
            // ----------------------------------------------------------------------
            $response = Http::asForm()
                ->post(Config("const.line_login.verify"), [
                    "id_token" => $line_info["id_token"],
                    "client_id" => $line_account->channel_id,
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
                    throw new \Exception("Failed registering new line member info.");
                }
            } else {
                // nullでない場合はアップデートを行う
                // 二度目以降のログイン
                $line_member = $line_member->update($line_info);
                if ($line_member !== true) {
                    throw new \Exception("LINEユーザー情報のアップデートに失敗しました");
                }
            }
            return true;
        } catch (\Throwable $e) {
            logger()->error($e);
            return false;
        }
    }

}
