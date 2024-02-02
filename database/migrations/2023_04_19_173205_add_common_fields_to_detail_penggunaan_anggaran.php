<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommonFieldsToDetailPenggunaanAnggaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_penggunaan_anggaran', function (Blueprint $table) {
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
        Schema::table('detail_penggunaan_anggaran', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by', 'created_at', 'updated_at']);
        });
    }
}
