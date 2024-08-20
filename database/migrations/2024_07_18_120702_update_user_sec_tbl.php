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
        Schema::table('user_sec_tbl', function (Blueprint $table){ 
            $table->string('production_crud')->default('0100')->after('jobcards_crud');
            $table->tinyInteger('return_stock')->default('0')->after('receive_stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_sec_tbl', function (Blueprint $table) {
            $table->dropColumn('production_crud');            
            $table->dropColumn('return_stock');
        });
    }
};
