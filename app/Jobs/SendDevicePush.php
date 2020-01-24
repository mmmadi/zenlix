<?php

namespace zenlix\Jobs;

use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use zenlix\Jobs\Job;

class SendDevicePush extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    protected $deviceHash, $title, $message;

    const ZENLIX_PUSH_SERVER = "http://api3.zenlix.com/";
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deviceHash, $title, $message)
    {
        //
        $this->deviceHash = $deviceHash;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $client = new Client;

        $res = $client->post(self::ZENLIX_PUSH_SERVER, [
            'body' => [
                'deviceHash' => $this->deviceHash,
                'title' => $this->title,
                'message' => $this->message,
            ],
        ]);

    }
}
