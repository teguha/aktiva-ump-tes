<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ref_district',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('city_id');
                $table->string('code', 6);
                $table->string('name', 32);
                $table->commonFields();

                $table->foreign('city_id')
                    ->on('ref_city')
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
        Schema::dropIfExists('ref_district');
    }
}
