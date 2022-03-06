<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_members', function (Blueprint $table) {
            $table->id();
            $table->string("access_token");
            $table->bigInteger("expires_in");
            // JWT形式のデータ
            $table->string("id_token", 2048);
            $table->string("refresh_token");
            $table->string("token_type");
            $table->string("email");
            $table->string("picture");
            $table->string("name");
            $table->string("sub");
            $table->string("aud");
            $table->bigInteger("line_account_id");
            // LINEプラットフォームとは別に本アプリケーション側で扱うトークン
            $table->string("api_token")->nullable();
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
        Schema::dropIfExists('line_members');
    }
}
