<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransOperationCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_operationalCost', function (Blueprint $table) {
            $table->id();
            $table->string("code", 30)->unique();
            $table->date('date');
            $table->unsignedBigInteger("struct_id");
            $table->string("skema_pembayaran");
            $table->string("cara_pembayaran");
            $table->longText("sentence_start")->nullable();
            $table->longText("sentence_end")->nullable();
            $table->string('status')->default('new');
            $table->commonFields();

            $table->foreign("struct_id")
                ->references("id")
                ->on("ref_org_structs");
        });

        Schema::create(
            'trans_operationalCost_details',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger("pengajuan_id");
                $table->unsignedBigInteger("vendor_id");
                $table->string('name')->nullable();
                $table->unsignedBigInteger('cost')->nullable();
                $table->date('tgl_pemesanan')->nullable();
                $table->string('status')->nullable();
                $table->commonFields();

                $table->foreign('pengajuan_id')
                    ->references('id')
                    ->on('trans_operationalCost');
                $table->foreign('vendor_id')
                    ->references('id')
                    ->on('ref_vendor');
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
        Schema::dropIfExists('trans_operationalCost_details');
        Schema::dropIfExists('trans_operationalCost');
    }
}
