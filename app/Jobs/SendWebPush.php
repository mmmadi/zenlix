<?php

namespace zenlix\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;
use zenlix\Jobs\Job;

class SendWebPush extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $login, $title, $message, $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($login, $title, $message, $url)
    {
        //

        $this->login = $login;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        Redis::publish('ZEN-channel', json_encode([
            'msgType' => 'webPush',
            'login' => $this->login,
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,

        ]));

    }
}
