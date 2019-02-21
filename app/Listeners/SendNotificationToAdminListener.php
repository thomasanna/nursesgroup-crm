<?php

namespace App\Listeners;

use App\Events\GenerateTimesheetConsoleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\TimesheetGenerate;
use Log;

class SendNotificationToAdminListener  implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GenerateTimesheet  $event
     * @return void
     */
    public function handle(GenerateTimesheetConsoleEvent $event)
    {
      if(count($event->bookings) > 0){
        // Mail::to('manager@nursesgroup.co.uk')
        // ->bcc('jishadp369@gmail.com')->queue(new TimesheetGenerate($event->bookings));
      }
    }
}
