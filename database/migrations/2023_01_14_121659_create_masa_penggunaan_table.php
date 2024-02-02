<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasaPenggunaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masa_penggunaan', function (Blueprint $table) {
            $table->id();
            $table->integer('masa_penggunaan')->unsigned();
            $table->string('status')->comment('1:draft|2:aktif|3:nonaktif');
            $table->commonFields();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('masa_penggunaan');
    }
}
