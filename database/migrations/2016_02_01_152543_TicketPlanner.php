<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketPlanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('ticket_planner', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->integer('ticket_id')->unsigned()->nullable();
            $table->integer('author_id')->unsigned()->nullable();
            $table->enum('period', ['day', 'week', 'month'])->default('day');
            $table->integer('dayHour')->unsigned()->nullable();
            $table->integer('dayMinute')->unsigned()->nullable();
            $table->integer('weekDay')->unsigned()->nullable();
            $table->integer('monthDay')->unsigned()->nullable();
            $table->dateTime('startWork')->nullable();
            $table->dateTime('endWork')->nullable();

        });

        Schema::create('ticket_planner_log', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->integer('ticket_id')->unsigned()->nullable();
            $table->integer('planner_id')->unsigned()->nullable();

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('ticket_planner');
        Schema::drop('ticket_planner_log');
    }
}
