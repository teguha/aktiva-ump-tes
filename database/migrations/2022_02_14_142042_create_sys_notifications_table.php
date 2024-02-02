<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_notifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('target');
            $table->string('module');
            $table->string('message');
            $table->string('url');
            $table->commonFields();
        });

        Schema::create('sys_notifications_users', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('readed_at')->nullable();

            $table->foreign('notification_id')->references('id')->on('sys_notifications')->onDelete('cascade');
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
        Schema::dropIfExists('sys_notifications_users');
        Schema::dropIfExists('sys_notifications');
    }
}
