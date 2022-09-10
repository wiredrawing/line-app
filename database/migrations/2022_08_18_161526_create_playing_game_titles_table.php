<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayingGameTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playing_game_titles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("player_id");
            $table->bigInteger("game_title_id");
            // 当該ゲームのレベル
            $table->integer("skill_level");
            // 遊ぶ頻度 1日/週 or 7日/週
            $table->integer("frequency");
            // そのゲームのアカウントを登録(そのゲーム上で一意に識別できるID
            $table->string("game_account_id", 512)->nullable();
            $table->text("memo")->nullable();
            $table->timestamps();

            // 外部キー誓約
            $table->foreign("player_id")->references("id")->on("players");
            $table->foreign("game_title_id")->references("id")->on("game_titles");

            // ユニークキー誓約
            $table->unique([
                "player_id",
                "game_title_id",
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playing_game_titles');
    }
}
