<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToRefOrgStructsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_org_structs', function (Blueprint $table) {
            $table->enum('status', [1,2,3])->comment('1:draft|2:aktif|3:nonaktif');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_org_structs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
