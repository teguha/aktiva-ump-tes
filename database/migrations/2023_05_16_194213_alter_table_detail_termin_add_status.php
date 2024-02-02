<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDetailTerminAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_detail_termin', function (Blueprint $table) {
            $table->string('status')->nullable()->default('Belum Dibayar')->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_detail_termin', function(Blueprint $table){
            $table->dropColumn('status');
        });
    }
}
