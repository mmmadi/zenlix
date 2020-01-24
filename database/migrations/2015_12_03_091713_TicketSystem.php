<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('ticket_sla_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->string('name');
            $table->integer('reaction_time_def')->unsigned()->default('0');
            $table->integer('reaction_time_low_prio')->unsigned()->default('0');
            $table->integer('reaction_time_high_prio')->unsigned()->default('0');
            
            $table->integer('work_time_def')->unsigned()->default('0');
            $table->integer('work_time_low_prio')->unsigned()->default('0');
            $table->integer('work_time_high_prio')->unsigned()->default('0');
            
            $table->integer('deadline_time_def')->unsigned()->default('0');
            $table->integer('deadline_time_low_prio')->unsigned()->default('0');
            $table->integer('deadline_time_high_prio')->unsigned()->default('0');
        });


        Schema::create('ticket_sla_log', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->integer('ticket_id')->unsigned()->nullable();
            $table->integer('reaction_time')->unsigned()->default('0');
            $table->integer('work_time')->unsigned()->default('0');
            $table->integer('deadline_time')->unsigned()->default('0');
           
           
        });

        
        Schema::create('tickets', function (Blueprint $table) {
            
            $table->increments('id');
            
            $table->integer('author_id')->unsigned()->nullable();
            $table->string('code')->unique(); //add index
            
            $table->integer('client_id')->unsigned()->nullable();

            
            $table->enum('prio', ['low', 'normal', 'high'])->default('normal');
            
            $table->mediumText('text');
            $table->string('subject');
            $table->string('tags')->nullable();;
            $table->string('urlhash')->unique();
            //$table->string('number')->unique();
            
            $table->integer('sla_id')->unsigned()->nullable();
            //$table->foreign('sla_id')->references('id')->on('ticket_sla_plans');
            
            $table->integer('target_group_id')->unsigned()->nullable();
            //$table->foreign('target_group_id')->references('id')->on('groups');
            $table->dateTime('deadline_time')->nullable();
            $table->enum('inspect_after_ok', ['true', 'false'])->default('false');
            $table->enum('individual_ok', ['true', 'false'])->default('false');
            $table->enum('overtime', ['true', 'false'])->default('false');
            //$table->enum('status', ['new', 'lock', 'high'])->default('normal');
            $table->enum('planner_flag', ['true', 'false'])->default('false');
            $table->enum('merge_flag', ['true', 'false'])->default('false');

            $table->softDeletes();
            $table->enum('status', ['free', 'lock', 'waitsuccess', 'success', 'arch'])->default('free');


            $table->timestamps();
        });



/*        Schema::create('ticket_planner', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->integer('ticket_id')->unsigned()->nullable();
            $table->integer('author_id')->unsigned()->nullable();
            $table->enum('period', ['day', 'week', 'month'])->default('day');
            $table->integer('dayHour')->unsigned()->nullable();
            $table->integer('dayMinute')->unsigned()->nullable();
            $table->integer('weekDay')->unsigned()->nullable();
            $table->integer('monthDay')->unsigned()->nullable();
            $table->dateTime('startWork')->nullable();
            $table->dateTime('endWork')->nullable();

        });
        Schema::create('ticket_planner_log', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('ticket_id')->unsigned()->nullable();
            $table->integer('planner_id')->unsigned()->nullable();

        });*/


        
        Schema::create('ticket_fields_structure', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->enum('f_type', ['text', 'textarea', 'select', 'multiselect'])->default('text');
            $table->enum('required', ['true', 'false'])->default('false');
            $table->mediumText('field_name');
            $table->string('field_value');
            $table->string('field_placeholder');
            $table->string('field_hash');
        });
        
        Schema::create('ticket_fields_data', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('ticket_field_id')->unsigned()->nullable();
            //$table->foreign('ticket_field_id')->references('id')->on('ticket_fields_structure');
            $table->integer('ticket_id')->unsigned()->nullable();

            $table->mediumText('field_data');
        });

