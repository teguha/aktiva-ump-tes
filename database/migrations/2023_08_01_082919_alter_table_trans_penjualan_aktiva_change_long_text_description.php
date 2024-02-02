<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPenjualanAktivaChangeLongTextDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(
            'trans_penjualan_aktiva',
            function (Blueprint $table) {
                $table->longText('description')->nullable(true)->change();

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
        //
        Schema::table(
            'trans_penjualan_aktiva',
            function (Blueprint $table) {
                $table->string('description')->nullable(true)->change();

            }
        );
    }
}
