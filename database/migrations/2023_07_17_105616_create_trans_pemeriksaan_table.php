<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPemeriksaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pemeriksaan',
            function (Blueprint $table) {
                $table->id();
                $table->string('status')->nullable();
                $table->string('code');
                $table->date('date');
                $table->unsignedBigInteger('struct_id');
                $table->string('description', 10240);
                $table->commonFields();

                $table->foreign('struct_id')
                    ->references('id')
                    ->on('ref_org_structs');
            }
        );
        Schema::create(
            'trans_pemeriksaan_pemeriksa',
            function (Blueprint $table) {
                $table->unsignedBigInteger('pemeriksaan_id');
                $table->unsignedBigInteger('user_id');

                $table->foreign('pemeriksaan_id')
                    ->references('id')
                    ->on('trans_pemeriksaan');
                $table->foreign('user_id')
                    ->references('id')
                    ->on('sys_users');
            }
        );
        Schema::create(
            'trans_pemeriksaan_detail',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pemeriksaan_id');
                $table->unsignedBigInteger('aktiva_id');
                $table->string('condition');
                $table->string('description', 10240);
                $table->commonFields();

                $table->foreign('pemeriksaan_id')
                    ->references('id')
                    ->on('trans_pemeriksaan');
                $table->foreign('aktiva_id')
                    ->references('id')
                    ->on('trans_aktiva');
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
        Schema::dropIfExists('trans_pemeriksaan_detail');
        Schema::dropIfExists('trans_pemeriksaan_pemeriksa');
        Schema::dropIfExists('trans_pemeriksaan');
    }
}
