<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
//use DefaultTicketForm;
//use zenlix\TicketForms;
use Carbon\Carbon;

//use \Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(DefaultTicketForm::class);

        Model::reguard();
    }
}

class DefaultTicketForm extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('ticket_forms')->insert([

'id'=>'1',
'name'=>'_default',
'client_field'=>'self',
'target_field'=>'group',
'prio'=>'true',
'subj_field'=>'text',
'upload_files'=>'false',
//'upload_files_types'=>Null,
'upload_files_count'=>'1',
'upload_files_size'=>'1024',
'deadline_field'=>'false',
'watching_field'=>'false',
'individual_ok_field'=>'false',
'check_after_ok'=>'false',
'create_user'=>'false',
'created_at' => Carbon::now(), // remember to import Carbon
'updated_at' => Carbon::now()

        ]);


DB::table('groups')->insert([
                'id'    =>'1',
                'name' => 'Default group',
                'description' => 'Default group',
                'group_urlhash' => 'def_group',
    ]);

DB::table('group_ticket_conf')->insert([
                'group_id' => '1',
                'ticket_form_id' => '1',
                'status' => 'true',
                'group_type'=>'firm',
    ]);

            //GroupTicketConf


//create main user

/*        $user=Zen::storeNewUser([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>$data['password']
        ]);*/


/*Setting::set('test', 'set');
Setting::save();*/

/*
'ticket.days2arch', '2'
'ticket.days2del', '4'
'ticket.code', 'code'
'ticket.codeCount', '5'
'sitename', 'ZENLIX'
'sitenameShort', 'ZEN'
'slogan' , 'Amazing HelpDesk system'
'sitelogo', 'false'
'AuthUsers', 'true'
'RecoveryPasswords', 'true'

'LdapAuth', 'true'

'ticket.ReceiveMail.status', 'true'

'ticket.ReceiveMail.receiveAnon'  'true'
'ticket.ReceiveMail.ServerDirectory'  INBOX
'ticket.ReceiveMail.AuthMail' info@zenlix.com
'ticket.ReceiveMail.AuthAddr' imap.yandex.ru
'ticket.ReceiveMail.AuthPort' 993
'ticket.ReceiveMail.AuthLogin'    info@zenlix.com1
'ticket.ReceiveMail.AuthPass' 372423
'ticket.ReceiveMail.AuthSecurity' /imap/ssl/novalidate-cert
'ticket.ReceiveMail.filter'   UNSEEN
'ticket.ReceiveMail.targetGroup'  1
'ticket.ReceiveMail.watching' 1
'ticket.ReceiveMail.tags' 0
'ticket.ReceiveMail.upload_files' true
'ticket.ReceiveMail.upload_files_types'   jpeg,bmp,png,pdf,doc,docx
'ticket.ReceiveMail.upload_files_count'   5
'ticket.ReceiveMail.upload_files_size'    10000
'mailStatus'  true
'smsStatus'   false
'smsLogin'    rustem_ck1
'pbStatus'    false
'pbKey'   
'smsPassword' 372423
'smsAccess'   
'WPURL'   http://localhost:3001/
'ticket.deadlineNotifyStatus' true
'ticket.deadlineNotify'   2
'ticket.overtimeNotifyStatus' true
'ticket.ReceiveMail.targetUsers'  
'ticket.ReceiveMail.clients'  
'apiStatus'   true


Setting::set('', '');
*/




    }
}