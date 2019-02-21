<?php

namespace App\Listeners;

use App\Events\GeneratePayeeReportEvent;
use Illuminate\Queue\InteractsWithQueue;

use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ClientUnitSchedule;
use App\Models\Admin;
use Log;
use Auth;

class GeneratePayeeReportListener
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
     * @param  GenerateDailyReportEvent  $event
     * @return void
     */
    public function handle(GeneratePayeeReportEvent $event)
    {
        $bookingList = $event->bookings;
        (new FastExcel($bookingList))->download('Payee_Report_'.date('d-M-Y',strtotime("-1 days")).".xlsx", function ($booking) {
            $staff = "Searching Now";
            if($booking->staffId != 0)
            $staff = $booking->staff->forname." ".$booking->staff->surname;
            if($booking->staff->paymentMode==1) { $paymentMode = "Selfies"; }else{ $paymentMode="Payee";}
            $time = ClientUnitSchedule::where('clientUnitId',$booking->unitId)
                          ->where('staffCategoryId',$booking->categoryId)
                          ->where('shiftId',$booking->shiftId)->first();
            if($time->unPaidBreak == NULL) { $breakHrs = 0; } else{ $breakHrs = $time->unPaidBreak;}
            $totalHr = $this->calculateHourlyRate($booking->staff,'day')*$time->totalHoursStaff;
            if($booking->bonus == 0){ $bnsAuthBy = "NA"; }else{ $bnsAuthBy = $this->getAdminName($booking->bonusAuthorizedBy);}
            $totalTa = $booking->transportAllowence*$time->totalHoursStaff;
            $totalETa = $booking->extraTA*$time->totalHoursStaff;
            $totalBns = $booking->bonus*$time->totalHoursStaff;
            return [
                'Shift ID'     => $booking->bookingId,
                'Unit'         => $booking->unit->alias,
                'Category'     => $booking->category->name,
                'Date'         => date('d-M-Y, D',strtotime($booking->date)),
                'Shift'        => $booking->shift->name,
                'Staff'        => $staff,
                'Payment Mode' => $paymentMode,
                'Start Time'   => date('H:i',strtotime($time->startTime)) ,
                'End Time'     => date('H:i',strtotime($time->endTime)),
                'Break Hours'        => number_format($breakHrs,2),
                'Staff Hours' => number_format($time->totalHoursStaff,2),
                'Unit Hours' => number_format($time->totalHoursUnit,2),
                'Timesheet Number' => 0,
                'Entered By' => "",
                'Staff Rate'  =>  number_format($this->calculateHourlyRate($booking->staff,'day'),2),
                'Distance to Workplace'    => $booking->distenceToWorkPlace,
                'TA'    =>  number_format($booking->transportAllowence,2) ,
                'Extra TA'    =>  number_format($booking->extraTA,2) ,
                'Bonus'    =>  number_format($booking->bonus,2) ,
                'Bonus Reason'    => $this->getBonusReason($booking->bonusReason),
                'Bonus Authorized By'    => $bnsAuthBy,
                'Total Hr Rt' => number_format($totalHr,2),
                'Total TA' => number_format($totalTa,2),
                'Total Extra TA' => number_format($totalETa,2),
                'Total Bonus' => number_format($totalBns,2),
                'Total Pay' => number_format($totalHr+$totalTa+$totalETa+$totalBns,2),
                'Weekly Pay' => number_format($this->calculateBasicPay($totalHr,$totalBns),2),
                'Holiday Pay' => number_format($this->calculateHolidayPay($totalHr,$totalBns),2),
                'Transport Allowance' => number_format(($totalTa+$totalETa),2),
                'F H Rate' => number_format($this->calculateBasicPay($totalHr,$totalBns)/$time->totalHoursStaff,2)
            ];
        });
    }

    public function getAdminName($id){
      $admin = Admin::find($id);
      if($admin){
        return $admin->name;
      }else{
        return "NA";
      }
    }

    public function calculateHolidayPay($totalHr,$totalBns){
      return ($totalBns+$totalHr)*12.08/100;
    }

    public function calculateBasicPay($totalHr,$totalBns){
      $HolidayPay = ($totalBns+$totalHr)*12.08/100;
      return ($totalBns+$totalHr)-$HolidayPay;
    }

    public function calculateHourlyRate($staff,$shift){
        $day = strtolower(date('D',strtotime("-1 days")));
        if($day=='mon' || $day=='tue' || $day=='wed' || $day=='thu' || $day=='fri'){
            if($shift=='day'){
                return $staff->payRateWeekday;
            }else{
                return $staff->payRateWeekNight;
            }
        }
        if($day=='sun' || $day=='sat'){
            if($shift=='day'){
                return $staff->payRateWeekendDay;
            }else{
                return $staff->payRateWeekendNight;
            }
        }
    }

    public function getBonusReason($reason){
        Log::info($reason);
        switch ($reason) {
            case '1':
                return "Last min call";
                break;
            case '2':
                return "Short Shift-less than 4hr";
                break;
            case '3':
                return "Staff Cancellation";
                break;
            case '4':
                return "Booking Error";
                break;
            case '5':
                return "Weather Condition";
                break;
            case '6':
                return "Staying Over Time";
                break;
            case '7':
                return "Other Reason";
                break;
            default:
                return "N/A";
                break;
        }
    }
}
