<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_activities', function (Blueprint $table) {
            $table->id();
            $table->morphs('target');
            $table->string('module');
            $table->string('message');
            $table->string('ip')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->commonFields();

            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_activities');
    }
}
