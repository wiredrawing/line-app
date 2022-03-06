<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("line_message_id");
            $table->bigInteger("line_account_id");
            $table->string("line_member_sub");
            //-----------------------------------------
            // メッセージは配信予定日を設定できるが
            // 実際に配信された時間を保持させる
            //-----------------------------------------
            $table->dateTime("delivered_at");
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
        Schema::dropIfExists('line_broadcasts');
    }
}
