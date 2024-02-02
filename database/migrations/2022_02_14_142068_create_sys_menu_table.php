<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_menu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('module')->unique();
            $table->integer('order')->default(1);
            $table->commonFields();
        });

        Schema::create('sys_menu_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('role_id');
            $table->smallInteger('type')->default(1)->comment('1:sequence/berurutan, 2:pararel/berbarengan');
            $table->integer('order')->default(1);
            $table->commonFields();

            $table->foreign('menu_id')->references('id')->on('sys_menu')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('sys_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_menu_flows');
        Schema::dropIfExists('sys_menu');
    }
}
