<?php


use App\Http\Controllers\Admin\Line\AccountController;
use App\Http\Controllers\Admin\Line\MemberController;
use App\Http\Controllers\Admin\Line\ReserveController;
use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/", "as" => "admin."], function () {


    // --------------------------------------------------
    // 管理画面側ログインページ
    // --------------------------------------------------
    Route::group(["middleware" => ["web"], "prefix" => "/login", "as" => "login."], function () {
        Route::get("/", [
            LoginController::class, "index",
        ])->name("index");

        // --------------------------------------------------
        // 認証処理
        // --------------------------------------------------
        Route::post("/authenticate", [
            LoginController::class, "authenticate",
        ])->name("authenticate");

        // --------------------------------------------------
        // 管理画面側ログアウトページ
        // --------------------------------------------------
        Route::get("/logout", [
            LoginController::class, "logout",
        ])->name("logout");
    });

    // --------------------------------------------------
    // 以下は管理画面へログイン済みであることが前提とする
    // --------------------------------------------------
    Route::middleware("auth")->group(function () {
        // --------------------------------------------------
        // LINE関連の処理
        // --------------------------------------------------
        Route::group(["prefix" => "line", "as" => "line."], function () {


            // 公式LINEアカウント一覧
            Route::group(["prefix" => "account", "as" => "account."], function () {
                // 登録済みのLINEアカウント一覧を取得
                Route::get("/", [
                    AccountController::class, "index"
                ])->name("index");

                // 任意のLINEアカウントを表示
                Route::get("/detail/{line_account_id}", [
                    AccountController::class, "detail"
                ])->name("detail");
            });

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

                // 新規LINEメッセージの予約を作成する
                Route::get("/register", [
                    ReserveController::class, "register",
                ])->name("register");
            });

            // LINEログイン済みメンバー情報
            Route::group(["prefix" => "member", "as" => "member."], function () {

                // メンバーページTOP
                Route::get("/", [
                    MemberController::class, "index"
                ])->name("index");

                // 指定したline_member_idの詳細情報
                Route::get("/detail/{line_member_id}", [
                    MemberController::class, "detail"
                ])->name("detail");
            });
        });
    });
});
