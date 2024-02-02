<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChartOfAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('kode_akun')->unsigned()->unique();
            $table->string('nama_akun')->unique();
            $table->enum('tipe_akun', ['laba rugi', 'pendapatan', 'biaya', 'neraca', 'aset', 'kewajiban', 'ekuitas']);
            $table->string('deskripsi')->nullable();
            $table->enum('status', [1,2,3])->comment('1:draft|2:aktif|3:nonaktif');
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
        Schema::dropIfExists('chart_of_accounts');
    }
}
