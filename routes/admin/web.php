<?php


use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\Line\AccountController;
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
    // 登録済みのLINEアカウント一覧を取得
    Route::group(["prefix" => "line", "as" => "line."], function () {
        Route::get("/account", [
            AccountController::class, "index"
        ])->name("account");
    });
});
