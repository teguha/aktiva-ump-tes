<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRefOrgStructsAddProvinceCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table(
            'ref_org_structs',
            function (Blueprint $table) {
                $table->unsignedBigInteger('city_id')->nullable();
                // $table->string('email')->after('name')->nullable();
                // $table->string('website')->after('email')->nullable();
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
            'ref_org_structs',
            function (Blueprint $table) {
                $table->dropColumn('city_id');
                // $table->dropColumn('email');
                // $table->dropColumn('website');

            }
        );
    }
}
