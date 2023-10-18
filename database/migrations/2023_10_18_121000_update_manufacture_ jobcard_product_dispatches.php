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
            $table->bigInteger('customer_id')->after('registration_number')->default(0);
            $table->bigInteger('product_id')->after('customer_id');
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
            $table->dropColumn('customer_id');
            $table->dropColumn('product_id');
        });
    }
};
