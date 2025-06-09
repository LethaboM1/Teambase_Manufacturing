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
            $table->tinyInteger('dispatch_admin')->default('0')->after('dispatch_transfer_approve');            
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
            $table->dropColumn('dispatch_admin');                        
        });
    }
};
