<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificationMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

                Schema::create('notification_menu', function(Blueprint $table)
    {
        $table->increments('id');
        $table->integer('user_id')->unsigned()->nullable();
        $table->integer('author_id')->unsigned()->nullable();

        $table->integer('ticket_id')->unsigned()->nullable();
        $table->enum('action', ['create', 'refer', 'comment', 'lock','lockNext', 'unlock', 'ok', 'unok','unokNext', 'arch', 'delete', 'restore', 'edit','waitok','approve','noapprove'])->default('create');
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
        Schema::drop('notification_menu');
    }
}
