<?php

use App\Http\Controllers\Front\Api\FollowerController;
use App\Http\Controllers\Front\Api\GameTitleController;
use App\Http\Controllers\Front\Api\PlayerController;
use App\Http\Controllers\Front\Api\PlayingGameTitleController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/", "as" => "top."], function () {


    // ゲームタイトルに関するAPI
    Route::group(["prefix" => "title", "as" => "gameTitle."], function () {
        // 現在,登録中のゲームタイトル一覧を取得する
        Route::get("/search", [
            GameTitleController::class, "search",
        ])->name("search");

        Route::post("/create", [
            GameTitleController::class, "create",
        ])->name("create");

        Route::post("/update/{id}", [
            GameTitleController::class, "update",
        ])->name("update");
    });

    // 各ユーザーがプレイしているゲームタイトルについて
    Route::group(["prefix" => "playingGameTitle", "as" => "playingGameTitle."], function () {

        // 指定したユーザーのプレイ中ゲームタイトル一覧を取得する
        Route::get("/{player_id}", [
            PlayingGameTitleController::class, "detail",
        ])->name("detail");

        // 指定したユーザーに新たなプレイ中ゲームタイトルを追加する
        Route::post("/create/{player_id}", [
            PlayingGameTitleController::class, "create",
        ])->name("create");

        // 指定したユーザーの指定したプレイ中ゲームタイトルの情報を編集する
        Route::post("/update/{id}", [
            PlayingGameTitleController::class, "update",
        ])->name("update");
    });

    // ゲームプレイヤーに関するAPI
    Route::group(["prefix" => "player", "as" => "player."], function () {

        // プレイヤーの検索(検索可能なゲームプレイヤー一覧を取得する)
        Route::get("/search", [
            PlayerController::class, "search",
        ])->name("search");

        Route::get("/detail/{id}", [
            PlayerController::class, "detail",
        ])->name("detail");

        Route::post("/update/{id}", [
            PlayerController::class, "update",
        ])->name("update");
    });

    // プレイヤーのフォロワー関係
    Route::group(["prefix" => "follower", "as" => "follower."], function () {

        // 指定したプレイヤーIDがフォローしている一覧を取得する
        Route::get("/following/{player_id}", [
            FollowerController::class, "list",
        ])->name("following");

        // 指定したユーザーをフォローしているプレイヤー
        Route::get("/followed/{player_id}", [
            FollowerController::class, "followed",
        ])->name("followed");

        // 指定したplayerがすでにマッチング済みのプレイヤー一覧を取得する
        Route::get("/matching/{player_id}", [
            FollowerController::class, "matching",
        ])->name("matching");

        // 指定したplayerをフォローする
        Route::post("/create", [
            FollowerController::class, "create",
        ])->name("create");

        // 指定したplayerのフォローを外す
        Route::post("/unfollow", [
            FollowerController::class, "unfollow",
        ])->name("unfollow");
    });
});
