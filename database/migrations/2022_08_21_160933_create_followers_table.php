<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("from_player_id");
            $table->bigInteger("to_player_id");
            // フォロー関係がマッチした日時
            $table->dateTime("matched_at")->nullable();
            $table->timestamps();

            // 外部key制約
            $table->foreign("from_player_id")->references("id")->on("players");
            $table->foreign("to_player_id")->references("id")->on("players");

            // ユニーク制約
            $table->unique([
                "from_player_id",
                "to_player_id",
            ], "from_player_id_to_player_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
