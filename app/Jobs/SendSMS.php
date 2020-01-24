<?php

namespace zenlix\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Setting;
use SMSCenter\SMSCenter;
use zenlix\Jobs\Job;

//use zenlix\User;

class SendSMS extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $tel, $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tel, $message)
    {
        //
        $this->tel = $tel;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//    dd($this->message);
        /*smsStatus
        smsLogin
        smsPassword*/

        if (Setting::get('smsStatus') == 'true') {
            $login = Setting::get('smsLogin');
            $pass = Setting::get('smsPassword');

            $smsc = new SMSCenter($login, md5($pass), false, [
                'charset' => SMSCenter::CHARSET_UTF8,
                'fmt' => SMSCenter::FMT_XML,
            ]);

            if (!empty($this->tel)) {
                $smsc->send($this->tel, $this->message);
            }

        }

    }

}
