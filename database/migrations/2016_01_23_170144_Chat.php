<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Chat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('chat', function(Blueprint $table)
    {
        $table->increments('id');
        //$table->string('subject');        
        $table->mediumText('text');

        //$table->enum('draft_flag', ['false', 'true'])->default('false');
        $table->enum('read_flag', ['false', 'true'])->default('true');        
        //$table->enum('star_flag', ['false', 'true'])->default('false');        
        
        $table->integer('from_user_id')->unsigned()->nullable();
        $table->integer('to_user_id')->unsigned()->nullable();

        //$table->string('message_urlhash');
        $table->timestamps();
        //$table->softDeletes();

    });

                Schema::create('chat_request', function(Blueprint $table)
    {
        $table->increments('id');
        $table->integer('user_id')->unsigned()->nullable();
        $table->integer('chatWith_id')->unsigned()->nullable();
        $table->timestamps();
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

Schema::drop('chat');
Schema::drop('chat_request');


    }
}
