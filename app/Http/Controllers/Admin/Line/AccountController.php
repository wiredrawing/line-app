<?php

namespace App\Http\Controllers\Admin\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Base\Line\AccountRequest;
use App\Models\LineAccount;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{


    /**
     * 現在登録中のLINEアカウント一覧を取得する
     * Auth::user()でログインユーザー情報を取得するためにはルーティングにwebミドルウェアを設定する
     *
     * @param AccountRequest $request
     * @return Application|Factory|View|void
     */
    public function index(AccountRequest $request)
    {
        try {
            if (Auth::check() !== true) {
                throw new \Exception("ログインしていません");
            }
            // ログイン中の管理者情報を取得
            $administrator = Auth::user();
            // print_r($administrator->toArray());
            logger()->info($administrator);

            $line_accounts = LineAccount::all();
            return view("admin.line.account.index", [
                "line_accounts" => $line_accounts,
            ]);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            logger()->error($e);
        }
    }


    /**
     * 新規にLINEアカウントを作成する
     *
     * @param AccountRequest $request
     * @return void
     */
    public function create(AccountRequest $request)
    {
        try {
            $validated = $request->validated();

            $line_account = LineAccount::create($validated);
            if ($line_account === null) {
                throw new Exception("新規LINEアカウントの登録に失敗しました");
            }
        } catch (Exception $e) {
            logger()->error($e);
        }
    }


    /**
     * 指定したLINEアカウントの詳細を表示する
     *
     * @param AccountRequest $request
     * @param integer $line_account_id
     * @return void
     */
    public function detail(AccountRequest $request, int $line_account_id)
    {
        try {
            $validated = $request->validated();
            logger()->info($validated);

            $line_account = LineAccount::with([
                "line_members",
            ])
                ->findOrFail($validated["line_account_id"]);

            return view("admin.line.account.detail", [
                "line_account" => $line_account,
            ]);
        } catch (Exception $e) {
            logger()->error($e);
        }
    }
}
