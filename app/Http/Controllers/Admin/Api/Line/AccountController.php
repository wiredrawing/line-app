<?php

namespace App\Http\Controllers\Admin\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Api\Line\AccountRequest;
use App\Models\LineAccount;
use App\Libraries\RandomToken;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private $errors = [];



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
            logger()->info($validated);

            $line_accounts = LineAccount::where([
                "is_enabled" => Config("const.binary_type.on"),
                "is_hidden" => Config("const.binary_type.off")
            ])
            ->get();

            $response = [
                "status" => true,
                "response" => $line_accounts,
                "errors" => null,
            ];
            logger()->info($response);
            return response()->json($response);
        } catch (\Exception $e) {
            logger()->error($e);
            $response = [
                "status" => false,
                "response" => null,
                // errorsは配列とする
                "errors" => $this->errors,
            ];
            logger()->error($response);
            return response()->json($response);
        }
    }

    /**
     * 指定したLINEアカウントの情報を取得する
     *
     * @param AccountRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function detail(AccountRequest $request, int $line_account_id, string $api_token)
    {
        try {
            $validated = $request->validated();

            logger()->info($validated);

            $line_account = LineAccount::where([
                "api_token" => $api_token,
            ])->find($line_account_id);

            if ($line_account === null) {
                throw new \Exception("Could not find the account which you specified.");
            }

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
                "status" => false,
                "response" => null,
                // errorsは配列とする
                "errors" => $this->errors,
            ];
            logger()->error($response);
            return response()->json($response);
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

            // DBへのinsertが失敗した場合
            if ($line_account === null) {
                $this->errors["system_error"][] = "Failed creating new line official account.";
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
                "status" => false,
                "response" => null,
                // errorsは配列とする
                "errors" => $this->errors,
            ];
            logger()->error($response);
            return response()->json($response);
        }
    }

    /**
     * 新規LINEアカウントを追加する前に行うバリデーションチェック
     *
     * @param AccountRequest $request
     * @return void
     */
    public function check(AccountRequest $request)
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

            // ---------------------------------------------------
            // バリデーションチェックのみなのでpostデータをそのまま返却する
            // response
            // ---------------------------------------------------
            $response = [
                "status" => true,
                "response" => $validated,
                // errorsは配列とする
                "errors" => null,
            ];
            logger()->info($response);
            return response()->json($response);
        } catch (\Exception $e) {
            logger()->error($e);
            $response = [
                "status" => false,
                "response" => null,
                // errorsは配列とする
                "errors" => $this->errors,
            ];
            logger()->error($response);
            return response()->json($response);
        }
    }

    /**
     * 指定したLINE公式アカウントの更新処理をする
     *
     * @param AccountRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function update(AccountRequest $request, int $line_account_id)
    {
        try {
            $validated = $request->validated();

            logger()->info($validated);

            $line_account = LineAccount::findOrFail($line_account_id);

            // アップデート処理を行う度に api_token を更新する
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

            // update
            $result = $line_account->update($validated);

            if ($result !== true) {
                throw new \Exception("Failed updating the account which you specified.");
            }

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
                "status" => false,
                "response" => null,
                // errorsは配列とする
                "errors" => $this->errors,
            ];
            logger()->error($response);
            return response()->json($response);
        }
    }
}
