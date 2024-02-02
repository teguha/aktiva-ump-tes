<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_vendor', function (Blueprint $table) {
            $table->id();
            $table->string("id_vendor")->nullable($value = false)->unique();
            $table->string("name")->nullable($value = false);
            $table->string("address")->nullable();
            $table->string("telp")->nullable();
            $table->string("email")->nullable();
            $table->string('contact_person')->nullable();
            $table->enum('status', [1, 2, 3])->comment('1:draft|2:aktif|3:nonaktif')->default('1');
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
        Schema::dropIfExists('ref_vendor');
    }
}
