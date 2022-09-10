<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_titles', function (Blueprint $table) {
            $table->id();
            $table->string("title", 512);
            // ゲームのプラットフォーム PS系,switch,xbox系
            $table->integer("platform_id");
            $table->text("description");
            // ゲームジャンル
            $table->integer("genre_id");
            $table->tinyInteger("is_displayed")->default(Config("const.binary_type.on"));
            $table->tinyInteger("is_deleted")->default(Config("const.binary_type.off"));
            $table->bigInteger("created_by")->nullable();
            $table->bigInteger("updated_by")->nullable();
            $table->timestamps();

            // 外部キー誓約
            $table->foreign("created_by")->references("id")->on("players");
            $table->foreign("updated_by")->references("id")->on("players");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_titles');
    }
}
