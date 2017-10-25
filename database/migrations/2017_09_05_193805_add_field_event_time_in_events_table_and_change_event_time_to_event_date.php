<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldEventTimeInEventsTableAndChangeEventTimeToEventDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('event_time', 'event_date')->change();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->timeTz('event_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('event_time');
            $table->renameColumn('event_date', 'event_time');
        });
    }
}
