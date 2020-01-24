<?php

namespace zenlix\Console\Commands;

use File;
use Illuminate\Console\Command;

class ZenlixUpdate extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zenlix:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update zenlix system.';

    const UPDATE_CHECK_URL = 'http://update.zenlix.com/check.php';
    const UPDATE_DIST_URL = 'http://update.zenlix.com/files/zenlix-update.zip';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        //$this->cmd=$cmd;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        //

/*

1. check version
2. download zip
2.1 make file/DB backup
2.2 delete old backup
3. unpack zip
4. run migrations

 */

/*$this->line($this->showNewVersionNum());
dd('ok');*/

        $ZEN_ARCHIVE = base_path() . '/updates/zenlix-master.zip';
        @unlink($ZEN_ARCHIVE);

        $this->line('Connecting to ZENLIX UPDATE server...');

        if ($this->checkUpdateServerConnect()) {

            $this->line('Checking for new version...');
            if ($this->checkVersion()) {
                $this->line('New version ' . $this->showNewVersionNum() . ' is available!');

                //$this->line(' ');
                $this->output->progressStart(5);
                $this->line(' ');
                $this->line('Getting new version from remote server...');

                if ($this->getFile(self::UPDATE_DIST_URL, 'zenlix-master.zip')) {
                    //$this->line(' ');
                    $this->output->progressAdvance();
                    $this->line(' ');
                    $this->info('Success!');

                    $this->line('Unpacking new version...');

                    if ($this->unpackNewVersion()) {

                        //$this->line(' ');
                        $this->output->progressAdvance();
                        $this->line(' ');

                        $this->info('Success!');
                        $this->line('Running migrations...');

                        if ($this->storeMigrations()) {

                            //$this->line(' ');
                            $this->output->progressAdvance();
                            $this->line(' ');
                            $this->info('Success!');

                            $this->line('Cleaning updates...');
                            @unlink($ZEN_ARCHIVE);
                            //$this->line(' ');
                            $this->output->progressAdvance();
                            $this->line(' ');

                            $this->info('Success!');

                            $this->updateVersionNum();

                            $this->output->progressFinish();
                            $this->line(' ');
                            $this->info('Update succefully!');
                            $this->info('You have latest version v.' . $this->showNewVersionNum() . ' !');

                        } else {
                            $this->error('Failed!');
                        }

                    } else {
                        $this->error('Failed!');
                    }

                } else {
                    $this->error('Error downloading!');
                }

            } else {
                $this->info('You have latest version!');
            }

        } else {
            $this->error('Could not connect to update server!');
        }

    }

    public function storeMigrations() {

        try {
            parent::call('migrate', [
                '--force' => true,
            ]);
        } catch (Exception $e) {
            return parent::error($e->getMessage());
        }

        return true;
    }

    public static function unpackNewVersion() {
        //$this->line('Unpacking new version...');
        $ZEN_ARCHIVE = base_path() . '/updates/zenlix-master.zip';
        $zip = new \ZipArchive;
        if ($zip->open($ZEN_ARCHIVE) === true) {
            $zip->extractTo(base_path() . '/');
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    public static function updateVersionNum() {

        $path2env = base_path('.env');
        $oldVersion = config('app.zenlix_version');
        $newVersion = static::showNewVersionNum();

        if (file_exists($path2env)) {

            file_put_contents($path2env, str_replace(
                'ZENLIX_VERSION=' . $oldVersion, 'ZENLIX_VERSION=' . $newVersion, file_get_contents($path2env)
            ));
        }

    }

    /**
     * Downloads a file and stores it in the local filesystem
     * @param string $url
     * @param string$path
     * @return string with error messages
     */
    public static function getFile($url, $path) {

/*$contents = File::get($url);
if ($contents === false)
{
die("Couldn't fetch the file.");
}*/

        $error = '';

        if (!file_exists(base_path() . '/updates/')) {
            File::makeDirectory(base_path() . '/updates/', $mode = 0777, true, true);
        }

        $fp = fopen(base_path() . '/updates/' . $path, 'w+');
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $page = curl_exec($ch);
        if (!$page) {
            echo curl_error($ch);
            return false;
            //exit;
        }

        curl_close($ch);
        fclose($fp);

        return true;
//dd('ok');
    }

    public static function checkUpdateServerConnect() {

        $content_json = @file_get_contents(self::UPDATE_CHECK_URL);

        if ($content_json === false) {
            return false;
        }
        return true;

    }

    /**
     * @return mixed
     */
    public static function showNewVersionNum() {

        $content_json = file_get_contents(self::UPDATE_CHECK_URL);
        $json_responce = json_decode($content_json, true);
        return $json_responce['version'];
    }

    public static function checkVersion() {

        $current_version = config('app.zenlix_version');
        $newVersion = static::showNewVersionNum();

        //$newVersion='3.0.2';

        if ($current_version < $newVersion) {
            return true;
        } else {
            return false;
        }

    }

}
