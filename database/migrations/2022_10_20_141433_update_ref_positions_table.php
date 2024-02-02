<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRefPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_positions', function (Blueprint $table) {
            $table->unsignedBigInteger('kelompok_id')->nullable()->after('location_id');

            $table->foreign('kelompok_id')->references('id')->on('ref_kelompok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_positions', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn('kelompok_id');
        });
    }
}
