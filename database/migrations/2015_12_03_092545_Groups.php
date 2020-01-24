<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Groups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
                Schema::create('groups', function(Blueprint $table)
    {
        $table->increments('id');
        $table->string('name');
        $table->string('description');
        $table->string('cover')->nullable();
        $table->string('icon')->nullable();
        $table->enum('status', ['public', 'private'])->default('public');
        $table->longText('description_full')->nullable();
        $table->string('slogan')->nullable();
        $table->string('address')->nullable();
        $table->string('tags')->nullable();
        $table->string('facebook')->nullable();
        $table->string('twitter')->nullable();
        $table->string('group_urlhash'); // add index
        
        $table->timestamps();
    });

        Schema::create('user_groups', function(Blueprint $table)
    {
        $table->integer('user_id')->unsigned()->index();
        //$table->foreign('user_id')->references('id')->on('users');
        $table->integer('group_id')->unsigned()->index();
        //$table->foreign('group_id')->references('id')->on('groups');
        $table->enum('priviliges', ['admin', 'user'])->default('user');
        $table->enum('status', ['wait', 'success'])->default('wait');
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
                Schema::drop('groups');
        Schema::drop('user_groups');
        
    }
}
