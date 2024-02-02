<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPenggunaanAnggaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penggunaan_anggaran', function (Blueprint $table) {
            $table->id();
            $table->string('penggunaan')->nullable();
            $table->unsignedBigInteger('id_mata_anggaran');
            $table->unsignedBigInteger('id_pj_ump');
            $table->unsignedBigInteger('nominal');
            $table->foreign('id_mata_anggaran')->references('id')->on('mata_anggaran');
            // $table->foreign('id_pj_ump')->references('id')->on('ump_pj_ump');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penggunaan_anggaran');
    }
}
