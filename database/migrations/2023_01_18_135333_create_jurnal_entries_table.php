<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_template');
            $table->foreign('id_template')->references('id')->on('jurnal_template')->onDelete('cascade');
            $table->unsignedBigInteger('id_coa');
            $table->foreign('id_coa')->references('id')->on('chart_of_accounts')->onDelete('cascade');
            $table->string('jenis')->comment('debit|kredit');
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
        Schema::dropIfExists('jurnal_entries');
    }
}
