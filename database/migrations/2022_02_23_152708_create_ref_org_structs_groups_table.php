<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefOrgStructsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_org_structs_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('struct_id');

            $table->foreign('group_id')->references('id')->on('ref_org_structs')->onDelete('cascade');
            $table->foreign('struct_id')->references('id')->on('ref_org_structs');

            $table->unique(['group_id', 'struct_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_org_structs_groups');
    }
}
