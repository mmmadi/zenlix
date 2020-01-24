<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('files', function(Blueprint $table)
    {
        $table->increments('id');
       // $table->enum('target', ['feed', 'ticket'])->default('feed');
        $table->integer('user_id')->unsigned()->nullable();
        //$table->foreign('user_id')->references('id')->on('users');
        $table->integer('target_id')->unsigned()->nullable();
        $table->string('target_type')->nullable();

        $table->string('name')->nullable();
        $table->string('hash')->nullable();
        $table->string('mime')->nullable();
        $table->string('extension')->nullable();
        $table->enum('status', ['tmp', 'success'])->default('tmp');
        $table->enum('image', ['true', 'false'])->default('false');
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
        Schema::drop('files');
    }
}
