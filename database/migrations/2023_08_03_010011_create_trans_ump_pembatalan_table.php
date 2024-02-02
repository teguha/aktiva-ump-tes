<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransUmpPembatalanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_ump_pembatalan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_ump_id');
            $table->string('id_ump_pembatalan')->unique()->nullable();
            $table->date('tgl_ump_pembatalan')->nullable();
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
        Schema::dropIfExists('trans_ump_pembatalan');
    }
}
