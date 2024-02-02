<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmpPjUmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_ump_pj', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_ump_id');
            $table->string('id_pj_ump')->unique()->nullable();
            $table->date('tgl_pj_ump')->nullable();
            $table->text('uraian')->nullable();
            $table->string('status')->default('new')->comment('new:Baru|draft:Draft|waiting approval:Menunggu Otorisasi|waiting verification:Menunggu Verifikasi|pay remaining:Pembayaran Selisih|completed:Selesai|revision:Revisi');
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
        Schema::dropIfExists('ump_pj_ump');
    }
}
