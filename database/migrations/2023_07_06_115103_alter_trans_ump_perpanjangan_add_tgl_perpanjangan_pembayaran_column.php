<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransUmpPerpanjanganAddTglPerpanjanganPembayaranColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_ump_perpanjangan',
            function (Blueprint $table) {
                $table->date('tgl_perpanjangan_pembayaran')
                    ->after('tgl_ump_perpanjangan')
                    ->nullable();
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
        Schema::table(
            'trans_ump_perpanjangan',
            function (Blueprint $table) {
                $table->dropColumn('tgl_perpanjangan_pembayaran');
            }
        );
    }
}
