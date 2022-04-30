<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
{

    // --------------------------------------------------------
    // 以下traitを使うためには
    // composer require laravel/ui "^2.0"
    // を実行して認証モジュールをインストールする必要がある
    // --------------------------------------------------------
    use AuthenticatesUsers;


    /**
     * 管理画面側のログインページ
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {

        var_dump("ログインページ");


        return view("admin.login.index", [

        ]);
    }


    /**
     * 認証処理を実行
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response|void
     * @throws ValidationException
     */
    public function authenticate(Request $request)
    {
        // ------------------------------------------------------------------------------------
        // 認証成功後の画面遷移先を修正
        // route関数を使って動的にリダイレクト先を変更
        // ------------------------------------------------------------------------------------
        $this->redirectTo = route("admin.line.account.index", [], false);

        // validation
        $this->validateLogin($request);

        // ログイン回数のチェック
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // ログインの処理
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    protected function loggedOut(Request $request)
    {
        // ログアウト処理完了後は再度ログインページへリダイレクトさせる
        return redirect()->route("admin.login.index");
    }

}

