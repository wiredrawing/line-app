<?php

use App\Http\Controllers\Front\Api\GameTitleController;
use App\Http\Controllers\Front\Api\PlayerController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/", "as" => "top."], function () {


    // ゲームタイトルに関するAPI
    Route::group(["prefix" => "title", "as" => "gameTitle."], function () {
        // 現在,登録中のゲームタイトル一覧を取得する
        Route::get("/list", [
            GameTitleController::class, "list",
        ])->name("list");

        Route::post("/create", [
            GameTitleController::class, "create",
        ])->name("create");

        Route::post("/update/{game_title_id}", [
            GameTitleController::class, "update",
        ])->name("update");
    });

    // ゲームプレイヤーに関するAPI
    Route::group(["prefix" => "player", "as" => "player."], function () {

        // 検索可能なゲームプレイヤー一覧を取得する
        Route::get("/list", [
            PlayerController::class, "list",
        ])->name("list");

        // プレイヤーの検索
        Route::get("/search", [
            PlayerController::class, "search",
        ])->name("search");

        Route::get("/detail/{player_id}", [
            PlayerController::class, "detail",
        ])->name("detail");

        Route::post("/update/{player_id}", [
            PlayerController::class, "update",
        ])->name("update");
    });
});
