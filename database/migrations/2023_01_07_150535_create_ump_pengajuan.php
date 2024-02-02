<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmpPengajuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_ump_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('new');
            $table->unsignedBigInteger('aktiva_id')->nullable();
            $table->unsignedBigInteger('code_sgu')->nullable();
            $table->unsignedBigInteger('rekening_id')->nullable();
            $table->unsignedBigInteger('struct_id')->nullable();
            $table->string('code_ump')->nullable()->unique();
            $table->date('date_ump')->nullable();
            $table->bigInteger('nominal_pembayaran')->nullable();
            $table->date('tgl_pembayaran')->nullable();
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->text('perihal')->nullable();
            $table->commonFields();

            // $table->foreign('aktiva_id')->references('id')->on('trans_pembelian_aktiva');
            // $table->foreign('code_sgu')->references('id')->on('trans_sgu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ump_pengajuan');
    }
}
