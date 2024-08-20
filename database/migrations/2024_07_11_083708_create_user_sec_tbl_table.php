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
        Schema::create('user_sec_tbl', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unique();
            $table->tinyInteger('global_admin')->default('0');
            $table->tinyInteger('settings_admin')->default('0');
            $table->string('user_profile_crud')->default('0100');
            $table->string('user_man_crud')->default('0000');
            $table->string('customer_crud')->default('0100');
            $table->string('supplier_crud')->default('0100');
            $table->string('products_crud')->default('0100');
            $table->tinyInteger('products_adjustment_request')->default('0');
            $table->tinyInteger('products_adjustment_approve')->default('0');
            $table->string('recipes_crud')->default('0100');
            $table->string('jobcards_crud')->default('0100');
            $table->string('lab_tests_crud')->default('0100');
            $table->string('dispatch_crud')->default('0100');
            $table->tinyInteger('dispatch_transfer_request')->default('0');
            $table->tinyInteger('dispatch_transfer_approve')->default('0');
            $table->tinyInteger('dispatch_returns')->default('0');
            $table->tinyInteger('receive_stock')->default('0');
            $table->tinyInteger('reports_dispatch')->default('0');
            $table->tinyInteger('reports_labs')->default('0');
            $table->tinyInteger('reports_stock')->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sec_tbl');
    }
};
