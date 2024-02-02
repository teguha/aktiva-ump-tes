<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransDetailTermin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_detail_termin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('termin_id');
            $table->string('no_termin')->nullable();
            $table->date('tgl_termin')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('nominal')->default(0);
            $table->unsignedBigInteger('pajak')->default(0);
            $table->unsignedBigInteger('total');
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
        Schema::dropIfExists('trans_detail_termin');
    }
}
