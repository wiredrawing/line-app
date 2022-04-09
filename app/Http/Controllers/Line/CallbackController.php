<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Line\CallbackRequest;
use App\Models\LineAccount;
use App\Models\LineMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CallbackController extends Controller
{


    /**
     * LINEプラットフォームから取得した認可コードでログインユーザーの
     * アクセストークンを取得する
     *
     * @param CallbackRequest $request
     * @return void
     */
    public function index(CallbackRequest $request)
    {
        try {
            // バリデーション後のGETおよびPOSTデータを取得
            $validated_data = $request->validated();

            // line_account_idから実行中のLINEアプリケーションを取得
            $line_account = LineAccount::with([
                // "line_callback_urls"
            ])
            // ->whereHas("line_callback_urls")
            ->findOrFail($validated_data["line_account_id"]);

            // var_dump(route("line.callback.index", [
            //     "line_account_id" => $line_account->id,
            //     "api_token" => $validated_data["api_token"]
            // ]));
            // exit();

            // ----------------------------------------------------------------------
            // LINEプラットフォームから取得した認可コードを使ってaccess_tokenをリクエスト
            // ----------------------------------------------------------------------
            $response = Http::asForm()->post(Config("const.line_login.token"), [
                "grant_type" => "authorization_code",
                "code" => $validated_data["code"],
                // "redirect_uri" => $line_account->line_callback_urls->first()->url."?api_token={$validated_data["api_token"]}",
                "redirect_uri" => route("line.callback.index", [
                    "line_account_id" => $line_account->id,
                    "api_token" => $validated_data["api_token"]
                ]),
                "client_id" => $line_account->channel_id,
                "client_secret" => $line_account->channel_secret,
            ]);

            $response->throw();

            // httpリクエストが成功したかどうかを検証
            if ($response->successful() !== true) {
                throw new \Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            $response = $response->json();


            $line_info = [
                "access_token"  => $response["access_token"],
                "token_type"    => $response["token_type"],
                "refresh_token" => $response["refresh_token"],
                "expires_in"    => $response["expires_in"],
                "id_token"      => $response["id_token"],
            ];

            // LINEプラットフォームから取得したid_tokenを解析してユーザー情報を取得する
            $response = Http::asForm()->post(Config("const.line_login.verify"), [
                "id_token" => $line_info["id_token"],
                "client_id" => $line_account->channel_id,
            ]);
            $response->throw();

            // httpリクエストが成功したかどうかを検証
            if ($response->successful() !== true) {
                throw new \Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            $response = $response->json();

            // 当該LINEアプリケーションのLINE_IDを個別に取得
            $line_id = $response["sub"];
            $line_info["name"]            = $response["name"];
            $line_info["picture"]         = $response["picture"];
            $line_info["email"]           = $response["email"];
            $line_info["sub"]             = $response["sub"];
            $line_info["aud"]             = $response["aud"];
            $line_info["line_account_id"] = $validated_data["line_account_id"];
            $line_info["api_token"]       = $validated_data["api_token"];

            $line_member = LineMember::where([
                "sub" => $line_id,
                "line_account_id" => $validated_data["line_account_id"],
            ])
            ->get()
            ->first();


            if ($line_member === null) {
                // 当該のLINEアプリケーションへのログインが始めての場合
                // line_membersテーブルへ新規insert
                $line_member = LineMember::create($line_info);
            } else {
                // nullでない場合はアップデートを行う
                // 二度目以降のログイン
                $line_member = $line_member->update($line_info);
                if ($line_member !== true) {
                    throw new \Exception("LINEユーザー情報のアップデートに失敗しました");
                }
            }
            // LINEログイン完了画面へ遷移
            return redirect()->route("line.callback.completed", [
                "line_account_id" => $validated_data["line_account_id"],
                "api_token" => $validated_data["api_token"],
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
            var_dump($e->getMessage());
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }


    /**
     * LINE認証完了後に表示するページ
     *
     * @param CallbackRequest $request
     * @return void
     */
    public function completed(CallbackRequest $request)
    {
        try {
            $validated = $request->validated();
            // --------------------------------------------
            // 実際は本webアプリケーションを利用する側のサイトへ
            // ?api_token=something というqueryをともなって
            // リダイレクトさせる
            // --------------------------------------------
            return view("line.callback.completed", [
                "validated" => $validated,
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }
}
