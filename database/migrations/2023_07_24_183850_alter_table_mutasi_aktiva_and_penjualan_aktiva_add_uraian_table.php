<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableMutasiAktivaAndPenjualanAktivaAddUraianTable extends Migration
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
            'trans_mutasi_aktiva',
            function (Blueprint $table) {
                $table->text('description')
                    ->nullable()->after('to_struct_id');
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
            'trans_mutasi_aktiva',
            function (Blueprint $table) {
                $table->dropColumn('description');

            }
        );
    }
}
