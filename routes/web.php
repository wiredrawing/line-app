<?php

use App\Http\Controllers\Line\LineMemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Line\LoginController;
use App\Http\Controllers\Line\LogoutController;
use App\Http\Controllers\Line\CallbackController;
// use App\Http\Controllers\Api\Line\RefreshController;
// use App\Http\Controllers\Api\Line\ReserveController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route("line.login.index");
});


Route::group(["prefix" => "user", "as" => "user.",], function () {
    Route::middleware("auth")->group(function () {
        Route::get("/index", [
            LineMemberController::class,
            "index",
        ]);
    });
});

// -------------------------------------------------------------------------
// LINEログイン用のグループURL
// -------------------------------------------------------------------------
Route::group(["prefix" => "line", "as" => "line."], function () {

    Route::group(["prefix" => "login", "as" => "login."], function () {
        // LINEログインが可能なアカウント一覧を表示
        Route::get("/login", [
            LoginController::class, "index"
        ])->name("index");
        // ログイン用コントローラー
        Route::get("/login/{line_account_id}/{application_key}", [
            LoginController::class, "detail"
        ])->name("detail");
    });


    Route::group(["prefix" => "callback", "as" => "callback."], function () {
        // ---------------------------------------------------------------
        // LINEドメイン側で認証後に戻ってくるweb側のコールバックURL
        // GETパラメータで line_account_idというキーでline_accounts.idを渡す
        // ---------------------------------------------------------------
        Route::get("/", [CallbackController::class, "index"])->name("index");
        // ---------------------------------------------------------------
        // LINE認証完了後,DBを更新後表示するLINEログイン完了ページ
        // GETパラメータで line_account_idというキーでline_accounts.idを渡す
        // ---------------------------------------------------------------
        Route::get("/completed", [CallbackController::class, "completed"])->name("completed");
    });

    // LINEアカウントからログアウトするURL
    Route::get("/logout", [LogoutController::class, "index"])->name("logout.index");
});


// // -------------------------------------------------------------------------
// // API用ルーティンググループ
// // あとでapi.phpに分ける
// // -------------------------------------------------------------------------
// Route::group(["prefix" => "api", "as" => "api."], function () {
//     Route::group(["prefix" => "line", "as" => "line."], function () {
//
//
//
//
//
//         // 修正後は以下
//         Route::group(["prefix" => "reserve", "as" => "reserve."], function () {
//             // 指定したLINEユーザーにメッセージをPushする
//             Route::post("/push/{line_reserve_id}", [ReserveController::class, "push"])->name("push");
//             // 任意のメッセージを作成および予約日時を指定する
//             Route::post("/reserve/{line_account_id}", [ReserveController::class, "reserve"])->name("reserve");
//             // リクエスト時点で未送信のメッセージ一覧を取得する
//             Route::get("unsent/{line_account_id}", [ReserveController::class, "unsentMessages"])->name("unsent");
//             // リクエスト時点で配信済みのメッセージ一覧を取得する
//             Route::get("sent/{line_account_id}", [ReserveController::class, "sentMessages"])->name("sent");
//         });
//
//
//
//     });
// });

// Auth::routes();
//
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//
// Auth::routes();
//
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
