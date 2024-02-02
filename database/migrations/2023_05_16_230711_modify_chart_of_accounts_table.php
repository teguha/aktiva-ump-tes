<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyChartOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('chart_of_accounts', function (Blueprint $table) {
        //     $table->enum('tipe_akun_new', ['laba rugi', 'pendapatan', 'biaya', 'neraca', 'aset', 'kewajiban', 'ekuitas'])->nullable();
        // });

        // DB::statement('UPDATE chart_of_accounts SET tipe_akun_new = tipe_akun');

        // Schema::table('chart_of_accounts', function (Blueprint $table) {
        //     $table->dropColumn('tipe_akun');
        //     $table->renameColumn('tipe_akun_new', 'tipe_akun');
        //     $table->integer('kode_akun')->unsigned()->nullable()->change();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('chart_of_accounts', function (Blueprint $table) {
        //     $table->dropColumn('tipe_akun');
        //     $table->renameColumn('tipe_akun_new', 'tipe_akun');
        // });

        // DB::statement('UPDATE chart_of_accounts SET tipe_akun_new = tipe_akun');

        // Schema::table('chart_of_accounts', function (Blueprint $table) {
        //     $table->integer('kode_akun')->unsigned()->unique()->change();
        //     $table->enum('tipe_akun_new', ['laba rugi', 'pendapatan', 'biaya', 'neraca', 'aset', 'kewajiban', 'ekuitas']);
        // });
    }
}
