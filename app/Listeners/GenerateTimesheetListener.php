<?php

namespace App\Listeners;

use App\Events\GenerateTimesheetConsoleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Timesheet;
use App\Models\ClientUnitSchedule;
use App\Models\BookingLog;
use Log;
use Auth;

class GenerateTimesheetListener
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
      foreach ($event->bookings as $booking) {
        if(Timesheet::where('bookingId',$booking->bookingId)->count()==0){
          $bookId[] = $booking->bookingId;
          Log::info('GenerateTimesheet Listener is '.$booking->bookingId);
          $time = ClientUnitSchedule::where('clientUnitId',$booking->unitId)
                        ->where('staffCategoryId',$booking->categoryId)
                        ->where('shiftId',$booking->shiftId)->first();
          $timesheet = Timesheet::create([
            'bookingId' =>$booking->bookingId,
            'startTime'=>$time->startTime,
            // Need to insert important notes in the field of comment column
            'comments'=>$booking->importantNotes,
            'endTime'=>$time->endTime,
            'breakHours'=>$time->unPaidBreak,
            'unitHours'  => $time->totalHoursUnit,
            'staffHours'=> $time->totalHoursStaff
            ]);

          BookingLog::create([
              'bookingId' =>$timesheet->bookingId,
              'content' =>"Timesheet <strong> <span class='logHgt'>Generated. </span> </strong> ",
              'author' =>1,
            ]);
        }
      }
    }
}
