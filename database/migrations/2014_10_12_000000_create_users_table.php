<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('user_profiles', function(Blueprint $table)
  {
    $table->integer('user_id')->unsigned()->nullable();
    $table->string('user_img')->nullable();
    $table->string('user_cover')->nullable();
    $table->string('lang')->nullable()->default('en');
    $table->string('full_name')->nullable();
    $table->string('user_urlhash')->nullable(); //add index
    $table->string('sms')->nullable();
    $table->string('pb')->nullable();
    $table->string('telephone')->nullable();
    $table->string('skype')->nullable();
    $table->string('address')->nullable();
    $table->string('position')->nullable();
    $table->integer('birthdayDay')->nullable();
    $table->integer('birthdayMonth')->nullable();
    $table->integer('birthdayYear')->nullable();
    $table->string('email')->nullable();
    $table->string('facebook')->nullable();
    $table->string('twitter')->nullable();
    $table->string('website')->nullable();
    $table->string('about', 512)->nullable();
    $table->string('skills', 512)->nullable();
    $table->timestamps();
  });

        Schema::create('user_roles', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->nullable();
            $table->enum('role', ['admin', 'user', 'client'])->default('client');
          });


/*

user roles
        - admin (admin access)
        - user 
        - client 



roles:
  -admin
  -user
  -client

users

user_roles
  user_id
  role_id



if ($user->roles == 'admin')




*/

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('user_profiles');
        Schema::drop('user_roles');
    }
}
