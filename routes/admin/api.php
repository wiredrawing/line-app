<?php

use App\Http\Controllers\Admin\Api\Line\RefreshController;
use App\Http\Controllers\Admin\Api\Line\ReserveController;
use App\Http\Controllers\Admin\Api\Line\AccountController;
use App\Http\Controllers\Admin\Api\Line\MemberController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/", "as" => "admin."], function () {
    Route::group(["prefix" => "/", "as" => "api."], function () {

        Route::group(["prefix" => "line", "as" => "line."], function () {
            // ------------------------------------------------------
            // フロントエンド用のLINEメッセージの操作用API
            // ------------------------------------------------------
            Route::group(["prefix" => "reserve", "as" => "reserve."], function () {
                // 指定したLINEユーザーにメッセージをPushする
                Route::post("/push/{line_reserve_id}", [ReserveController::class, "push"])->name("push");
                // 任意のメッセージを作成および予約日時を指定する
                Route::post("/reserve/{line_account_id}", [ReserveController::class, "reserve"])->name("reserve");
                // リクエスト時点で未送信のメッセージ一覧を取得する
                Route::get("/unsent/{line_account_id}", [ReserveController::class, "unsentMessages"])->name("unsent");
                // リクエスト時点で配信済みのメッセージ一覧を取得する
                Route::get("/sent/{line_account_id}", [ReserveController::class, "sentMessages"])->name("sent");
                // 任意の指定したLINEメッセージ予約を編集用データとして返却する
                Route::get("/fetchReserve/{line_reserve_id}", [
                    ReserveController::class, "fetchReserve",
                ])->name("fetchReserve");
                // 任意の指定したLINEメッセージ予約の更新処理を行う
                Route::post("/update/{line_reserve_id}", [
                    ReserveController::class, "update",
                ])->name("update");
            });

            // 登録済みのLineMemberのaccess_tokenをリフレッシュする
            Route::group(["prefix" => "refresh", "as" => "refresh."], function () {
                Route::post("/index", [
                    RefreshController::class, "index",
                ])->name("index");
            });

            // ------------------------------------------------------
            // LINEオフィシャルアカウント関連のAPI
            // ------------------------------------------------------
            Route::group(["prefix" => "account", "as" => "account."], function () {

                // 登録済みLINEアカウント一覧を取得する
                Route::get("/list", [
                    AccountController::class, "list"
                ])->name("list");

                // 新規でLINEアカウントを登録する前のバリデーションチェック
                Route::post("/check", [
                    AccountController::class, "check"
                ])->name("check");

                // 新規でLINEアカウントを登録する
                Route::post("/create", [
                    AccountController::class, "create"
                ])->name("create");

                // 指定したLINE公式アカウントを更新する
                Route::post("/update/{line_account_id}", [
                    AccountController::class, "update"
                ])->name("update");

                Route::get("/detail/{line_account_id}/{api_token}", [
                    AccountController::class, "detail"
                ])->name("detail");
            });

            // ------------------------------------------------------
            // 現在登録中のLINEメンバー関連のAPI
            // ------------------------------------------------------
            Route::group(["prefix" => "member", "as" => "member."], function () {

                // 登録済みメンバー一覧
                Route::get("/list", [
                    MemberController::class, "list"
                ])->name("list");

                // 登録済みメンバー一覧
                Route::get("/detail/{line_member_id}", [
                    MemberController::class, "detail",
                ])->name("detail");
            });
        });
    });
});
