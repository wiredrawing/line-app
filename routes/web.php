<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Line\LoginController;
use App\Http\Controllers\Line\LogoutController;
use App\Http\Controllers\Line\CallbackController;
use App\Http\Controllers\Line\MessageController;
use Symfony\Component\Mime\MessageConverter;

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
    return view('welcome');
});


// -------------------------------------------------------------------------
// LINEログイン用のグループURL
// -------------------------------------------------------------------------
Route::group(["prefix" => "line", "as" => "line."], function () {

    // LINEログインが可能なアカウント一覧を表示
    Route::get("/login", [LoginController::class, "index"])->name("login.index");
    // ログイン用コントローラー
    Route::get("/login/{line_account_id}/{application_key}", [LoginController::class, "detail"])->name("login.detail");
    // LINEドメイン側で認証後に戻ってくるweb側のコールバックURL
    Route::get("/callback/{line_account_id}", [CallbackController::class, "index"])->name("callback.index");
    // LINE認証完了後,DBを更新後表示するLINEログイン完了ページ
    Route::get("/callback/completed/{line_account_id}", [CallbackController::class, "completed"])->name("callback.completed");
    // LINEアカウントからログアウトするURL
    Route::get("/logout", [LogoutController::class, "index"])->name("logout.index");

    Route::group(["prefix" => "message", "as" => "message."], function () {
        // 指定したLINEユーザーにメッセージをPushする
        Route::post("/pushing/{line_account_id}", [MessageController::class, "pushing"])->name("pushing");
        // 任意のメッセージを作成および予約日時を指定する
        Route::post("/reserve/{line_account_id}", [MessageController::class, "reserve"])->name("reserve");
    });
});
