<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefOrgStructsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_org_structs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('level')->comment('root, bod, division, branch');
            $table->unsignedTinyInteger('type')->default(0)
                ->comment('1:presdir, 2:direktur, 3:ia division, 4:it division');
            $table->string('name');
            $table->unsignedInteger('code');
            $table->string('phone', 20)->nullable();
            $table->string('website', 128)->nullable();
            $table->string('email', 128)->nullable();
            $table->text('address')->nullable();
            $table->commonFields();

            $table->foreign('parent_id')->references('id')->on('ref_org_structs');
        });

        Schema::create('ref_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('name');
            $table->unsignedInteger('code');
            $table->commonFields();

            $table->foreign('location_id')->references('id')->on('ref_org_structs');
        });

        Schema::table('sys_users', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable()->after('password');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('ref_org_structs');
            $table->foreign('position_id')->references('id')->on('ref_positions');
        });

        Schema::table('sys_approvals', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable()->after('user_id');
            $table->foreign('position_id')->references('id')->on('ref_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sys_approvals', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });

        Schema::table('sys_users', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
            $table->dropColumn('location_id');
        });

        Schema::dropIfExists('ref_positions');
        Schema::dropIfExists('ref_org_structs');
    }
}
