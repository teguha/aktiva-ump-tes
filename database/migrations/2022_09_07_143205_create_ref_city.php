<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ref_city',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('province_id');
                $table->string('code', 4);
                $table->string('name', 32);
                $table->commonFields();

                $table->foreign('province_id')
                    ->on('ref_province')
                    ->references('id');
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
        Schema::dropIfExists('ref_city');
    }
}
