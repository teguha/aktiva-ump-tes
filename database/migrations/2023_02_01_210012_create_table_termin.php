<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTermin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_termin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code_sgu')->nullable();
            $table->unsignedBigInteger('aktiva_id')->nullable();
            $table->unsignedBigInteger('struct_id')->nullable();
            $table->string('code')->nullable();
            $table->date('date')->nullable();
            $table->text('perihal')->nullable();
            $table->unsignedBigInteger('nominal_pembayaran')->nullable();
            $table->string('status')->default('new');
            $table->commonFields();
        });


        Schema::create('trans_termin_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('termin_id')->nullable();
            $table->string('status')->default('new');
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
        Schema::dropIfExists('trans_termin_pembayaran');
        Schema::dropIfExists('trans_termin');
    }
}
