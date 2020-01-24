<?php

namespace zenlix\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use View;
use zenlix\User;
use Auth;
use Setting;
use zenlix\Ticket;
use zenlix\Messages;
use Chat;
use Session;
use zenlix\Classes\Zen;
use Carbon\Carbon;
use zenlix\Calendar;

use zenlix\NotificationMenu;

abstract class APIController extends BaseController
{

        use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {


    }



}
