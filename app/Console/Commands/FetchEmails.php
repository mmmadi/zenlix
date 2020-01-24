<?php

namespace zenlix\Console\Commands;

use Event;
use File;
use Illuminate\Console\Command;
use PhpImap\Mailbox as ImapMailbox;
use Setting;
use Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;
use zenlix\Classes\Zen;
use zenlix\Events\TicketLogger;
use zenlix\Events\TicketNotify;
use zenlix\Events\UserNotify;
use zenlix\Files;
use zenlix\Ticket;
use zenlix\TicketComments;
use zenlix\User;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching e-mail for tickets creation.';

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
     * @return mixed
     */
    public function ticketCodeGenerate()
    {
        $selTicketCode = Setting::get('ticket.code');
        do {
            if ($selTicketCode == "autoinc") {
//get last ticket id
                $ticket = Ticket::orderBy('created_at', 'desc')->first();
                $codeGen = $ticket->id + 1;
            } else {
                $randNum = Setting::get('ticket.codeCount', '4');

                $codeGen = strtoupper(str_random($randNum));
            }

        } while (Ticket::where('code', $codeGen)->count() != 0);

        return $codeGen;
    }

    /**
     * @param $attachments
     * @param $textPlain
     * @param $textHtml
     * @return mixed
     */
    public static function showBody($attachments, $textPlain, $textHtml)
    {

        $attFlagPlain = false;
        $attFlagHTML = false;
        foreach ($attachments as $attachment) {

            $fileMime = File::mimeType($attachment->filePath);
//echo $fileMime;

            if ($fileMime == 'text/plain') {
                $attFlagPlain = true;
                $PlainTextContents = File::get($attachment->filePath);
                //File::delete($attachment->filePath);
            } else if ($fileMime == 'text/html') {
                $attFlagHTML = true;
                $HTMLTextContents = File::get($attachment->filePath);
                //File::delete($attachment->filePath);

            }

        }

        $mailMessage = $textPlain;
        if (empty($textPlain)) {

            if (empty($textHtml)) {
                //do some with attachements
                if ($attFlagPlain == true) {
                    $mailMessage = $PlainTextContents;
                } else {
                    $mailMessage = 'empty';
                    if ($attFlagHTML == true) {
                        $mailMessage = clean($HTMLTextContents);
                    }

                }

            } else {
                $mailMessage = clean($textHtml);
            }

        }

        return $mailMessage;
    }

    /**
     * @param $userMail
     */
    public static function checkExistsUser($userMail)
    {

        return User::where('email', $userMail)->exists();

    }

    /**
     * @param $fromName
     * @param $fromAddress
     * @param $newPass
     * @return mixed
     */
    public static function storeNewUser($fromName, $fromAddress, $newPass)
    {

        $AuthorUser = Zen::storeNewUser([
            'name' => $fromName,
            'email' => $fromAddress,
            'password' => $newPass,
        ]);

        Event::fire(new UserNotify($AuthorUser->id, $newPass, 'create'));
        return $AuthorUser;
    }

    /**
     * @param $AuthorUser
     * @param $mailMessageClean
     * @param $mailSubject
     * @param $attachments
     */
    public function storeTicket($AuthorUser, $mailMessageClean, $mailSubject, $attachments)
    {

        $confFileSize = Setting::get('ticket.ReceiveMail.upload_files_size');
        $confFileCount = Setting::get('ticket.ReceiveMail.upload_files_count');
        $confFileTypes = Setting::get('ticket.ReceiveMail.upload_files_types');

        $confFileStatus = Setting::get('ticket.ReceiveMail.upload_files');

        (empty(Setting::get('ticket.ReceiveMail.targetGroup'))) ? $ticketDef_TargetGroup = null : $ticketDef_TargetGroup = Setting::get('ticket.ReceiveMail.targetGroup');

        $ticketDef_TargetUsers = Setting::get('ticket.ReceiveMail.targetUsers');
        $ticketDef_Client = Setting::get('ticket.ReceiveMail.clients');
        $ticketDef_watchingUsers = Setting::get('ticket.ReceiveMail.watching');
        $tags = Setting::get('ticket.ReceiveMail.tags');

        $code = $this->ticketCodeGenerate();
        $urlhash = str_random(10);

        $ticket = Ticket::create([
            'author_id' => $AuthorUser->id,
            'code' => $code,
            'prio' => 'normal',
            'text' => $mailMessageClean,
            'subject' => $mailSubject,
            'tags' => $tags,
            'urlhash' => $urlhash,
            'target_group_id' => $ticketDef_TargetGroup,
        ]);

        if (!empty($ticketDef_Client)) {
            $ticket->clients()->attach(explode(',', $ticketDef_Client));
        }
        if (!empty($ticketDef_TargetUsers)) {
            $ticket->targetUsers()->attach(explode(',', $ticketDef_TargetUsers));
        }
        if (!empty($ticketDef_watchingUsers)) {
            $ticket->watchingUsers()->attach(explode(',', $ticketDef_watchingUsers));
        }
        Event::fire(new TicketLogger($ticket->id, $AuthorUser->id, 'create'));
        Event::fire(new TicketNotify($ticket->id, $AuthorUser->id, 'create'));

        $countUpload = 1;
        foreach ($attachments as $attachment) {

            //if ($countUpload > $confFileCount) break;

            $fileMime = File::mimeType($attachment->filePath);
            $fileSize = File::size($attachment->filePath);

            if ($confFileStatus == "true") {

                if ($countUpload < $confFileCount) {

                    $FileUploaded = static::pathToUploadedFile($attachment->filePath, true);

//dd($FileUploaded);

                    $validator = Validator::make(array('file' => $FileUploaded), [
                        'file' => 'mimes:' . $confFileTypes . '|max:' . $confFileSize . '',
                    ]);

                    if ($validator->fails()) {
                        //echo "fails";
                    } else {
                        //echo "valid";

                        static::storeFileTicket($attachment->filePath, $attachment->name, $ticket->id, $AuthorUser->id);
                        $countUpload++;
                    }

                }
            }
            File::delete($attachment->filePath);
        }

    }

    /**
     * 
     * Storing files from mail to ticket
     * 
     * @param $FilePath
     * @param $FileName
     * @param $TicketID
     * @param $UserID
     */
    public static function storeFileTicket($FilePath, $FileName, $TicketID, $UserID)
    {

        $fileHash = str_random(30);

        $extension = File::extension($FilePath);
        $mime = File::mimeType($FilePath);
        $originalName = $FileName;

        $isimage = 'false';
        if (substr($mime, 0, 5) == 'image') {
            $isimage = 'true';
        }

        $storage = Storage::disk('users');
        $file_name = $fileHash . '.' . $extension;

        if (!$storage->exists($UserID)) {
            $storage->makeDirectory($UserID);
        }

        $storage->put($UserID . '/' . $file_name,
            file_get_contents($FilePath));

        Files::create([
            'user_id' => $UserID,
            'target_id' => $TicketID,
            'target_type' => 'ticket',
            'name' => $originalName,
            'hash' => $fileHash,
            'mime' => $mime,
            'extension' => strtolower($extension),
            'status' => 'success',
            'image' => $isimage,

        ]);

    }

    /**
     * 
     * Add file to comment ticket
     * 
     * @param $FilePath
     * @param $FileName
     * @param $TicketID
     * @param $UserID
     */
    public static function storeFileComment($FilePath, $FileName, $TicketID, $UserID)
    {

        $fileHash = str_random(30);

        $extension = File::extension($FilePath);
        $mime = File::mimeType($FilePath);
        $originalName = $FileName;

        $isimage = 'false';
        if (substr($mime, 0, 5) == 'image') {
            $isimage = 'true';
        }

        $storage = Storage::disk('users');
        $file_name = $fileHash . '.' . $extension;

        if (!$storage->exists($UserID)) {
            $storage->makeDirectory($UserID);
        }

        $storage->put($UserID . '/' . $file_name,
            file_get_contents($FilePath));

        Files::create([
            'user_id' => $UserID,
            'target_id' => $TicketID,
            'target_type' => 'ticketComment',
            'name' => $originalName,
            'hash' => $fileHash,
            'mime' => $mime,
            'extension' => strtolower($extension),
            'status' => 'success',
            'image' => $isimage,

        ]);

    }

    /**
     * @param $path
     * @param $public
     * @return mixed
     */
    public static function pathToUploadedFile($path, $public = false)
    {
        $name = File::name($path);

        $extension = File::extension($path);

        $originalName = $name . '.' . $extension;

        $mimeType = File::mimeType($path);

        $size = File::size($path);

        $error = null;

        $test = $public;

        $object = new UploadedFile($path, $originalName, $mimeType, $size, $error, $test);

        return $object;
    }

    /**
     * @param $AuthorUser
     * @param $mailMessage
     * @param $ticketCode
     * @param $attachments
     */
    public static function storeReplyMessage($AuthorUser, $mailMessage, $ticketCode, $attachments)
    {

        $confFileSize = Setting::get('ticket.ReceiveMail.upload_files_size');
        $confFileCount = Setting::get('ticket.ReceiveMail.upload_files_count');
        $confFileTypes = Setting::get('ticket.ReceiveMail.upload_files_types');
        $confFileStatus = Setting::get('ticket.ReceiveMail.upload_files');

        $visibleClientStatus = 'false';
        if ($AuthorUser->roles->role == 'client') {
            $visibleClientStatus = 'true';
        }

//убрать цитаты
        //echo "OK!";

        $visibleText = \EmailReplyParser\EmailReplyParser::parseReply($mailMessage);

//$visibleText

//RUN VIEW MIDDLEWARE
        $ticket = Ticket::where('code', $ticketCode)->firstOrFail();
        $comment = TicketComments::create([
            'text' => $visibleText,
            'author_id' => $AuthorUser->id,
            'ticket_id' => $ticket->id,
            'visible_client' => $visibleClientStatus,
            'urlhash' => str_random(10),
        ]);
        Event::fire(new TicketLogger($ticket->id, $AuthorUser->id, 'comment'));
        Event::fire(new TicketNotify($ticket->id, $AuthorUser->id, 'comment'));

/*confFileSize
confFileCount
confFileMimes*/

        //check files via
        $countUpload = 1;
        foreach ($attachments as $attachment) {

            //if ($countUpload > $confFileCount) break;

            $fileMime = File::mimeType($attachment->filePath);
            $fileSize = File::size($attachment->filePath);

            if ($confFileStatus == 'true') {

                if ($countUpload < $confFileCount) {

                    $FileUploaded = static::pathToUploadedFile($attachment->filePath, true);

//dd($FileUploaded);

                    $validator = Validator::make(array('file' => $FileUploaded), [
                        'file' => 'mimes:' . $confFileTypes . '|max:' . $confFileSize . '',
                    ]);

                    if ($validator->fails()) {
                        //echo "fails";
                    } else {
                        //echo "valid";

                        static::storeFileComment($attachment->filePath, $attachment->name, $comment->id, $AuthorUser->id);
                        $countUpload++;
                    }

                }
            }

            File::delete($attachment->filePath);
        }

    }

    /**
     * @param $ticketCode
     * @param $user
     */
    public static function showUserRights($ticketCode, $user)
    {

        $ticket = Ticket::whereCode($ticketCode)->firstOrFail();
//$user=Auth::user();

        //автор?
        if ($ticket->author_id == $user->id) {
            return true;
        }

        //в списке следящих?
        foreach ($ticket->watchingUsers as $value) {
            if ($value->id == $user->id) {return true;}
        }

        //заявка мне назначена?
        foreach ($ticket->targetUsers as $value) {
            if ($value->id == $user->id) {return true;}
        }

        //заявка моему отделу и никому конкретно?
        if (($ticket->targetUsers->count() == 0) && ($ticket->target_group_id != null)) {
            //return $ticket->target_group_id;
            foreach ($user->groups as $value) {
                if ($value->id == $ticket->target_group_id) {return true;}
                # code...
            }
        }

        //я клиент заявки?
        foreach ($ticket->watchingUsers as $value) {
            if ($value->id == $user->id) {return true;}
        }

//если заявка на отдел то проверить, я ли суперполльзователь отдела?
        if ($ticket->target_group_id != null) {
            foreach ($user->groups()->wherePivot('priviliges', 'admin')->get() as $value) {
                if ($value->id == $ticket->target_group_id) {
                    return true;
                }
            }
        }

//если заявка на пользователей конкретно, (у каждого пользователя отдел, и я ли в том отделе суперпользователь)
        if ($ticket->targetUsers->count() > 0) {
            $targetUsersGroups = [];
            foreach ($ticket->targetUsers as $targetUser) {
                # code...
                # у каждого пользователя берём группу
                foreach ($targetUser->groups as $group) {
                    # code...
                    array_push($targetUsersGroups, $group->id);

                }

            }

            $targetUsersGroups = array_unique($targetUsersGroups);

            foreach ($user->groups()->wherePivot('priviliges', 'admin')->get() as $value) {
                //if ($value->id == $ticket->target_group_id)
                if (in_array($value->id, $targetUsersGroups)) {
                    return true;
                }
            }

        }

        return false;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->info('Display this on the screen');

        $confReceiveAnon = true;

        $confTicketDef_User = '';
        $confTicketDef_Group = '';

        $serverAddr = Setting::get('ticket.ReceiveMail.AuthAddr');
        $serverPort = Setting::get('ticket.ReceiveMail.AuthPort');
        $serverSecurity = Setting::get('ticket.ReceiveMail.AuthSecurity');
        $serverLogin = Setting::get('ticket.ReceiveMail.AuthLogin');
        $serverPass = Setting::get('ticket.ReceiveMail.AuthPass');
        $serverDirectory = Setting::get('ticket.ReceiveMail.ServerDirectory');
        $serverFilter = Setting::get('ticket.ReceiveMail.filter');

        $serverReceiveAnon = Setting::get('ticket.ReceiveMail.receiveAnon');

        $serverStatus = Setting::get('ticket.ReceiveMail.status');

        if ($serverStatus == 'true') {



            $mailbox = new ImapMailbox('{' . $serverAddr . ':' . $serverPort . '' . $serverSecurity . '}' . $serverDirectory . '', $serverLogin, $serverPass, storage_path('/tmp/'));
            $mails = array();

            $mailsIds = $mailbox->searchMailbox($serverFilter);

            //dd('true');

            if (!$mailsIds) {
                //die('Mailbox is empty');
            } else {

                $max_msg = Setting::get('ticket.ReceiveMail.upload_files_count');
                $i = 0;
                foreach ($mailsIds as $id) {
                    if ($i > $max_msg) {
                        break;
                    }

                    $message = $mailbox->getMail($id);

                    //dd($message);

                    $attachments = $message->getAttachments();

                    $createFlag = false;

                    if (User::where('email', $message->fromAddress)->exists()) {

                        $AuthorUser = User::where('email', $message->fromAddress)->firstOrFail();

                        $createFlag = true;
                    } else {
                        if ($serverReceiveAnon == 'true') {

                            $newPass = str_random(6);

                            $AuthorUser = $this->storeNewUser($message->fromName, $message->fromAddress, $newPass);

                            $createFlag = true;
                        }
                    }

                    if ($createFlag == true) {

//showBody($attachments, $$message->textPlain, $$message->textHtml)
                        $mailMessage = $this->showBody($attachments, $message->textPlain, $message->textHtml);

//clean many /n symbols
                        $mailMessageClean = nl2br($mailMessage);
                        $mailMessageClean = preg_replace('#(<br */?>\s*)+#i', '<br />', $mailMessageClean);

                        $mailSubject = $message->subject;

//$mailMessage;
                        //$MailAuthorID;
                        //$mailSubject

/*проверить - является ли сообщение ответом?
если да
то есть ли у пользователя права на комментарии/доступ к заявке.
если да то создать заявку. */

//тема: НОВАЯ ЗАЯВКА [CODE:#G6PNWI]

//if (preg_match('/([CODE:#[0-9]+)/',$subj)) { }
                        if (strpos($mailSubject, '[CODE:#') !== false) {
                            //КОММЕНТАРИЙ [CODE:#.....]

                            $ticketCodePart = explode('[CODE:#', $mailSubject);
                            $ticketCodePart = explode("]", $ticketCodePart[1]);
                            $ticketCode = $ticketCodePart[0];

//dd($ticketCode);

                            if (Ticket::where('code', $ticketCode)->exists()) {

                                $access = $this->showUserRights($ticketCode, $AuthorUser);
                                if ($access) {
                                    $this->storeReplyMessage($AuthorUser, $mailMessage, $ticketCode, $attachments);
                                }

                            }

                        } else {
                            //НОВАЯ ЗАЯВКА

                            $this->storeTicket($AuthorUser, $mailMessageClean, $mailSubject, $attachments);

                        }

                    }
//END CREATE FLAG

                    $i++;
                }

            }

        }
    }
}
