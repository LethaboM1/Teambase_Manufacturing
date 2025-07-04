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
            $table->text('delivery_address')->after('reference')->nullable();
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
            $table->dropColumn('delivery_address');
        });
    }
};
