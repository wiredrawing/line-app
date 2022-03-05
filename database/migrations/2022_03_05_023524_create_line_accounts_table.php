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
            $table->string("channel_id");
            $table->string("channel_secret");
            $table->string("user_id");
            // MessagingAPI
            $table->string("messaging_channel_id");
            $table->string("messaging_channel_secret");
            $table->string("messaging_user_id");
            $table->string("messaging_channel_access_token")->nullable();
            $table->string("webhook_url")->nullable();
            $table->tinyInteger("is_enabled")->default(1);
            $table->tinyInteger("is_hidden")->default(0);
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
        Schema::dropIfExists('line_accounts');
    }
}
