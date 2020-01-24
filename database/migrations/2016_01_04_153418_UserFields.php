<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


Schema::create('user_fields_struct', function(Blueprint $table)
  {

$table->increments('id');

$table->enum('status', ['true', 'false'])->default('true');
$table->enum('visible_client', ['true', 'false'])->default('false');
$table->enum('field_type', ['text', 'textarea', 'select', 'multiselect'])->default('text');
$table->string('name');
$table->string('placeholder');
$table->string('value')->nullable();
$table->timestamps();

  });

Schema::create('user_fields_data', function(Blueprint $table)
  {

            $table->increments('id');
            $table->timestamps();
            $table->integer('user_field_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->mediumText('field_data');

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
Schema::drop('user_fields_struct');
Schema::drop('user_fields_data');


    }
}
