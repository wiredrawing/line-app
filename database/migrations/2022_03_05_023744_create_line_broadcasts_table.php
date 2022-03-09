<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     * 指定したLineメッセージを配信するユーザー一覧を保持
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("line_reserve_id");
            $table->string("line_member_id");
            //-----------------------------------------
            // メッセージは配信予定日を設定できるが
            // 実際に配信された時間を保持させる
            //-----------------------------------------
            $table->dateTime("delivered_at");
            $table->timestamps();

            $table->unique([
                "line_reserve_id",
                "line_member_id",
            ], "line_reserve_id_line_member_id");
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
