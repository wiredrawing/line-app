<?php

namespace App\Http\Controllers\Line;

use App\Models\LineAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{






    /**
     * 任意のLINEアプリケーションへのLINEログインのページ
     *
     * @param Request $request
     * @param integer $line_account_id
     * @return void
     */
    public function index(Request $request, int $line_account_id)
    {
        try {
            $line_account = LineAccount::findOrFail($line_account_id);

            $authorization_url = "https://access.line.me/oauth2/v2.1/authorize?";
            $query_build = [
                "response_type" => "code",
                "client_id" => $line_account->channel_id,
                "redirect_uri" => "http://localhost/line/callback/{$line_account->id}",
                "scope" => "profile openid email",
                "nonce" => hash("sha256", Str::uuid()),
                "state" => hash("sha256", Str::uuid()),
            ];

            return redirect($authorization_url.http_build_query($query_build));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
