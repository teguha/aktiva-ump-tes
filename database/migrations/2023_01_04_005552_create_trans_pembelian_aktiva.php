<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPembelianAktiva extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pembelian_aktiva',
            function (Blueprint $table) {
                $table->id();
                $table->string("code", 30)->unique();
                $table->date('date');
                $table->unsignedBigInteger("struct_id");
                $table->string("skema_pembayaran");
                $table->string("cara_pembayaran");
                $table->longText("sentence_start")->nullable();
                $table->longText("sentence_end")->nullable();
                $table->string('status')->default('new');
                $table->commonFields();

                $table->foreign("struct_id")
                    ->references("id")
                    ->on("ref_org_structs");
            }
        );

        Schema::create(
            'trans_pembelian_aktiva_detail',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger("pengajuan_pembelian_id");
                $table->unsignedBigInteger("vendor_id");
                $table->unsignedBigInteger("struct_id");
                $table->string('jenis_asset')->nullable();
                $table->string('nama_aktiva')->nullable();
                $table->string('merk')->nullable();
                $table->string('no_seri')->nullable();
                $table->integer('jumlah_unit_pembelian')->nullable();
                $table->integer('harga_per_unit')->nullable();
                $table->unsignedBigInteger('total_harga')->nullable();
                $table->integer('masa_penggunaan')->unsigned()->nullable();
                $table->date('tgl_pembelian')->nullable();
                $table->date('habis_masa_depresiasi')->nullable();
                $table->date('tgl_mulai_depresiasi')->nullable();
                $table->date('habis_masa_amortisasi')->nullable();
                $table->date('tgl_mulai_amortisasi')->nullable();
                $table->string('status')->nullable();
                $table->commonFields();

                $table->foreign('pengajuan_pembelian_id')
                    ->references('id')
                    ->on('trans_pembelian_aktiva');
                $table->foreign('vendor_id')
                    ->references('id')
                    ->on('ref_vendor');
                $table->foreign('struct_id')
                    ->references('id')
                    ->on('ref_org_structs');
            }
        );
        Schema::create(
            'trans_aktiva',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger("pembelian_aktiva_detail_id");
                $table->string("code", 30)->unique();
                $table->unsignedBigInteger("struct_id");
                $table->unsignedBigInteger("mutasi_aktiva_detail_id")->nullable();
                $table->commonFields();

                $table->foreign('pembelian_aktiva_detail_id')
                    ->references('id')
                    ->on('trans_pembelian_aktiva_detail');
                $table->foreign('struct_id')
                    ->references('id')
                    ->on('ref_org_structs');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_aktiva');
        Schema::dropIfExists('trans_pembelian_aktiva_detail');
        Schema::dropIfExists('trans_pembelian_aktiva');
    }
}
