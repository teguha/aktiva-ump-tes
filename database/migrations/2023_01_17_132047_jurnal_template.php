<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JurnalTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_template', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->string('nama_template');
            $table->string('deskripsi')->nullable();
            $table->enum('status', [1,2])->comment('1:baru|2:aktif');
            $table->string('nama_akun')->nullable();
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
        Schema::dropIfExists('jurnal_template');
    }
}
