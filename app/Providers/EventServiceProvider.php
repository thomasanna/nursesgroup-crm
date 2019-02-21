<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\GenerateTimesheetConsoleEvent' => [
            'App\Listeners\SendNotificationToAdminListener',
            'App\Listeners\GenerateTimesheetListener',
        ],
        'App\Events\GenerateTimesheetAdminEvent' => [
            'App\Listeners\GenerateTimesheetListener',
        ],
        'App\Events\GeneratePayeeReportEvent' => [
            'App\Listeners\GeneratePayeeReportListener',
        ],
        'App\Events\GenerateDailyReport' => [
            'App\Listeners\GenerateDailyReportListener',
        ],
        'App\Events\GenerateFurtherReport' => [
            'App\Listeners\GenerateFurtherReportListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
