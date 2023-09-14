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
        Schema::dropIfExists('manufacture_jobcard_product_dispatch_deliveryzones');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('manufacture_jobcard_product_dispatch_deliveryzones', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');            
            $table->timestamps();
        });
    }
};
