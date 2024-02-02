<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransMutasiAktivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_mutasi_aktiva', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->date('date');
            $table->unsignedBigInteger('from_struct_id');
            $table->unsignedBigInteger('to_struct_id');
            $table->string('status')->nullable();
            $table->commonFields();

            $table->foreign('from_struct_id')
                ->references('id')
                ->on('ref_org_structs');
            $table->foreign('to_struct_id')
                ->references('id')
                ->on('ref_org_structs');
        });
        Schema::create('trans_mutasi_aktiva_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedBigInteger('aktiva_id');
            $table->string('status')->nullable();
            $table->commonFields();

            $table->foreign('aktiva_id')
                ->references('id')
                ->on('trans_aktiva');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_mutasi_aktiva');
        Schema::dropIfExists('trans_mutasi_aktiva_detail');
    }
}
