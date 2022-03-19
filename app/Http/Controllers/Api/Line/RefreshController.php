<?php

namespace App\Http\Controllers\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Line\RefreshRequest;
use App\Models\LineMember;
use Illuminate\Support\Facades\Http;

class RefreshController extends Controller
{

    /**
     * 指定したLINEユーザーのアクセストークンの更新を行う
     *
     * @param RefreshRequest $request
     * @param string $api_token
     * @return void
     */
    public function index(RefreshRequest $request)
    {
        try {
            // --------------------------------------
            // 指定したLINEメンバーのアクセストークン更新開始
            // --------------------------------------
            logger()->info("start updating access token.");

            $validated = $request->validated();

            $line_member = LineMember::with([
                "line_account"
            ])
            ->where([
                "api_token" => $validated["api_token"],
            ])
            ->get()
            ->first();

            logger()->info("LINEメンバーアカウントトークン更新前レコード----->");
            logger()->info($line_member);


            $request_body = [
                "grant_type" => "refresh_token",
                "refresh_token" => $line_member->refresh_token,
                "client_id" => $line_member->line_account->channel_id,
                "client_secret" => $line_member->line_account->channel_secret,
            ];

            $response = Http::asForm()->post(Config("const.line_login.token"), $request_body);

            // logging
            logger()->info("アクセストークン更新用API ".Config("const.line_login.token")."へのpost data");
            logger()->info($request_body);

            // エラーの場合は例外を発生
            $response->throw();

            // httpリクエストが成功したかどうかを検証
            if ($response->successful() !== true) {
                logger()->error("LINEメッセージの送信に失敗しました");
                throw new \Exception();
            }
            $response = $response->json();

            $member_to_update = [
                "access_token" => $response["access_token"],
                "refresh_token" => $response["refresh_token"]
            ];

            $response = $line_member->update($member_to_update);
            logger()->info($response);
            if ($response !== true) {
                throw new \Exception("LINEメンバーのアクセストークンの更新処理が失敗しました");
            }

            logger()->info("completed updating access token.");

            $response = [
                "status" => true,
                "response" => $line_member,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            logger()->error($e);
            $response = [
                "status" => false,
                "response" => null,
                "error" => $e->getMessage(),
            ];
            return response()->json($response);
        }
    }
}
