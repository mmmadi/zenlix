<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersNotifyConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

Schema::create('user_notify', function(Blueprint $table)
  {

            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->enum('target', ['mail', 'sms'])->default('mail');
            $table->string('type')->nullable();

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
        Schema::drop('user_notify');
    }
}
