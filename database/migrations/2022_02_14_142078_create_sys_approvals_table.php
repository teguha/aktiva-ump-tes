<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_approvals', function (Blueprint $table) {
            $table->id();
            $table->morphs('target');
            $table->string('module');
            $table->unsignedBigInteger('role_id');
            $table->smallInteger('type')->default(1)->comment('1:sequence/berurutan, 2:pararel/berbarengan');
            $table->integer('order')->default(1);
            $table->string('status')->default('new')->comment('new|rejected|approved|authorized');
            $table->unsignedBigInteger('user_id')->nullable();
            // $table->unsignedBigInteger('position_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->commonFields();

            $table->foreign('role_id')->references('id')->on('sys_roles');
            $table->foreign('user_id')->references('id')->on('sys_users');
            // $table->foreign('position_id')->references('id')->on('ref_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_approvals');
    }
}
