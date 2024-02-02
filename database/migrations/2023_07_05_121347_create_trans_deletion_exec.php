<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransDeletionExec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pelaksanaan_penghapusan_aktiva',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('penghapusan_aktiva_id');
                $table->string('status')->default('new');
                $table->string('code')->nullable()->unique();
                $table->date('date')->nullable();
                $table->string('description', 10240)->nullable();
                $table->commonFields();

                $table->foreign('penghapusan_aktiva_id', 'penghapusan_aktiva_id')
                    ->references('id')
                    ->on('trans_penghapusan_aktiva');
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
        Schema::dropIfExists('trans_pelaksanaan_penghapusan_aktiva');
    }
}
