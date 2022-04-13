<?php

namespace App\Http\Controllers\Admin\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Api\Line\AccountRequest;
use App\Models\LineAccount;
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
        try {
            $validated = $request->validated();
            logger()->info($validated);

            $line_account = LineAccount::create($validated);

            if ($line_account === null) {
                throw new \Exception("Failed creating new line official account.");
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
        }
    }
}
