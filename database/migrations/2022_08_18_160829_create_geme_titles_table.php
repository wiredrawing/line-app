<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGemeTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geme_titles', function (Blueprint $table) {
            $table->id();
            $table->string("title", 512);
            // ゲームのプラットフォーム PS系,switch,xbox系
            $table->integer("platform_id");
            $table->text("description");
            // ゲームジャンル
            $table->integer("genre_id");
            $table->tinyInteger("is_displayed")->default(1);
            $table->tinyInteger("is_deleted")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geme_titles');
    }
}
