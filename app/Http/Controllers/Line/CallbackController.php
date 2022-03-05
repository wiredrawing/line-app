<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Line\CallbackRequest;
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
     * @param Request $request
     * @param integer $line_account_id
     * @return void
     */
    public function index(CallbackRequest $request, int $line_account_id)
    {
        try {
            $validated_data = $request->validated();
            $line_account = LineAccount::findOrFail($validated_data["line_account_id"]);

            print_r($validated_data);

            $http_response = Http::asForm()->post(Config("const.line_login.token"), [
                "grant_type" => "authorization_code",
                "code" => $validated_data["code"],
                "redirect_uri" => "http://localhost/line/callback/1",
                "client_id" => $line_account->channel_id,
                "client_secret" => $line_account->channel_secret,
            ]);

            $http_response->throw();


            // httpリクエストが成功したかどうかを検証
            if ($http_response->successful() !== true) {
                throw new \Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            $json = $http_response->json();

            $line_member_info = [
                "access_token"  => $json["access_token"],
                "token_type"    => $json["token_type"],
                "refresh_token" => $json["refresh_token"],
                "expires_in"    => $json["expires_in"],
                "id_token"      => $json["id_token"],
            ];

            $http_response = Http::asForm()->post(Config("const.line_login.verify"), [
                "id_token" => $json["id_token"],
                "client_id" => $line_account->channel_id,
            ]);
            // httpリクエストが成功したかどうかを検証
            if ($http_response->successful() !== true) {
                throw new \Exception("LINE側からユーザー情報の取得に失敗しました");
            }
            $json = $http_response->json();
            print_r($json);

            $line_member_info["name"] = $json["name"];
            $line_member_info["picture"] = $json["picture"];
            $line_member_info["email"] = $json["email"];
            $line_member_info["sub"] = $json["sub"];
            $line_member_info["aud"] = $json["aud"];

            print_r($line_member_info);
            $line_member = LineMember::create($line_member_info);
            print_r($line_member->toArray());
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
