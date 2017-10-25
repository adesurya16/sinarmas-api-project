<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifiedFilingStatusFieldCanNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verified_submissions', function (Blueprint $table) {
            $table->smallInteger('filing_status')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verified_submissions', function (Blueprint $table) {
            $table->smallInteger('filing_status')->default(0)->change();
        });
    }
}
