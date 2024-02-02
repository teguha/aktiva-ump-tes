<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransSgu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_sgu', function (Blueprint $table) {
            $table->id();
            $table->string("submission_id", 30)->unique()->nullable();;
            $table->date("submission_date")->nullable();
            $table->unsignedBigInteger("termin_id")->nullable();
            $table->date("termin_date")->nullable();
            $table->unsignedBigInteger("work_unit_id")->nullable();
            $table->string("payment_scheme")->nullable();

            //detail
            $table->longText("sentence_start")->nullable();
            $table->longText("sentence_end")->nullable();
            $table->text("rent_location")->nullable();
            $table->date("rent_start_date")->nullable();
            $table->date("rent_end_date")->nullable();
            $table->string('rent_time_period', 30)->nullable();

            $table->bigInteger("deposit")->nullable();
            $table->bigInteger("rent_cost")->nullable();
            $table->date("depreciation_start_date")->nullable();
            $table->date("depreciation_end_date")->nullable();
            $table->date("payment_date")->nullable();
            $table->bigInteger("depreciation_total")->nullable();

            $table->string("status")->nullable();
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
        Schema::dropIfExists('trans_sgu');
    }
}
