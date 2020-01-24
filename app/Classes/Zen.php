<?php

namespace zenlix\Classes;

use Auth;
use zenlix\Chat;
use zenlix\User;
use zenlix\UserLdap;
use zenlix\UserProfile;
use zenlix\UserRole;
use zenlix\UsersNotifyConfig;
use zenlix\UserTicketConf;

class Zen
{

    public function __construct()
    {

        //dd('ok');

    }

    public static function zen()
    {
        return "ok";
    }

    public static function showNotifyList()
    {

        return [
            'create' => trans('notifyMenu.create'),
            'refer' => trans('notifyMenu.refer'),
            'lock' => trans('notifyMenu.lock'),
            'unlock' => trans('notifyMenu.unlock'),
            'ok' => trans('notifyMenu.success'),
            'unok' => trans('notifyMenu.noSuccess'),
            'waitok' => trans('notifyMenu.waitApprove'),
            'aprrove' => trans('notifyMenu.noSuccess'),
            'noapprove' => trans('notifyMenu.noApprove'),
            'delete' => 'delete ticket',
            'restore' => trans('notifyMenu.restore'),
            'comment' => trans('notifyMenu.comment'),
            'edit' => trans('notifyMenu.edit'),

        ];
    }

    public static function checkUnreadChatAll()
    {

        $user = Auth::user();

        return Chat::where('to_user_id', $user->id)->where('read_flag', 'true')->count();

    }

    public static function checkUnreadChat($onlineUser)
    {

        $user = Auth::user();

        $totalC = Chat::where('to_user_id', $user->id)->where('from_user_id', $onlineUser)->where('read_flag', 'true')->count();

        if ($totalC > 0) {
            return true;
        } else {
            return false;
        }
/*                    ->orWhere(function ($query) use($user, $onlineUser) {
return $query->where('to_user_id', $onlineUser)->where('from_user_id', $user->id);
})*/

    }

    public static function storeNewUser($data)
    {

        if (!$data['password']) {
            $password = str_random(6);
        } else {
            $password = $data['password'];
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($password),
        ]);

        $profile = new UserProfile;
        $profile->full_name = $data['name'];
        $profile->email = $data['email'];
        $profile->user_urlhash = str_random(25);
        $user->profile()->save($profile);

        UserTicketConf::create([
            'user_id' => $user->id,
            'ticket_form_id' => '1',
            'conf_params' => 'user',
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'role' => 'client',
        ]);

        UserLdap::create([
            'user_id' => $user->id,
        ]);

        $notifyArr = ['create',
            'refer',
            'lock',
            'unlock',
            'ok',
            'unok',
            'waitok',
            'aprrove',
            'noapprove',
            'delete',
            'restore',
            'comment',
            'edit'];

        foreach ($notifyArr as $notify) {
            UsersNotifyConfig::create([
                'user_id' => $user->id,
                'target' => 'mail',
                'type' => $notify,
            ]);
        }

        return $user;
    }

    public static function showShortName($name)
    {

        return preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2.$3.', $name);

    }

    public static function showUserImg($user_img)
    {
        if ($user_img == null) {
            return asset('dist/img/def_usr.png');
        } else {
            return asset('files/users/img/' . $user_img);
        }
    }

    public static function showUserImgSmall($user_img)
    {
        if ($user_img == null) {
            return asset('dist/img/def_usr_small.png');
        } else {
            $file_name = pathinfo('files/users/img/' . $user_img, PATHINFO_FILENAME);
            $extension = pathinfo('files/users/img/' . $user_img, PATHINFO_EXTENSION);
            return asset('files/users/img/' . $file_name . '_small.' . $extension);
        }
    }

    public static function fileIcon($mime_type)
    {

        // List of official MIME Types: http://www.iana.org/assignments/media-types/media-types.xhtml
        static $font_awesome_file_icon_classes = array(

            // Images
            'image' => 'fa-file-image-o',

            // Audio
            'audio' => 'fa-file-audio-o',

            // Video
            'video' => 'fa-file-video-o',

            // Documents
            'application/pdf' => 'fa-file-pdf-o',
            'text/plain' => 'fa-file-text-o',
            'text/html' => 'fa-file-code-o',
            'application/json' => 'fa-file-code-o',

            // Archives
            'application/gzip' => 'fa-file-archive-o',
            'application/zip' => 'fa-file-archive-o',

            // Misc
            'application/octet-stream' => 'fa-file-o',
        );
        if (isset($font_awesome_file_icon_classes[$mime_type])) {
            return $font_awesome_file_icon_classes[$mime_type];
        }
        $mime_parts = explode('/', $mime_type, 2);
        $mime_group = $mime_parts[0];
        if (isset($font_awesome_file_icon_classes[$mime_group])) {
            return $font_awesome_file_icon_classes[$mime_group];
        }
        return "fa-file-o";
    }
}
