<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Calendar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

Schema::create('calendar', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->string('title')->nullable();
            $table->dateTime('dtStart')->nullable();
            $table->dateTime('dtStop')->nullable();

            $table->enum('allday', ['true', 'false'])->default('false');

            $table->string('backgroundColor')->nullable();
            $table->string('borderColor')->nullable();

            $table->mediumText('description');

            $table->integer('user_id')->nullable();
            $table->string('uniq_hash');

            $table->enum('personal', ['true', 'false'])->default('true');  

        });

Schema::create('calendar_group', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('event_id')->nullable();
            $table->integer('group_id')->nullable();
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

        Schema::drop('calendar');
        Schema::drop('calendar_group');

    }
}
