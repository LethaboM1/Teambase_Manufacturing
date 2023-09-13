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
        Schema::table('manufacture_jobcard_product_dispatches', function (Blueprint $table) {
            $table->string('delivery_zone', 20)->after('reference')->default('0');
            $table->float('dispatch_temp', 6,2)->after('delivery_zone')->nullable();
            $table->dropColumn('haulier_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacture_jobcard_product_dispatches', function (Blueprint $table) {
            $table->dropColumn('delivery_zone');
            $table->dropColumn('dispatch_temp');
            $table->string('haulier_code', 100)->after('reference');
        });
    }
};
