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
            $table->tinyInteger('use_historical_weight_in')->after('comment')->nullable()->default(0);
            $table->string('outsourced_contractor', 50)->after('plant_id')->nullable();
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
            $table->dropColumn('use_historical_weight_in');
            $table->dropColumn('outsourced_contractor');
        });
    }
};