/*        Schema::create('ticket_field', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('field_id')->unsigned()->nullable();
            //$table->foreign('field_id')->references('id')->on('ticket_fields_data');
            $table->integer('ticket_id')->unsigned()->nullable();
            //$table->foreign('ticket_id')->references('id')->on('tickets');
            
            //$table->mediumText('field_data');
            
        });*/


        
        Schema::create('ticket_target_user', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            
            $table->integer('ticket_id')->unsigned()->nullable();
            //$table->foreign('ticket_id')->references('id')->on('tickets');
            
            $table->enum('individual_ok_status', ['false', 'true'])->default('false');
            $table->enum('individual_lock_status', ['false', 'true'])->default('false');
        });

        Schema::create('ticket_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            
            $table->integer('ticket_id')->unsigned()->nullable();
            //$table->foreign('ticket_id')->references('id')->on('tickets');
            
            //$table->enum('individual_ok_status', ['false', 'true'])->default('false');
        });

//те кто увидел заявку 
//через web


        Schema::create('ticket_watched', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            
            $table->integer('ticket_id')->unsigned()->nullable();
            //$table->foreign('ticket_id')->references('id')->on('tickets');
            
            //$table->enum('individual_ok_status', ['false', 'true'])->default('false');
        });

        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('text');
            
            $table->integer('author_id')->unsigned()->nullable();
            //$table->foreign('author_id')->references('id')->on('users');
            
            $table->integer('ticket_id')->unsigned()->nullable();
            //$table->foreign('ticket_id')->references('id')->on('tickets');
            
            $table->enum('visible_client', ['true', 'false'])->default('true');
            
            $table->string('urlhash');
            
            $table->timestamps();
        });
        
        Schema::create('ticket_watching', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            $table->integer('ticket_id')->unsigned()->nullable();
            //$table->foreign('ticket_id')->references('id')->on('tickets');
        });
        
        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('author_id')->unsigned()->nullable();
            //$table->foreign('author_id')->references('id')->on('users');
            $table->integer('ticket_id')->unsigned()->nullable();
            //$table->foreign('ticket_id')->references('id')->on('tickets');
            
            $table->enum('action', ['create', 'refer', 'comment', 'lock','lockNext', 'unlock', 'ok', 'unok','unokNext', 'arch', 'delete', 'restore', 'edit','waitok','approve','noapprove'])->default('create');
            
            $table->string('description');
            
            //create by USER to GROUP/USER
            //refer by USER to GROUP/USER
            //comment by USER
            //lock by USER
            //unlock by USER
            //ok by USER
            //un_ok
            //arch 

           // \Event::fire(new TicketLogger($ticket_id, $author_id, $action, $description));
            
            
        });
        
        Schema::create('ticket_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->enum('client_field', ['self', 'group'])->default('self');
            //+=form_client_groups
            //add client lists or groups
            
            $table->enum('target_field', ['user_groups', 'group', 'users'])->default('user_groups');
            //+=form_target_groups
            //+=form_target_users
            $table->enum('prio', ['true', 'false'])->default('true');
            
            $table->enum('subj_field', ['text', 'list'])->default('text');
            //$table->string('subj_list')->nullable();
            //+=form_subjs_lists
            
            $table->enum('upload_files', ['true', 'false'])->default('true');
            $table->string('upload_files_types')->nullable();
            $table->integer('upload_files_count')->unsigned()->nullable();
            $table->integer('upload_files_size')->unsigned()->nullable();            
            
            $table->enum('deadline_field', ['false', 'true'])->default('false');
            
            $table->enum('watching_field', ['false', 'true'])->default('false');

            $table->enum('create_user', ['false', 'true'])->default('false');
            
            $table->enum('individual_ok_field', ['false', 'true'])->default('false');
            $table->enum('check_after_ok', ['false', 'true'])->default('false');
        });
        



        Schema::create('form_subj_lists', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->integer('ticket_form_id')->unsigned()->nullable();
            $table->integer('subj_id')->unsigned()->nullable();
        });
        Schema::create('ticket_subj', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();

        });
        Schema::create('ticket_subj_lists', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->integer('ticket_subj_id')->unsigned()->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
        });


        Schema::create('user_ticket_conf', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            $table->integer('ticket_form_id')->unsigned()->nullable()->default('1');;
            //$table->foreign('ticket_form_id')->references('id')->on('ticket_forms');
            $table->enum('conf_params', ['group', 'user'])->default('user');
            
            $table->integer('group_conf_id')->unsigned()->nullable();
            //$table->foreign('group_conf_id')->references('id')->on('groups');
        });

        Schema::create('form_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('field_id')->unsigned()->nullable();
            //$table->foreign('field_id')->references('id')->on('ticket_fields_structure');
            
            $table->integer('form_id')->unsigned()->nullable();
            //$table->foreign('form_id')->references('id')->on('ticket_forms');
        });
        
        Schema::create('form_sla', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            //$table->string('name')->nullable();
            $table->integer('sla_id')->unsigned()->nullable();
            //$table->foreign('sla_id')->references('id')->on('ticket_sla_plans');
            $table->integer('form_id')->unsigned()->nullable();
            //$table->foreign('form_id')->references('id')->on('ticket_forms');
        });
        
        Schema::create('form_client_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('client_group_id')->unsigned()->nullable();
            //$table->foreign('target_group_id')->references('id')->on('groups');
            $table->integer('form_id')->unsigned()->nullable();
            //$table->foreign('form_id')->references('id')->on('ticket_forms');
        });


        Schema::create('form_target_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('target_group_id')->unsigned()->nullable();
            //$table->foreign('target_group_id')->references('id')->on('groups');
            $table->integer('form_id')->unsigned()->nullable();
            //$table->foreign('form_id')->references('id')->on('ticket_forms');
        });
        
        Schema::create('form_target_users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('target_user_id')->unsigned()->nullable();
            //$table->foreign('target_user_id')->references('id')->on('users');
            $table->integer('form_id')->unsigned()->nullable();
            //$table->foreign('form_id')->references('id')->on('ticket_forms');
        });
        
        Schema::create('group_ticket_conf', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('group_id')->unsigned()->nullable();
            //$table->foreign('group_id')->references('id')->on('groups');
            $table->integer('ticket_form_id')->unsigned()->nullable();
            //$table->foreign('ticket_form_id')->references('id')->on('ticket_forms');
            $table->enum('status', ['true', 'false'])->default('false');
            
            $table->enum('group_type', ['client', 'firm'])->default('client');
        });
        
        Schema::create('group_ticket_users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            $table->integer('group_id')->unsigned()->nullable();
            //$table->foreign('group_id')->references('id')->on('groups');
        });
        
        Schema::create('group_ticket_superusers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            $table->integer('group_id')->unsigned()->nullable();
            //$table->foreign('group_id')->references('id')->on('groups');
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
        Schema::drop('group_ticket_conf');
        Schema::drop('group_ticket_users');
        Schema::drop('group_ticket_superusers');
        
        Schema::drop('ticket_forms');
        Schema::drop('form_target_groups');
        Schema::drop('form_target_users');
        Schema::drop('form_fields');
        Schema::drop('form_sla');
        
        Schema::drop('ticket_sla_plans');
        Schema::drop('tickets');
        Schema::drop('ticket_target_client');
        Schema::drop('ticket_logs');
        Schema::drop('ticket_watching');
        Schema::drop('ticket_field');
        Schema::drop('ticket_fields_data');
        Schema::drop('ticket_fields_structure');
        Schema::drop('ticket_comments');
        Schema::drop('user_ticket_conf');


    }
}
