<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_images', function (Blueprint $table) {
            // 本テーブルは物理削除仕様とする
            $table->uuid("id")->primary();
            $table->uuid("image_id");
            $table->bigInteger("player_id");
            $table->tinyInteger("is_displayed")->default(Config("const.binary_type.on"));
            $table->timestamps();

            // 外部キー誓約
            $table->foreign("player_id")->references("id")->on("players");
            $table->foreign("image_id")->references("id")->on("images");
            // ユニーク誓約
            $table->unique([
                "image_id",
                "player_id",
            ], "image_id_player_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_images');
    }
}
