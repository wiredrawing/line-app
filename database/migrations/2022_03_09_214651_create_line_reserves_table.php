<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_reserves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("line_account_id");
            $table->tinyInteger("is_displayed")->default(1);
            // 配信予定日時
            $table->dateTime("delivery_datetime");
            // 実際に配信されたかどうか
            // 配信処理後はフラグを 0 => 1 に変更
            $table->tinyInteger("is_sent")->default(0);
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
        Schema::dropIfExists('line_reserves');
    }
}
