<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("line_member_id");
            $table->string("family_name", 512)->nullable();
            $table->string("middle_name", 512)->nullable();
            $table->string("given_name", 512)->nullable();
            $table->string("nickname", 512)->nullable();
            $table->string("email", 2048);
            $table->text("description")->nullable();
            // プロフィール画面には表示させない
            $table->integer("gender_id")->nullable();
            $table->tinyInteger("is_displayed")->default(1);
            $table->tinyInteger("is_deleted")->default(0);
            // プレイヤーの検索結果に表示させるかどうか?
            $table->tinyInteger("is_published")->default(0);
            // プレイヤーのAPIコール用に使用するtoken
            $table->string("api_token", 2048);
            // なんかメモ用に
            $table->text("memo")->nullable();
            $table->timestamps();


            // 外部キー誓約
            $table->foreign("line_member_id")->references("id")->on("line_members");

            // ユニーク誓約
            $table->unique("api_token", "player_api_token");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
