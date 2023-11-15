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
        Schema::table('manufacture_product_transactions', function (Blueprint $table) {
            $table->bigInteger('dispatch_id')->after('id')->default(0);  
            $table->float('dispatch_temp', 6,2)->after('qty')->nullable();            
            $table->bigInteger('manufacture_jobcard_product_id')->after('product_id')->default(0);          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacture_product_transactions', function (Blueprint $table) {
            $table->dropColumn('dispatch_id');            
            $table->dropColumn('dispatch_temp');            
            $table->dropColumn('manufacture_jobcard_product_id');
        });
    }
};
