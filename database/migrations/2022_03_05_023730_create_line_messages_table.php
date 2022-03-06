<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("line_account_id");
            $table->string("type", 512);
            $table->string("text", 5000);
            // 配信予定日
            $table->dateTime("delivery_datetime");
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
        Schema::dropIfExists('line_messages');
    }
}
