<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekeningBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekening_bank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            // $table->string('no_rekening', 25);
            $table->string('kcp')->nullable();
            $table->string('nama_pemilik')->nullable();
            $table->string('status')->comment('draft:Draft|active:Aktif|nonactive:Non Aktif')->default('active');
            $table->unsignedBigInteger('bank_id');
            $table->commonFields();

            $table->foreign('user_id')
                    ->on('sys_users')
                    ->references('id')->onDelete('cascade');

            $table->foreign('vendor_id')
                    ->on('ref_vendor')
                    ->references('id')->onDelete('cascade');

            $table->foreign('bank_id')
                    ->on('bank')
                    ->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rekening_bank');
    }
}
