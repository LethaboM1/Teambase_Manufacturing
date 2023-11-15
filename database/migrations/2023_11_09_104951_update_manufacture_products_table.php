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
        Schema::table('manufacture_products', function (Blueprint $table) {
            $table->tinyInteger('weighed_product')->after('filled')->default(0);              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacture_products', function (Blueprint $table) {
            $table->dropColumn('weighed_product');                        
        });
    }
};
