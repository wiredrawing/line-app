<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Base\PasswordRequest;
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
     * @param PasswordRequest $request
     * @return Application|Factory|View
     */
    public function renew(PasswordRequest $request)
    {
        return view("admin.password.renew", [

        ]);
    }


    /**
     * パスワードリセット用URlの送信処理
     *
     * @param PasswordRequest $request
     * @return RedirectResponse
     */
    public function postRenew(PasswordRequest $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
        return redirect()->route("admin.password.completed");
    }

    /**
     * パスワードリセットURL送信完了画面
     *
     * @param PasswordRequest $request
     * @return Application|Factory|View
     */
    public function completed(PasswordRequest $request)
    {
        return view("admin.password.completed", [

        ]);
    }

    /**
     * 新規パスワード入力画面
     *
     * @param PasswordRequest $request
     * @param string $token
     * @param string $email
     * @return Application|Factory|View|void
     */
    public function reset(PasswordRequest $request, string $token, string $email)
    {
        try {
            print("パスワード再発行画面");
            $validated = $request->validated();

            // パスワード再発行URLのパラメータをログ
            logger()->info($validated);

        } catch (\Throwable $e) {
            logger()->error($e);
            return view("admin.error.index");
        }
    }

}
