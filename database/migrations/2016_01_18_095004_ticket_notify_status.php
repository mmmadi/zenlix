<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketNotifyStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

Schema::create('ticket_notify_status', function(Blueprint $table)
  {

            $table->increments('id');
            $table->timestamps();
            $table->integer('ticket_id')->unsigned();
            $table->enum('deadline_flag', ['true', 'false'])->default('false');
            $table->enum('overtime_flag', ['true', 'false'])->default('false');
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
        Schema::drop('ticket_notify_status');
        
    }
}
