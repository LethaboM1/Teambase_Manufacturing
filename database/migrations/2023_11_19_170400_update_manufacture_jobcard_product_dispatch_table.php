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
            $table->bigInteger('job_id')->after('customer_id')->default(0);
            $table->bigInteger('product_id')->after('job_id')->default(0);
            $table->bigInteger('manufacture_jobcard_product_id')->after('product_id')->default(0);
            $table->decimal('qty', 10, 3)->after('weight_out_user_id')->nullable();
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
            $table->dropColumn('job_id');
            $table->dropColumn('product_id');
            $table->dropColumn('manufacture_jobcard_product_id');
            $table->dropColumn('qty');
        });
    }
};
