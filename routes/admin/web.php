<?php


use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\Line\AccountController;
use App\Http\Controllers\Admin\Line\MessageController;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/", "as" => "admin."], function () {


    // --------------------------------------------------
    // 管理画面側ログインページ
    // --------------------------------------------------
    Route::group(["prefix" => "/login", "as" => "login."], function () {
        Route::get("/", [
            LoginController::class, "index",
        ])->name("index");
    });

    // --------------------------------------------------
    // 管理画面側ログアウトページ
    // --------------------------------------------------
    Route::group(["prefix" => "/logout", "as" => "logout."], function () {
        Route::get("/", [
            LogoutController::class, "index",
        ])->name("index");
    });



    // --------------------------------------------------
    // LINE関連の処理
    // --------------------------------------------------
    Route::group(["prefix" => "line", "as" => "line."], function () {

        // 登録済みのLINEアカウント一覧を取得
        Route::get("/account", [
            AccountController::class, "index"
        ])->name("account");

        // LINEメッセージ関連のURL
        Route::group(["prefix" => "message", "as" => "message."], function () {
            // 全メッセージ一覧
            Route::get("/", [
                MessageController::class, "index"
            ])->name("index");

            // 予約済みメッセージ一覧
            Route::get("/reserved", [
                MessageController::class, "reserved"
            ])->name("reserved");

            // 送信済みメッセージ一覧
            Route::get("/sent", [
                MessageController::class, "sent"
            ])->name("sent");
        });
    });
});
