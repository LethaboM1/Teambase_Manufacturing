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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('request_type')->default('');
            $table->string('request_model')->default('');
            $table->bigInteger('request_model_id')->default('0');
            $table->bigInteger('requesting_user_id')->default('0');
            $table->bigInteger('approving_user_id')->default('0');            
            $table->tinyInteger('approved')->default('0');
            $table->tinyInteger('declined')->default('0');
            $table->longText('request_detail_array')->nullable();            
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
        Schema::dropIfExists('approvals');
    }
};
