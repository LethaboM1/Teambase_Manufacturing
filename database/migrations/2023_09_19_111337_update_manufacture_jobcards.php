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
        Schema::table('manufacture_jobcards', function (Blueprint $table) {
            $table->integer('internal_jobcard')->after('jobcard_number')->default('1')->nullable();
            $table->integer('customer_id')->after('internal_jobcard')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacture_jobcards', function (Blueprint $table) {
            $table->dropColumn('internal_jobcard');
            $table->dropColumn('customer_id');
        });
    }
};
