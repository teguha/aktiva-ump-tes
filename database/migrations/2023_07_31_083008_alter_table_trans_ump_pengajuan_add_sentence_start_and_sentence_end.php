<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransUmpPengajuanAddSentenceStartAndSentenceEnd extends Migration
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
            'trans_ump_pengajuan',
            function (Blueprint $table) {
                $table->longText("sentence_start")->nullable()->after('perihal');
                $table->longText("sentence_end")->nullable()->after('sentence_start');
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
            'trans_ump_pengajuan',
            function (Blueprint $table) {
                $table->dropColumn('sentence_start');
                $table->dropColumn('sentence_end');

            }
        );
    }
}
