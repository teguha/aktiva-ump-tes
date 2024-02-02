<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MataAnggaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mata_anggaran', function (Blueprint $table) {
            $table->id();
            $table->string('mata_anggaran')->unique();
            $table->string('nama')->unique();
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
        Schema::dropIfExists('mata_anggaran');
    }
}
