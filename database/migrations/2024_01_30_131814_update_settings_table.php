<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('trade_name', 100)->after('jobcard_service_d_hours');
            $table->string('reg_no', 30)->after('trade_name')->nullable();
            $table->string('vat_no', 30)->after('reg_no')->nullable();
            $table->string('tel_no', 20)->after('vat_no')->nullable();
            $table->string('fax_no', 20)->after('tel_no')->nullable();
            $table->string('mobile', 20)->after('tel_no')->nullable();
            $table->string('email', 150)->after('fax_no')->nullable();
            $table->string('url', 150)->after('email')->nullable();
            $table->text('physical_add')->after('url')->nullable();
            $table->text('postal_add')->after('physical_add')->nullable();
            $table->text('logo')->after('postal_add')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('trade_name');
            $table->dropColumn('reg_no');
            $table->dropColumn('vat_no');
            $table->dropColumn('tel_no');
            $table->dropColumn('fax_no');
            $table->dropColumn('mobile');
            $table->dropColumn('email');
            $table->dropColumn('url');
            $table->dropColumn('physical_add');
            $table->dropColumn('postal_add');
            $table->dropColumn('logo');
        });
    }
};
