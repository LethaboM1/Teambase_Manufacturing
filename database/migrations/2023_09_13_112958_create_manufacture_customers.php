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
        Schema::create('manufacture_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('credit')->default(0);
            $table->string('account_number', 100);
            $table->string('vat_number', 30)->nullable();
            $table->string('contact_name', 100)->nullable();
            $table->string('contact_number', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacture_customers');
    }
};
