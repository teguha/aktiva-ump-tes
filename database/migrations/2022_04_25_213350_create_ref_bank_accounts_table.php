<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('number');
            $table->string('bank');
            $table->commonFields();

            $table->foreign('user_id')->references('id')->on('sys_users');
        });

        Schema::create('ref_cost_components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
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
        Schema::dropIfExists('ref_cost_components');
        Schema::dropIfExists('ref_bank_accounts');
    }
}
