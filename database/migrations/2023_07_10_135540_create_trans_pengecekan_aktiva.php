<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengecekanAktiva extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengecekan_aktiva',
            function (Blueprint $table) {
                $table->id();
                $table->string('code');
                $table->date('date');
                $table->string('status')->nullable();
                $table->unsignedBigInteger('struct_id');
                $table->string('description')->nullable();
                $table->commonFields();

                $table->foreign('struct_id')
                    ->references('id')
                    ->on('ref_org_structs');
            }
        );
        Schema::create(
            'trans_pengecekan_aktiva_detail',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pengecekan_id');
                $table->unsignedBigInteger('aktiva_id');
                $table->string('status')->nullable();
                $table->commonFields();

                $table->foreign('pengecekan_id')
                    ->references('id')
                    ->on('trans_pengecekan_aktiva');
                $table->foreign('aktiva_id')
                    ->references('id')
                    ->on('trans_aktiva');
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
        Schema::dropIfExists('trans_pengecekan_aktiva_detail');
        Schema::dropIfExists('trans_pengecekan_aktiva');
    }
}
