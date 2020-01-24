<?php

namespace zenlix\Console\Commands;

use File;
use GuzzleHttp\Client;
//use Setting;
use Illuminate\Console\Command;

class ZenlixLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zenlix:license';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking ZENLIX license';

    //const LICENSE_CHECK_URL = "http://license.zenlix.com/check";
    const LICENSE_CHECK_URL = "http://license.zenlix.com/check.php";
    //const DEF_KEY = "mLs5ANj372tmKnIc1PHWV98jyK1xnwpG";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $ZEN_LICENSE = storage_path('license');

        $this->line('Finding license file...');

        if ($this->checkFile()) {
            $this->info('Success!');

            $this->line('Connecting to license server...');
            if ($this->checkLicenseServerConnect()) {
                $this->info('Success!');

                $this->line('Sending request to license server...');

                $resp = $this->checkResponse();

                if ($resp) {
                    $this->info('Success!');

                    $this->line('Parsing response...');
                    if ($this->storeLicense($resp)) {
                        $this->info('Success!');
                    } else {
                        $this->error('Could not storing license file!');
                    }
//$this->parseResponse($resp);

                } else {
                    $this->error('Could not understand response!');
                }

            } else {
                $this->error('Could not connect to license server!');
            }

//$licenseContent=$this->readLicense();
            //$licenseKey

        } else {
            $this->error('License file not found!');
        }

//echo "ok";

    }

    public function checkFile()
    {

        if (File::exists(storage_path('license'))) {
            return true;
        } else {
            return false;
        }

    }

    public function readLicense()
    {

        return File::get(storage_path('license'));

    }

    public static function checkLicenseServerConnect()
    {

        $content_json = @file_get_contents(self::LICENSE_CHECK_URL);

        if ($content_json === false) {
            return false;
        }
        return true;

    }

    public function prepareLicenseRequest()
    {

        $data = [
            'license_content' => $this->readLicense(),
        ];

        $data = json_encode($data);

        return $data;

    }

    public function makeLicenseRequest()
    {

        $client = new Client;

        $res = $client->post(self::LICENSE_CHECK_URL, [
            'body' => [
                'data' => $this->prepareLicenseRequest(),
            ],
        ]);

        return $res->json();

    }

//json_decode($response->getBody()) now instead of $response->json()

    public function showResponse()
    {

        return $this->makeLicenseRequest();

    }

    public function checkResponse()
    {

        $resArr = $this->showResponse();

        if (isset($resArr['license_content'])) {
            return $resArr['license_content'];
        } else {
            return false;
        }

    }

    public function storeLicense($response)
    {

//File::delete(storage_path('license'));

        $bytes_written = File::put(storage_path('license'), $response);
        if ($bytes_written === false) {
            return false;

        }
        return true;

    }

}
