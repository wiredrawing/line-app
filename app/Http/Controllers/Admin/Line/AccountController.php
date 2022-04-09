<?php

namespace App\Http\Controllers\Admin\Line;

use App\Http\Controllers\Controller;
use App\Models\LineAccount;
use Illuminate\Http\Request;

class AccountController extends Controller
{




    /**
     * 現在登録中のLINEアカウント一覧を取得する
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $line_accounts = LineAccount::all();


            return view("admin.line.account.index", [
                "line_accounts" => $line_accounts,
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
        }
    }


    /**
     * 指定したLINEアカウントの詳細を表示する
     *
     * @param Request $request
     * @param integer $line_account_id
     * @return void
     */
    public function detail(Request $request, int $line_account_id)
    {
        try {
            $validated = $request->validated();
            $line_account = LineAccount::findOrFail($validated["line_account_id"]);


            return view("admin.line.account.detail", [
                "line_account" => $line_account,
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
        }
    }
}
