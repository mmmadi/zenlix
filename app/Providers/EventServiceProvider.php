<?php

namespace zenlix\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'zenlix\Events\TicketLogger' => [
            'zenlix\Handlers\Events\TicketLoggerHandler',
        ],
        'zenlix\Events\TicketNotify' => [
            'zenlix\Handlers\Events\TicketNotifyHandler',
        ],
        'zenlix\Events\UserNotify' => [
            'zenlix\Handlers\Events\UserNotifyHandler',
        ],
        'zenlix\Events\MessageNotify' => [
            'zenlix\Handlers\Events\MessageNotifyHandler',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
