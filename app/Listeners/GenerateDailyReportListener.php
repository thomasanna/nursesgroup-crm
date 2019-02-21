<?php

namespace App\Listeners;

use App\Events\GenerateDailyReport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Driver;
use App\Models\Staff;
use Log;
use Auth;

class GenerateDailyReportListener
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
     * @param  GenerateDailyReport  $event
     * @return void
     */
    public function handle(GenerateDailyReport $event){
      $bookingList = $event->bookings;
      (new FastExcel($bookingList))->download('Payee_Report_'.date('d-M-Y',strtotime("-1 days")).".xlsx", function ($booking) {
          $staff = "Searching Now";
          if($booking->staffId != 0)
          $staff = $booking->staff->forname." ".$booking->staff->surname;
          if($booking->staff->paymentMode==1) { $paymentMode = "Selfies"; }else{ $paymentMode="Payee";}
          return [
              'Shift ID'     => $booking->bookingId,
              'Unit'         => $booking->unit->alias,
              'Category'     => $booking->category->name,
              'Date'         => date('d-M-Y, D',strtotime($booking->date)),
              'Shift'        => $booking->shift->name,
              'Staff'        => $staff,
              'Unit Status'  => 'Confirmed',
              'Staff Status'  => 'Confirmed',
              'OutBound Driver'  => $this->getOutBoundDriver($booking),
              'InBound Driver'  => $this->getInBoundDriver($booking),
              'Distence to Workplace'    => 0.0 ,

          ];
      });
    }

    public function getOutBoundDriver($booking){
      if($booking->modeOfTransport==1){
        return "Self";
      }else{
        switch ($booking->outBoundDriverType) {
          case 1:  //Private Driver
              $driver = Driver::find($booking->outBoundDriverId);
              return $driver->forname." ".$driver->surname;
            break;
          case 2:  // Possible Lift
              $staff = Staff::find($booking->outBoundDriverId);
              return $staff->forname." ".$staff->surname;
            break;
          case 3:  // Public Transport
              if($booking->outBoundDriverId == 1) return "Bus";
              if($booking->outBoundDriverId == 2) return "Rail";
              if($booking->outBoundDriverId == 3) return "Taxi";
            break;
        }
      }
    }

    public function getInBoundDriver($booking){
      if($booking->modeOfTransport==1){
        return "Self";
      }else{
        switch ($booking->inBoundDriverType) {
          case 1:  //Private Driver
              $driver = Driver::find($booking->inBoundDriverId);
              return $driver->forname." ".$driver->surname;
            break;
          case 2:  // Possible Lift
              $staff = Staff::find($booking->inBoundDriverId);
              return $staff->forname." ".$staff->surname;
            break;
          case 3:  // Public Transport
              if($booking->inBoundDriverId == 1) return "Bus";
              if($booking->inBoundDriverId == 2) return "Rail";
              if($booking->inBoundDriverId == 3) return "Taxi";
            break;
        }
      }
    }

}
