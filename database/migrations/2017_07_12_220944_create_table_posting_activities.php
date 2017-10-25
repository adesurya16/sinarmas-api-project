<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePostingActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posting_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tree_id');
            $table->unsignedInteger('profiles_id');
            $table->unsignedInteger('event_id');
            $table->string('location')->nullable();
            $table->text('caption')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tree_id')->references('id')->on('tree_species');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('profiles_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posting_activities');
    }
}
