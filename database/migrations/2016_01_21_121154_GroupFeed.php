<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupFeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('group_feed', function(Blueprint $table)
    {
        $table->increments('id');
        $table->mediumText('text');
        $table->enum('target', ['all', 'group', 'user'])->default('all');
        $table->enum('comments_flag', ['true', 'false'])->default('true');
        $table->integer('author_id')->unsigned()->nullable();
        $table->integer('group_id')->unsigned()->nullable();
        $table->enum('mark', ['true', 'false'])->default('false');
        $table->string('feed_urlhash');
        $table->timestamps();
    });

        Schema::create('feed_comments', function(Blueprint $table)
    {
        $table->increments('id');
        $table->mediumText('text');
        $table->integer('author_id')->unsigned()->nullable();
        $table->integer('feed_id')->unsigned()->nullable();
        $table->string('comment_urlhash');
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
        Schema::drop('group_feed');
        Schema::drop('feed_comments');        

    }
}
