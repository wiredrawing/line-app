<?php


use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\Line\AccountController;
use App\Http\Controllers\Admin\Line\ReserveController;
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
        Route::group(["prefix" => "reserve", "as" => "reserve."], function () {
            // 全メッセージ一覧
            Route::get("/", [
                ReserveController::class, "index"
            ])->name("index");

            // 指定したline_reserve_idのメッセージ詳細を取得する
            Route::get("/detail/{line_reserve_id}", [
                ReserveController::class, "detail"
            ])->name("detail");

            // 送信済みメッセージ一覧
            Route::get("/sent", [
                ReserveController::class, "sent"
            ])->name("sent");
        });
    });
});
