<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransUmpPembayaranaddTglJatuhTempo extends Migration
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
            'trans_ump_pembayaran',
            function (Blueprint $table) {
                $table->date('tgl_jatuh_tempo')->nullable();
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
            'trans_ump_pembayaran',
            function (Blueprint $table) {
                $table->dropColumn('tgl_jatuh_tempo');
            }
        );
    }
}
