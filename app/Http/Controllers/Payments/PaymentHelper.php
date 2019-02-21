<?php
namespace App\Http\Controllers\Payments;
use App\Models\Payment;
use App\Models\Admin;
use App\Models\Booking;
use Carbon\Carbon;

use Cache;
trait PaymentHelper{
  public static function getAdminName($id){
    if (Cache::has('admin_'.$id)) {
      $admin = Cache::get('admin_'.$id);
    }
    else {
      $admin = Admin::find($id);
      Cache::forever('admin_'.$id, $admin);
    }
    if($admin){
      return $admin->name;
    }else{
      return "NA";
    }
  }
  public static function calculateHolidayPay($totalHr,$totalBns){
    return ($totalBns+$totalHr)*12.08/100;
  }

  public static function calculateWeeklyPay($totalHr,$totalBns){
    $HolidayPay = ($totalBns+$totalHr)*12.08/100;
    return ($totalBns+$totalHr)-$HolidayPay;
  }

  public static function calculateHourlyRate($booking,$shift){
      $day = strtolower(date('D',strtotime($booking->date)));
      if($day=='mon' || $day=='tue' || $day=='wed' || $day=='thu' || $day=='fri'){
          if($shift=='day'){
              return $booking->staff->payRateWeekday;
          }else{
              return $booking->staff->payRateWeekNight;
          }
      }
      if($day=='sun' || $day=='sat'){
          if($shift=='day'){
              return $booking->staff->payRateWeekendDay;
          }else{
              return $booking->staff->payRateWeekendNight;
          }
      }
  }

  public static function getBonusReason($reason){
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

  public static function additionalStaff($booking){
    $outs = Booking::whereDate('date',$booking->date)
        ->where('outBoundDriverType',2)
        ->where('outBoundDriverId',$booking->staffId)->get();

    $ins = Booking::whereDate('date',$booking->date)
        ->where('inBoundDriverType',2)
        ->where('inBoundDriverId',$booking->staffId)->get();

    if(count($outs) == 0 && count($ins) ==0){ return  ['names'=>"N/A",'count'=>count($outs)+count($ins)]; }
    $names = "";
    for ($i=0; $i < count($ins); $i++) {
      if($i==0) $names .= "In - ";
      $names .= $ins[$i]->staff->forname;
      if ($i+1 != count($ins)){
        $names .=",";
      }

    }
    for ($j=0; $j < count($outs); $j++) {
      if($j==0) $names .= "| Out - ";
      $names .= $outs[$j]->staff->forname;
      if ($j+1 != count($ins)){
        $names .=",";
      }
    }
   return  ['names'=>$names,'count'=>count($outs)+count($ins)];
  }

  public static function getHistoricalRate($staffId){
    // Carbon::setWeekStartsAt(Carbon::SUNDAY);
    $payments = Payment::whereHas('booking.staff', function ($query) use($staffId) {
                  $query->where('staffId',$staffId);
                //   ->where('unitId',$unitId)
                //   ->whereBetween('date',[Carbon::now()->startOfWeek(),Carbon::now()->today()]);
                })
                ->where('status',2)->take(4)->latest()->get();
    $sum = $payments->sum('hourlyRate')+$payments->sum('ta')+$payments->sum('extraTa');
    if($payments->count() > 0){
      return number_format($sum/$payments->count(),2);
    }else{
      return number_format(0,2);
    }
  }
}
?>
