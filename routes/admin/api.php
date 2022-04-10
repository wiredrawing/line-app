<?php
use App\Http\Controllers\Admin\Api\Line\ReserveController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/", "as" => "admin."], function () {
    Route::group(["prefix" => "/", "as" => "api."], function () {
        Route::group(["prefix" => "line", "as" => "line."], function () {
            Route::group(["prefix" => "reserve", "as" => "reserve."], function () {
                // 指定したLINEユーザーにメッセージをPushする
                Route::post("/push/{line_reserve_id}", [ReserveController::class, "push"])->name("push");
                // 任意のメッセージを作成および予約日時を指定する
                Route::post("/reserve/{line_account_id}", [ReserveController::class, "reserve"])->name("reserve");
                // リクエスト時点で未送信のメッセージ一覧を取得する
                Route::get("unsent/{line_account_id}", [ReserveController::class, "unsentMessages"])->name("unsent");
                // リクエスト時点で配信済みのメッセージ一覧を取得する
                Route::get("sent/{line_account_id}", [ReserveController::class, "sentMessages"])->name("sent");
            });
        });
    });
});
