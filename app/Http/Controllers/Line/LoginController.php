<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Line\LoginRequest;
use App\Libraries\RandomToken;
use App\Models\LineAccount;
use App\Models\LineMember;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class LoginController extends Controller
{


    /**
     * 現在登録中のLINEアプリケーション一覧を表示
     *
     * @param LoginRequest $request
     * @return Application|Factory|View|Response
     */
    public function index(LoginRequest $request)
    {
        try {
            $line_accounts = LineAccount::where([
                "is_enabled" => Config("const.binary_type.on"),
                "is_hidden" => Config("const.binary_type.off"),
            ])
                ->where(
                    "application_key", "!=", null
                )
                ->get();

            return view("line.login.index", [
                "line_accounts" => $line_accounts
            ]);
        } catch (\Exception $e) {
            logger()->error($e);
            return response()->view("errors.index", [
                "e" => $e,
            ]);
        }
    }


    /**
     * 任意のLINEアプリケーションへのLINEログインのページ
     *
     * @param LoginRequest $request
     * @param int $line_account_id
     * @param string $application_key
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function detail(LoginRequest $request, int $line_account_id, string $application_key)
    {
        try {
            $validated = $request->validated();

            $line_account = LineAccount::where([
                "application_key" => $application_key,
            ])
                ->find($validated["line_account_id"]);

            if ($line_account === null) {
                throw new \Exception("指定したLINEアプリケーション用ログインページが見つかりませんでした");
            }

            // ユーザーの識別用のランダムトークン
            $api_token = RandomToken::MakeRandomToken(64);

            // 同一のLINE channel_idでapi_tokenが重複しないかどうかを検証
            $line_member = LineMember::where([
                "api_token" => $api_token,
                "line_account_id" => $line_account_id,
            ])
                ->get()
                ->first();

            if ($line_member !== null) {
                throw new \Exception("只今サーバーが混み合っているようです.");
            }

            $redirect_uri = route("line.callback.index", [
                "line_account_id" => $line_account->id,
                "api_token" => $api_token,
            ]);
            $query_build = [
                "response_type" => "code",
                "client_id" => $line_account->channel_id,
                // ラインログイン完了後,戻ってくる本アプリケーションのURL
                "redirect_uri" => $redirect_uri,
                "scope" => "profile openid email",
                "nonce" => hash("sha256", Str::uuid()),
                "state" => hash("sha256", Str::uuid()),
                "bot_prompt" => "aggressive",
            ];
            // 以下URL(LINE側ドメイン)にリダイレクトさせLINE側でログインさせる
            // ユーザーに認証と認可を要求する
            return redirect(Config("const.line_login.authorize") . "?" . http_build_query($query_build));
        } catch (\Exception $e) {
            logger()->error($e);
            return response()->view("errors.index", [
                "e" => $e,
            ]);
        }
    }
}
