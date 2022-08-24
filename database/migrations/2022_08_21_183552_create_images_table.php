<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            // 本テーブルは物理削除仕様とする
            $table->uuid("id")->primary();
            $table->string("filename", 512);
            $table->string("extension", 512);
            // アップロード画像がアップされたそのタイミングの日時
            // created_atでも代用できるかカラムに複数の用途を持たせるべきではないので
            // 個別にこの項目を用意する
            $table->dateTime("uploaded_at");
            $table->timestamps();

            $table->unique("filename");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
