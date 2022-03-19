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


            return view("admin.line.account", [
                "line_accounts" => $line_accounts,
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
        }
    }
}
