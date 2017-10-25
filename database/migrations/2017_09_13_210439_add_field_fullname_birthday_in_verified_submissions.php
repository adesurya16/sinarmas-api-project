<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldFullnameBirthdayInVerifiedSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verified_submissions', function (Blueprint $table) {
            $table->string('fullname', 255)->nullable();
            $table->date('birthday')->nullable();
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
            $table->dropColumn('fullname');
            $table->dropColumn('birthday');
        });
    }
}
