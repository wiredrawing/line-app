<?php

namespace App\Http\Controllers\Line;

use App\Models\LineAccount;
use App\Models\LineMember;
use App\Http\Controllers\Controller;
use App\Http\Requests\Line\LoginRequest;
use App\Libraries\RandomToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{


    /**
     * 現在登録中のLINEアプリケーション一覧を表示
     *
     * @param LoginRequest $request
     * @return void
     */
    public function index(LoginRequest $request)
    {
        try {
            $line_accounts = LineAccount::with([
                "line_callback_urls",
            ])
            ->where([
                "is_enabled" =>  Config("const.binary_type.on"),
                "is_hidden" => Config("const.binary_type.off"),
            ])
            ->get();
            return view("line.login.list", $line_accounts);
        } catch (\Exception $e) {
            return response()->view("errors.index", [
                "e" => $e,
            ]);
        }
    }





    /**
     * 任意のLINEアプリケーションへのLINEログインのページ
     *
     * @param LoginRequest $request
     * @param integer $line_account_id
     * @param string $application_key
     * @return void
     */
    public function detail(LoginRequest $request, int $line_account_id, string $application_key)
    {
        try {
            $validated = $request->validated();

            $line_account = LineAccount::with([
                "line_callback_urls",
            ])
            ->where([
                "application_key" => $application_key,
            ])
            ->whereHas("line_callback_urls")
            ->findOrFail($validated["line_account_id"]);

            // ユーザーの識別用のランダムトークン
            $api_token = RandomToken::MakeRandomToken();

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

            $query_build = [
                "response_type" => "code",
                "client_id" => $line_account->channel_id,
                "redirect_uri" => $line_account->line_callback_urls->first()->url."?api_token={$api_token}",
                "scope" => "profile openid email",
                "nonce" => hash("sha256", Str::uuid()),
                "state" => hash("sha256", Str::uuid()),
                "bot_prompt" => "aggressive",
            ];

            return redirect(Config("const.line_login.authorize")."?".http_build_query($query_build));
        } catch (\Exception $e) {
            return response()->view("errors.index", [
                "e" => $e,
            ]);
        }
    }
}
