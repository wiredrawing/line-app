<?php

namespace App\Http\Controllers\Admin\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Api\Line\AccountRequest;
use App\Models\LineAccount;
use App\Libraries\RandomToken;
use Illuminate\Http\Request;

class AccountController extends Controller
{




    /**
     * 登録済みLINEアカウント一覧を返却
     *
     * @param AccountRequest $request
     * @return void
     */
    public function list(AccountRequest $request)
    {
        try {
            $validated = $request->validated();

            $line_account = LineAccount::create($validated);

            if ($line_account === null) {
                throw new \Exception("Failed creating new line official account.");
            }
        } catch (\Exception $e) {
            logger()->error($e);
        }
    }


    /**
     * 新規LINEアカウントを追加するAPI
     *
     * @param AccountRequest $request
     * @return void
     */
    public function create(AccountRequest $request)
    {
        // サンプルpostデータ
        // {
        //     "channel_id": "channel_id",
        //     "channel_secret": "channel_secret",
        //     "user_id": "user_id",
        //     "messaging_channel_id": "messaging_channel_id",
        //     "messaging_channel_secret": "messaging_channel_secret",
        //     "messaging_user_id": "messaging_user_id",
        //     "message_channel_access_token": "message_channel_access_token"
        // }
        try {
            $validated = $request->validated();
            logger()->info($validated);

            // ----------------------------------------------------
            // 登録対象アカウント専用の本アプリケーション用の
            // アクセストークをランダムな文字列として生成
            // 当該トークンの重複は禁止のためduplication checkを行う
            // ----------------------------------------------------
            $api_token = RandomToken::MakeRandomToken(96);
            $duplication_check = LineAccount::where([
                "api_token" => $api_token,
            ])
            ->get()
            ->first();

            if ($duplication_check !== null) {
                throw new \Exception("只今サーバーが混み合っています");
            }

            $validated["api_token"] = $api_token;

            $line_account = LineAccount::create($validated);

            if ($line_account === null) {
                throw new \Exception("Failed creating new line official account.");
            }

            // response
            $response = [
                "status" => true,
                "response" => $line_account,
                // errorsは配列とする
                "errors" => null,
            ];
            logger()->info($response);
            return response()->json($response);
        } catch (\Exception $e) {
            logger()->error($e);
            $response = [
                "status" => true,
                "response" => null,
                // errorsは配列とする
                "errors" => null,
            ];
            logger()->info($response);

            return response()->json($response);
        }
    }
}
