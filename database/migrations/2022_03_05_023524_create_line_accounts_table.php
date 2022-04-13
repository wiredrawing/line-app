<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_accounts', function (Blueprint $table) {
            $table->id();
            // LINEログイン
            $table->string("channel_id", 512);
            $table->string("channel_secret", 512);
            $table->string("user_id", 512);
            // MessagingAPI
            $table->string("messaging_channel_id", 512);
            $table->string("messaging_channel_secret", 512);
            $table->string("messaging_user_id", 512);
            $table->string("messaging_channel_access_token", 512)->nullable();
            $table->string("webhook_url", 512)->nullable();
            // 本アプリケーション内でのみ使用するAPIトークン
            $table->string("api_token")->nullable();
            // 本アプリケーションをある程度非公開にするためのアプリケーションキー
            $table->string("application_key", 512)->nullable();
            $table->tinyInteger("is_enabled")->default(1);
            $table->tinyInteger("is_hidden")->default(0);
            $table->timestamps();

            // unique 制約
            $table->unique("messaging_channel_access_token");
            // LINEログインAPI系
            $table->unique([
                "channel_id",
                "channel_secret",
            ]);
            // メッセージングAPI系
            $table->unique([
                "messaging_channel_id",
                "messaging_channel_secret",
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
        Schema::dropIfExists('line_accounts');
    }
}
