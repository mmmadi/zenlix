<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::group(['middleware' => 'CanInstall'], function () {
    Route::get('install', ['uses' => 'InstallController@index']);

    Route::get('install/permissions', ['uses' => 'InstallController@showPermissions']);
    Route::get('install/requirements', ['uses' => 'InstallController@showRequirements']);
    Route::get('install/config', ['uses' => 'InstallController@showConfig']);

//install/preinstall
    Route::get('install/configPreInstall', ['uses' => 'InstallController@showPreInstall']);
    Route::patch('install/storeConfig', ['uses' => 'InstallController@storeConfig']);

    Route::patch('install/make', ['uses' => 'InstallController@storePreInstall']);
});

Route::group(['middleware' => 'IsInstall'], function () {

    Route::get('install/final', ['uses' => 'InstallController@showFinal']);

    Route::get('license/error', ['uses' => 'LicenseController@showError']);
    Route::get('license/success', ['uses' => 'LicenseController@showSuccess']);
    Route::post('license/add', ['uses' => 'LicenseController@storeLicense']);

/*

Авторизация
Просмотр входящих/исходящих/архивных заявок
Действие с заявкой: блокировка/выполнение/перенаправление/комментирование
Список пользователей/инфа о пользователе
создание заявки

ОБРАТНЫЙ API

 */

/*Route::group(['prefix' => 'api'], function () {

Route::post('authenticate', 'AuthenticateController@authenticate');

Route::group(['middleware' => 'jwt.auth'], function () {
Route::post('users', ['uses' => 'API\UsersController@index']);

});

});*/

    $api = app('Dingo\Api\Routing\Router');

//Route::group(['prefix' => 'api'], function() {
    //$api->version('v1', ['middleware' => 'api.auth'], function ($api) {
    $api->version('v1', ['middleware' => 'ActiveAPI', 'prefix' => 'api', 'namespace' => 'zenlix\Http\Controllers\API'], function ($api) {

        $api->post('login', 'AuthController@login');
        $api->post('logout', 'AuthController@logout');

//$api->group(['protected' => true, 'middleware' => 'jwt.refresh'], function($api) {
        $api->group(['protected' => true/*, 'middleware' => 'jwt.refresh'*/], function ($api) {

            $api->post('profile/view', ['uses' => 'ProfileController@index']);
            $api->post('profile/update', ['uses' => 'ProfileController@update']);

            $api->post('users/list', ['uses' => 'UsersController@index']);
            $api->post('users/show', ['uses' => 'UsersController@show']);

            $api->post('groups/list', ['uses' => 'GroupsController@index']);
            $api->post('groups/show', ['uses' => 'GroupsController@show']);

            $api->post('tickets/list/in', ['uses' => 'TicketController@indexIn']);
            $api->post('tickets/list/out', ['uses' => 'TicketController@indexOut']);
            $api->post('tickets/list/arch', ['uses' => 'TicketController@indexArch']);

            $api->post('ticket/show', ['uses' => 'TicketController@show']);

            //ticket lock/unlock
            //ticket ok/unok
            //ticket comment

//});

        });

    });

    //Route::post('login', 'Api\AuthController@login');

    //Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function() {

    //Route::post('logout', 'Api\AuthController@logout');

/*

1. Profile View
2. Profile Edit
3. Users list
4. User view
5. Groups List
6. Group View
7. Ticket List: In/Out/Archive
8. Ticket View
9. ticket Comment list
10. ticket comment add

 */

//});

    Route::get('/', ['as' => 'dashboard', function () {
        return redirect('dashboard');
    }]);

//jwt.auth

    Route::group(['middleware' => 'auth'], function () {

        Route::get('/socket.io', function () {
            return 'Server nodeJS is offline.';
        });

        Route::get('dashboard', ['uses' => 'DashboardController@index']);

        Route::get('404', ['uses' => 'ErrorController@show404']);

        Route::group(['middleware' => 'RoleAdminOrUser'], function () {

            Route::get('ticket/list', function () {return redirect('ticket/list/in');});

            Route::get('ticket/list/in', ['uses' => 'TicketController@indexIn']);
            Route::post('ticket/list/in', ['uses' => 'TicketController@showIn']);

            Route::get('ticket/list/out', ['uses' => 'TicketController@indexOut']);
            Route::post('ticket/list/out', ['uses' => 'TicketController@showOut']);

            Route::get('ticket/list/arch', ['uses' => 'TicketController@indexArch']);
            Route::post('ticket/list/arch', ['uses' => 'TicketController@showArch']);

            Route::get('ticket/planner', ['uses' => 'TicketController@indexPlanner']);
            Route::post('ticket/planner', ['uses' => 'TicketController@showPlanner']);

            Route::get('ticket/planner/{code}', ['uses' => 'TicketController@editPlannerTicket']);
            Route::post('ticket/planner/{code}', ['uses' => 'TicketController@updatePlannerTicket']);
            Route::delete('ticket/planner/delete/{code}', ['uses' => 'TicketController@destroyPlannerTicket']);

        });

        Route::group(['middleware' => 'RoleClient'], function () {

            //Route::get('tickets', function () { return redirect('tickets/'); });

            Route::get('tickets', ['uses' => 'TicketController@indexOutClient']);
            Route::post('tickets', ['uses' => 'TicketController@showOutClient']);

            Route::get('tickets/arch', ['uses' => 'TicketController@indexArchClient']);
            Route::post('tickets/arch', ['uses' => 'TicketController@showArchClient']);

        });

///files/view/small/
        Route::get('files/view/small/{id}', ['uses' => 'FilesController@showSmall']);
        Route::get('files/view/{id}', ['uses' => 'FilesController@showFull']);
///files/download/
        Route::get('files/download/{id}', ['uses' => 'FilesController@show']);

        Route::get('ticket/create', ['uses' => 'TicketController@create']);

        Route::post('ticket/upload/files', ['uses' => 'TicketController@storeFiles']);
        Route::post('ticket/files/delete/{id}', ['uses' => 'TicketController@destroyFiles']);
        Route::get('ticket/clients', ['uses' => 'TicketController@indexClients']);

        Route::get('ticket/watching', ['uses' => 'TicketController@indexWatching']);
        Route::get('ticket/watching/view', ['uses' => 'TicketController@showWatching']);
        Route::get('ticket/watching/view/panel', ['uses' => 'TicketController@showWatchingPanel']);

        Route::get('ticket/merged', ['uses' => 'TicketController@indexMerged']);
        Route::get('ticket/merged/view', ['uses' => 'TicketController@showMerged']);

        Route::get('ticket/refer', ['uses' => 'TicketController@showReferPanel']);

///ticket/clients/view
        Route::post('ticket/clients/view', ['uses' => 'TicketController@showClients']);

        Route::patch('ticket/create', ['uses' => 'TicketController@store']);

        Route::get('ticket/error', ['uses' => 'TicketController@accessError']);

//ticket/deleted ADD SPECIAL RIGHTS MIDDLEWARE
        Route::group(['middleware' => 'RoleAdmin'], function () {
            Route::get('ticket/deleted', ['uses' => 'TicketController@indexDeleted']);
            Route::post('ticket/list/deleted', ['uses' => 'TicketController@showDeleted']);
            Route::get('ticket/deleted/{id}', ['uses' => 'TicketController@showDeletedTicket']);
            Route::post('ticket/delete-restore/{id}', ['uses' => 'TicketController@destroyRestore']);
            Route::delete('ticket/delete-approve/{id}', ['uses' => 'TicketController@destroyApprove']);
        });

//TICKET_MODIFY_MIDDLEWARE
        Route::group(['middleware' => 'TicketModify'], function () {
            Route::delete('ticket/delete/{id}', ['uses' => 'TicketController@destroy']);
            Route::get('ticket/edit/{id}', ['uses' => 'TicketController@edit']);
            Route::post('ticket/edit/{id}', ['uses' => 'TicketController@update']);
            Route::post('ticket/approve/{id}', ['uses' => 'TicketController@updateSuccessStatusApprove']);
            Route::post('ticket/noapprove/{id}', ['uses' => 'TicketController@updateSuccessStatusNoApprove']);
//updateSuccessStatusApprove
        });
///////////////////////////

//TICKET_VIEW_MIDDLEWARE
        Route::group(['middleware' => 'TicketView'], function () {
            Route::get('ticket/{id}/{print?}', ['uses' => 'TicketController@show']);
            Route::patch('ticket/comment/{id}', ['uses' => 'TicketController@storeComment']);
            Route::patch('ticket/comment/{id}/file', ['uses' => 'FilesController@storeCommentFile']);
        });
///////////////////////////

//TICKET_ACTION_MIDDLEWARE
        Route::group(['middleware' => 'TicketAction'], function () {
            Route::patch('ticket/{id}/merge', ['uses' => 'TicketController@storeMerge']);
            Route::delete('ticket/{id}/merge', ['uses' => 'TicketController@destroyMerge']);

            Route::patch('ticket/{id}/watching', ['uses' => 'TicketController@storeWatching']);
            Route::delete('ticket/{id}/watching', ['uses' => 'TicketController@destroyWatching']);
            Route::post('ticket/refer/{id}', ['uses' => 'TicketController@updateRefer']);
            Route::post('ticket/{id}/workstatus', ['uses' => 'TicketController@updateWorkStatus']);
            Route::post('ticket/{id}/successstatus', ['uses' => 'TicketController@updateSuccessStatus']);
        });
///////////////////////////

//Route::patch('ticket/comment/{id}', ['uses' => 'TicketController@storeComment']);
        //Route::patch('ticket/comment/{id}/file', ['uses' => 'FilesController@storeCommentFile']);

//updateUserCover
        //deleteUserCover

        Route::get('profile/edit', ['uses' => 'ProfileController@edit']);
        Route::patch('profile/edit/password', ['uses' => 'ProfileController@updatePassword']);
        Route::patch('profile/edit/interface', ['uses' => 'ProfileController@updateInterface']);
        Route::patch('profile/edit/notify', ['uses' => 'ProfileController@updateNotify']);
        Route::patch('profile/edit', ['uses' => 'ProfileController@update']);

        Route::patch('profile/edit/userImg', ['uses' => 'ProfileController@updateUserImg']);
        Route::delete('profile/edit/userImg', ['uses' => 'ProfileController@destroyUserImg']);

//Route::group(['middleware' => 'RoleAdminOrUser'], function () {
        Route::get('users', ['uses' => 'UsersController@index']);
        Route::post('users', ['uses' => 'UsersController@showFind']);
//});

        Route::get('user/{id}', ['uses' => 'UsersController@show']);

//Route::group(['middleware' => 'RoleAdminOrUser'], function () {
        Route::get('groups', ['uses' => 'GroupsController@index']);
        Route::get('group/{id}', ['uses' => 'GroupsController@show']);
        Route::post('group/post/add/{id}', ['uses' => 'GroupsController@storePost']);
        Route::post('group/post/add/{id}/comment', ['uses' => 'GroupsController@storeComment']);

        Route::get('group/{id}/post/{postID}', ['uses' => 'GroupsController@showPost']);
//Route::get('group/{id}/post/{postID}/edit', ['uses' => 'GroupsController@editPost']);
        //Route::post('group/{id}/post/{postID}', ['uses' => 'GroupsController@updatePost']);
        Route::delete('group/{id}/post/{postID}', ['uses' => 'GroupsController@destroyPost']);

        Route::get('group/edit/{id}', ['uses' => 'GroupsController@edit']);
        Route::post('group/edit/{id}', ['uses' => 'GroupsController@update']);
//});

        Route::get('message', function () {return redirect('message/inbox');});
        Route::get('message/inbox', ['uses' => 'MessageController@index']);
        Route::get('message/sent', ['uses' => 'MessageController@indexSent']);
        Route::get('message/draft', ['uses' => 'MessageController@indexDraft']);
        Route::get('message/trash', ['uses' => 'MessageController@indexTrash']);
        Route::get('message/users', ['uses' => 'MessageController@indexUsers']);

        Route::get('message/new', ['uses' => 'MessageController@create']);
        Route::patch('message/new', ['uses' => 'MessageController@store']);
        Route::post('message/upload/files', ['uses' => 'MessageController@storeFiles']);
        Route::post('message/files/delete/{id}', ['uses' => 'MessageController@destroyFiles']);
        Route::get('message/files/download/{id}', ['uses' => 'MessageController@showFiles']);

        Route::get('message/{hash}', ['uses' => 'MessageController@show']);
        Route::get('message/{hash}/reply', ['uses' => 'MessageController@showReply']);
        Route::delete('message/{hash}/delete', ['uses' => 'MessageController@destroy']);
        Route::delete('/message/deleteMany', ['uses' => 'MessageController@destroyMany']);
        Route::post('/message/checkNew', ['uses' => 'MessageController@showNew']);
//Route::get('/message/showDataBody', ['uses' => 'MessageController@showDataBody']);

//online
        Route::post('/online', ['uses' => 'CoreController@storeOnline']);

        Route::post('/chat/send', ['uses' => 'ChatController@storeChat']);
        Route::get('/chat/get', ['uses' => 'ChatController@showChat']);
        Route::post('/chat/toggle', ['uses' => 'ChatController@updateChatToggle']);
        Route::post('/chat/closeCurrent', ['uses' => 'ChatController@updateChatCloseCurrent']);
        Route::post('/chat/setAsRead', ['uses' => 'ChatController@updateChatSetAsRead']);
        Route::post('/chat/sendRequest', ['uses' => 'ChatController@storeChatRequest']);
        Route::post('/chat/AcceptRequest', ['uses' => 'ChatController@storeChatRequestAccept']);

        Route::post('/chat/updateReqMenu', ['uses' => 'ChatController@showChatRequestMenu']);

//updateNotifyMenu
        Route::post('/updateNotifyMenu', ['uses' => 'DashboardController@showNotifyMenu']);
        Route::post('/notifyMenu/read', ['uses' => 'DashboardController@updateNotifyMenu']);

///chat/AcceptRequest

        Route::get('/report/user', ['uses' => 'ReportController@showUser']);
        Route::post('/report/user', ['uses' => 'ReportController@showUserReport']);
//showUserReport

        Route::get('/report/group', ['uses' => 'ReportController@showGroup']);
        Route::post('/report/group', ['uses' => 'ReportController@showGroupReport']);

//sidebarMenuState
        Route::post('/sidebar/toggle', ['uses' => 'DashboardController@updateSidebarMenuState']);

        Route::get('help', ['uses' => 'HelpCenterController@index']);
        Route::get('help/edit/category', ['uses' => 'HelpCenterController@editCategory']);
        Route::get('help/edit/category/{id}', ['uses' => 'HelpCenterController@editCategoryName']);
        Route::get('help/add/category', ['uses' => 'HelpCenterController@createCategory']);
        Route::patch('help/add/category', ['uses' => 'HelpCenterController@storeCategory']);
        Route::post('help/edit/category', ['uses' => 'HelpCenterController@updateCategorySort']);
        Route::post('help/edit/category/{id}', ['uses' => 'HelpCenterController@updateCategory']);
        Route::delete('help/delete/category/{id}', ['uses' => 'HelpCenterController@destroyCategory']);
        Route::get('help/cat/{id}', ['uses' => 'HelpCenterController@showCat']);
//<a href="{{URL::to('/help/cat/'.$cat->id)}}">

        Route::get('help/add', ['uses' => 'HelpCenterController@create']);
        Route::patch('help/add', ['uses' => 'HelpCenterController@store']);
        Route::post('help/upload', ['uses' => 'HelpCenterController@storeFile']);

        Route::post('help/upload/files', ['uses' => 'HelpCenterController@storeFiles']);
        Route::post('help/files/delete/{id}', ['uses' => 'HelpCenterController@destroyFiles']);
        Route::get('help/files/download/{id}', ['uses' => 'HelpCenterController@showFiles']);
        Route::get('help/edit/{slug}', ['uses' => 'HelpCenterController@edit']);
        Route::post('help/edit/{id}', ['uses' => 'HelpCenterController@update']);
        Route::post('help/find', ['uses' => 'HelpCenterController@showFind']);
        Route::delete('help/delete/{id}', ['uses' => 'HelpCenterController@destroy']);
        Route::get('help/{slug}', ['uses' => 'HelpCenterController@show']);

        Route::get('calendar', ['uses' => 'CalendarController@index']);
        Route::get('calendar/events', ['uses' => 'CalendarController@indexEvents']);
        Route::get('calendar/event', ['uses' => 'CalendarController@showEvent']);

        Route::post('calendar/event/create', ['uses' => 'CalendarController@storeEvent']);
        Route::post('calendar/event/resize', ['uses' => 'CalendarController@updateEventResize']);
        Route::post('calendar/event/drop', ['uses' => 'CalendarController@updateEventDrop']);

        Route::post('calendar/event/update', ['uses' => 'CalendarController@updateEvent']);
        Route::delete('calendar/event/{code}', ['uses' => 'CalendarController@destroyEvent']);

        Route::get('test', ['uses' => 'ConfigSystemController@indexTest']);
        Route::get('test2', ['uses' => 'ConfigSystemController@indexTest2']);

Route::patch('admin/users/loginAsAdmin', ['uses' => 'ConfigUsersController@updateLoginAsAdmin']);

        Route::group(['middleware' => 'RoleAdmin'], function () {
            Route::get('admin/config', ['uses' => 'ConfigSystemController@index']);
            Route::delete('admin/config', ['uses' => 'ConfigSystemController@destroyLogo']);
//destroyLogo

            Route::get('admin/config/upgrade', ['uses' => 'UpdateController@index']);
            Route::post('admin/config/update/check_version', ['uses' => 'UpdateController@showVersion']);
            Route::post('admin/config/update/make', ['uses' => 'UpdateController@store']);

//Route::get('admin/config/error_logs', ['uses' => 'ErrorLogController@index']);
            Route::get('admin/config/error_logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

            Route::get('admin/config/license', ['uses' => 'LicenseController@index']);
            Route::post('admin/config/license/make', ['uses' => 'LicenseController@updateLicense']);
///admin/config/license/make

            Route::post('admin/config', ['uses' => 'ConfigSystemController@update']);
            Route::get('admin/config/auth', ['uses' => 'ConfigSystemController@indexAuth']);
            Route::post('admin/config/auth', ['uses' => 'ConfigSystemController@updateAuth']);
            Route::get('admin/config/notify', ['uses' => 'ConfigSystemController@indexNotify']);
            Route::post('admin/config/notify', ['uses' => 'ConfigSystemController@updateNotify']);

            Route::post('admin/config/notify/testMail', ['uses' => 'ConfigSystemController@showNotifyTestMail']);
            Route::post('admin/config/notify/testSMS', ['uses' => 'ConfigSystemController@showNotifyTestSMS']);
            Route::post('admin/config/notify/testPB', ['uses' => 'ConfigSystemController@showNotifyTestPB']);
            Route::post('admin/config/notify/testWP', ['uses' => 'ConfigSystemController@showNotifyTestWP']);

            Route::get('admin/ticket', ['uses' => 'ConfigTicketController@index']);

//admin/ticket/config
            Route::get('admin/ticket/config', ['uses' => 'ConfigTicketController@indexConfig']);
            Route::post('admin/ticket/config', ['uses' => 'ConfigTicketController@update']);

            Route::get('admin/ticket/mail', ['uses' => 'ConfigTicketController@indexTicketMail']);
            Route::post('admin/ticket/mail', ['uses' => 'ConfigTicketController@updateTicketMail']);
            Route::post('admin/ticket/mail/test', ['uses' => 'ConfigTicketController@updateTicketMailTest']);
//updateTicketMail

            Route::get('admin/ticket/forms', ['uses' => 'ConfigTicketController@indexForms']);
            Route::get('admin/ticket/forms/create', ['uses' => 'ConfigTicketController@createForms']);
            Route::patch('admin/ticket/forms/create', ['uses' => 'ConfigTicketController@storeForms']);
            Route::get('admin/ticket/forms/edit/{id}', ['uses' => 'ConfigTicketController@editForms']);
            Route::post('admin/ticket/forms/edit/{id}', ['uses' => 'ConfigTicketController@updateForms']);
            Route::delete('admin/ticket/forms/delete/{id}', ['uses' => 'ConfigTicketController@destroyForm']);
///admin/ticket/forms/create

            Route::get('admin/ticket/subj', ['uses' => 'ConfigTicketController@indexSubj']);
            Route::get('admin/ticket/subj/create', ['uses' => 'ConfigTicketController@createSubj']);
            Route::patch('admin/ticket/subj/create', ['uses' => 'ConfigTicketController@storeSubj']);
            Route::get('admin/ticket/subj/edit/{id}', ['uses' => 'ConfigTicketController@editSubj']);
            Route::post('admin/ticket/subj/edit/{id}', ['uses' => 'ConfigTicketController@updateSubj']);
            Route::delete('admin/ticket/subj/delete/{id}', ['uses' => 'ConfigTicketController@destroySubj']);

            Route::get('admin/ticket/adv', ['uses' => 'ConfigTicketController@indexAdv']);
            Route::get('admin/ticket/adv/create', ['uses' => 'ConfigTicketController@createAdv']);
            Route::patch('admin/ticket/adv/create', ['uses' => 'ConfigTicketController@storeAdv']);
            Route::get('admin/ticket/adv/edit/{id}', ['uses' => 'ConfigTicketController@editAdv']);
            Route::post('admin/ticket/adv/edit/{id}', ['uses' => 'ConfigTicketController@updateAdv']);
            Route::delete('admin/ticket/adv/delete/{id}', ['uses' => 'ConfigTicketController@destroyAdv']);

            Route::get('admin/ticket/sla', ['uses' => 'ConfigTicketController@indexSla']);
            Route::get('admin/ticket/sla/create', ['uses' => 'ConfigTicketController@createSla']);
            Route::patch('admin/ticket/sla/create', ['uses' => 'ConfigTicketController@storeSla']);
            Route::get('admin/ticket/sla/edit/{id}', ['uses' => 'ConfigTicketController@editSla']);
            Route::post('admin/ticket/sla/edit/{id}', ['uses' => 'ConfigTicketController@updateSla']);
            Route::delete('admin/ticket/sla/delete/{id}', ['uses' => 'ConfigTicketController@destroySla']);

            Route::get('admin/users', ['uses' => 'ConfigUsersController@index']);
            Route::get('admin/user/create', ['uses' => 'ConfigUsersController@create']);
            Route::patch('admin/user/create', ['uses' => 'ConfigUsersController@store']);
            Route::get('admin/user/edit/{id}', ['uses' => 'ConfigUsersController@edit']);
            Route::post('admin/user/edit/{id}', ['uses' => 'ConfigUsersController@update']);
            Route::delete('admin/user/delete/{id}', ['uses' => 'ConfigUsersController@destroy']);
            Route::get('admin/users/import', ['uses' => 'ConfigUsersController@showImport']);
            Route::get('admin/users/import/csv', ['uses' => 'ConfigUsersController@showImportCSV']);
            Route::patch('admin/users/import/csv', ['uses' => 'ConfigUsersController@updateUsersImportCsv']);
            Route::patch('admin/users/import/csv/step2', ['uses' => 'ConfigUsersController@updateUsersImportCsvStep2']);

//updateUsersImportCsvStep3
            Route::patch('admin/users/loginAs', ['uses' => 'ConfigUsersController@updateLoginAs']);


            Route::get('admin/users/import/ldap', ['uses' => 'ConfigUsersController@showImportLDAP']);
            Route::patch('admin/users/import/ldap', ['uses' => 'ConfigUsersController@showImportLDAPStep2']);
            Route::patch('admin/users/import/ldap/step3', ['uses' => 'ConfigUsersController@showImportLDAPStep3']);

            Route::get('admin/users/adv', ['uses' => 'ConfigUsersController@showAdv']);
            Route::get('admin/users/adv/create', ['uses' => 'ConfigUsersController@createAdv']);
            Route::get('admin/users/adv/edit/{id}', ['uses' => 'ConfigUsersController@editAdv']);
            Route::post('admin/users/adv/edit/{id}', ['uses' => 'ConfigUsersController@updateAdv']);
            Route::delete('admin/users/adv/delete/{id}', ['uses' => 'ConfigUsersController@destroyAdv']);

            Route::patch('admin/users/adv', ['uses' => 'ConfigUsersController@storeAdv']);

            Route::get('admin/groups', ['uses' => 'ConfigGroupsController@index']);
            Route::get('admin/group/create', ['uses' => 'ConfigGroupsController@create']);
            Route::patch('admin/group/create', ['uses' => 'ConfigGroupsController@store']);
            Route::get('admin/group/edit/{id}', ['uses' => 'ConfigGroupsController@edit']);
            Route::post('admin/group/edit/{id}', ['uses' => 'ConfigGroupsController@update']);
            Route::delete('admin/group/delete/{id}', ['uses' => 'ConfigGroupsController@destroy']);
        });

        Route::get('search', function () {
            if (Input::get('q')) {
                return redirect('search/' . Input::get('q'));
            } else {
                return Redirect::back();
            }
        });

        Route::get('search/{id}', ['uses' => 'SearchController@index']);

    });

// Authentication routes...
    Route::get('login', 'Auth\AuthController@getLogin');
    Route::post('login', 'Auth\AuthController@postLogin');
    Route::get('logout', 'Auth\AuthController@getLogout');

// Registration routes...
    Route::get('register', 'Auth\AuthController@getRegister');
    Route::post('register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
    Route::get('forgot', 'Auth\PasswordController@getEmail');
    Route::post('forgot', 'Auth\PasswordController@postEmail');

// Password reset routes...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');

});
