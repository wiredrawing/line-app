<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// パスワード再発行処理に以下クラスが必要
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{


    /**
     * パスワード再発行画面
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function renew(Request $request)
    {
        return view("admin.password.renew", [

        ]);
    }


    /**
     * パスワードリセット用URlの送信処理
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postRenew(Request $request)
    {
        var_dump("start", __FUNCTION__);
        $request->validate(['email' => 'required|email']);
        var_dump($request->input("email"));
        //exit();
        var_dump(Config("mail"));
        var_dump($request->only("email"));
        $status = Password::sendResetLink(
            $request->only('email')
        );
        var_dump($status);


        $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

        var_dump($status);
        var_dump("end", __FUNCTION__);
        exit();
        return redirect()->route("admin.password.completed");
    }

    public function completed(Request $request)
    {
        return view("admin.password.completed", [

        ]);
    }

    public function reset(Request $request)
    {

    }

}
