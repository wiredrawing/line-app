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
            // 本アプリケーション上の表示フラグ
            $table->tinyInteger("is_displayed")->default(1);
            // LINEプラットフォームが提供するLINEユーザーのID
            $table->string("sub");
            $table->string("aud");
            $table->bigInteger("line_account_id");
            // ---------------------------------------------------------
            // LINEプラットフォームとは別に本アプリケーション側で扱うトークン
            // api_tokenカラムはログインの度に更新される
            // ---------------------------------------------------------
            $table->string("api_token")->nullable();
            $table->timestamps();

            $table->unique("api_token", "line_member_api_token");

            // 本アプリケーション側のユーザー用トークン
            $table->unique([
                "line_account_id",
                "api_token",
            ], "line_account_id_api_token");

            // ユニーク制約
            // LINE側のユーザーのID
            $table->unique([
                "line_account_id",
                "sub",
            ], "line_account_id_sub");
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
