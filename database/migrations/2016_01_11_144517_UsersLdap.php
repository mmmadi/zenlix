<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersLdap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

Schema::create('user_ldap', function(Blueprint $table)
  {


            $table->increments('id');
            $table->timestamps();
            $table->enum('status', ['true', 'false'])->default('false');
            $table->integer('user_id')->unsigned();
            $table->string('login')->nullable();
            $table->enum('authType', ['ldap', 'system'])->default('system');

            //$table->mediumText('password');

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

        Schema::drop('user_ldap');
    }
}
