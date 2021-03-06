<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Line\CallbackRequest;
use App\Interfaces\LineLoginInterface;
use App\Models\LineAccount;
use App\Models\LineMember;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CallbackController extends Controller
{


    /**
     * LINEプラットフォームから取得した認可コードでログインユーザーの
     * アクセストークンを取得する
     *
     * @param CallbackRequest $request
     * @param LineLoginInterface $lineLoginRepository
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(CallbackRequest $request, LineLoginInterface $lineLoginRepository)
    {
        try {
            // バリデーション後のGETおよびPOSTデータを取得
            $validated_data = $request->validated();

            // リポジトリパターンで対応
            $isSuccess = $lineLoginRepository->authenticate($validated_data);

            if ($isSuccess !== true) {
                throw new \Exception("Callback処理に失敗しました");
            }

            // LINEログイン完了画面へ遷移
            return redirect()->route("line.callback.completed", [
                "line_account_id" => $validated_data["line_account_id"],
                "api_token" => $validated_data["api_token"],
            ]);
        } catch (Exception $e) {
            logger()->error($e);
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }


    /**
     * LINE認証完了後に表示するページ
     *
     * @param CallbackRequest $request
     * @return Application|Factory|View
     */
    public function completed(CallbackRequest $request)
    {
        try {
            $validated = $request->validated();
            // --------------------------------------------
            // 実際は本webアプリケーションを利用する側のサイトへ
            // ?api_token=something というqueryをともなって
            // リダイレクトさせる
            // --------------------------------------------
            return view("line.callback.completed", [
                "validated" => $validated,
            ]);
        } catch (Exception $e) {
            logger()->error($e);
            return view("errors.index", [
                "e" => $e,
            ]);
        }
    }
}
