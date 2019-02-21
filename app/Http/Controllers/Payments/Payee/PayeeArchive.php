<?php
namespace App\Http\Controllers\Payments\Payee;
use Yajra\Datatables\Facades\Datatables;

use App\Models\PaymentArchive;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Controllers\Payments\Payee\PayeeWeek;
use Auth;

trait PayeeArchive{
  public static  function data(){
      $query = PaymentArchive::Join('payments','payments.paymentId','payment_archives.paymentId')
              ->Join('bookings','bookings.bookingId','payments.bookingId')
              ->whereHas('payment.booking.staff', function ($query) {
                  $query->where('paymentMode',2);
              })
              ->select('payments.*')
              ->where('isPaymentRecorded',1)
              ->groupBy('bookings.staffId');
      $data = Datatables::of($query);
      $data->addIndexColumn();
      

      $data->filterColumn('payment.booking.staff.name', function($archive, $keyword) {
          $archive->where('bookings.staffId', $keyword);
      });

      $data->editColumn('payment.booking.staff.name',function($archive){
          return $archive->payment->booking->staff->forname." ".$archive->payment->booking->staff->surname;
      });
      $data->editColumn('amountTotal',function($archive){
          return 345.90;
      });
      $data->editColumn('numberOfBookings',function($archive){
          return 12;
      });
      $data->editColumn('actions',function($archive){
          $html = "<a href='".route('payment.payee.archives.all',$archive->payment->booking->staffId)."' class='btn btn-success btn-xs mrs' style='margin: 0 5px;'>View All</a>";
          return $html;
      });
      $tableData =$data->make(true);
      return $tableData;
    }

    public static function archivesAll($staffId){
      $payments = Payment::with('taxyear')->join('payment_archives','payment_archives.paymentId','payments.paymentId')
              ->whereHas('booking.staff', function ($query) use ($staffId){
                  $query->where('paymentMode',2);
                  $query->where('staffId',$staffId);
              })->where('payment_archives.isPaymentRecorded',1)
              ->groupBy('payments.paymentYear')->get();
      
      return view('payments.payee.archives.view_all_archives',compact(['payments']));
    }

    public static function archivesAllWeeks($request) {
      $weeks = Payment::Join('payment_archives','payment_archives.paymentId','payments.paymentId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$request->staff)
                  ->where('paymentYear',$request->year)
                  ->where('payment_archives.raStatus',1)
                  ->groupBy('payments.paymentWeek')
                  ->pluck('paymentWeek');
      $html = "";
      foreach ($weeks as $week) {
        $html .="<div class='weekItem' week='".$week."' year='".$request->year."' staff='".$request->staff."'";
        $html .="token='".csrf_token()."' single='".route('payment.payee.archives.weeks.details')."'>";
        $html .=$week."</div>";
      }
      return ['status'=>true,'html'=>$html];
      
    }

    public static function archivesAllWeeksDetails($request) {
      $payments = Payment::Join('payment_archives','payment_archives.paymentId','payments.paymentId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$request->staffId)
                  ->where('paymentWeek',$request->weekNum)
                  ->where('paymentYear',$request->yearId)
                  ->where('payment_archives.raStatus',1)->get();
      $from  = 1;
      $payments = PayeeWeek::processPaymentArray($payments);
      $html = view('payments.payee.archives.ra',compact('payments','from'))->render();            
      return ['status'=>true,"data"=>$payments,'html'=>$html];
      
    }



}
?>
