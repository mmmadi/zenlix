<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class HelpCenter extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //

        Schema::create('help_category', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->integer('parent_id');
            $table->integer('sort_id');
            $table->string('name');
        });

        Schema::create('help', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->longText('description');
            $table->longText('text');
            $table->string('name');
            $table->string('tags');
            $table->string('slug');
            $table->enum('access_all', ['true', 'false'])->default('true');
        });

        Schema::create('help_access', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->integer('help_id')->unsigned();
            $table->integer('group_id')->unsigned();

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
        Schema::drop('help_category');
        Schema::drop('help');
        Schema::drop('help_access');
    }
}
