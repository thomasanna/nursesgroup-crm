<?php
namespace App\Http\Controllers\Payments\Payee;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\BookingLog;

use Log;
use Auth;

trait Payee{
  public static  function data($request){

    $query = Payment::with(['timesheet','booking','booking.shift','booking.unit','booking.category','booking.staff'])
            ->Join('bookings', 'bookings.bookingId', 'payments.bookingId')
            ->whereHas('booking.staff', function ($query) {
                $query->where('paymentMode',2);
            })
            ->where('status','<>',2);
    if(empty($request->columns[2]['search']['value'])){
        $query->whereDate('bookings.date','=',date('Y-m-d',strtotime("-1 days")));
    }
    $data = Datatables::of($query);
    $data->addIndexColumn();
    
    $data->filterColumn('booking.date', function($payment, $keyword) {
        $dates = explode(" - ",$keyword);
        $payment->whereBetween('bookings.date',[date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))]);
    });
    $data->editColumn('booking.date',function($payment){
        if($payment->booking->date){ return date('d-M-Y, D',strtotime($payment->booking->date)); }
    });

    $data->editColumn('booking.shift.name',function($payment){
       return $payment->booking->shift->name;
    });

    $data->filterColumn('booking.shift.name', function($payment, $keyword) {
        $payment->where('bookings.shiftId', $keyword);
    });

    $data->filterColumn('booking.category.name', function($payment, $keyword) {
        $payment->where('bookings.categoryId', $keyword);
    });

    $data->editColumn('status',function($payment){
      if($payment->status ==0){ return "<span class='label label-primary'>New</span>";}
      if($payment->status ==1){ return "<span class='label label-success'>Verified</span>";}
      if($payment->status ==3){ return "<span class='label label-danger'>Bounced</span>";}
    });

    $data->editColumn('booking.staff.name',function($payment){
        return $payment->booking->staff->forname." ".$payment->booking->staff->surname;
    });

    $data->filterColumn('booking.staff.name', function($payment, $keyword) {
        $payment->where('bookings.staffId', $keyword);
    });

    $data->editColumn('timesheet.startTime',function($payment){
        if($payment->timesheet->startTime){ return date('H:i',strtotime($payment->timesheet->startTime)); }
    });
    $data->editColumn('timesheet.endTime',function($payment){
        if($payment->timesheet->endTime){ return date('H:i',strtotime($payment->timesheet->endTime)); }
    });
    $data->editColumn('paymentWeek',function($payment){
        return "Week ".$payment->paymentWeek;
    });

    $data->filterColumn('paymentWeek',function($payment,$keyword){
        $payment->where('paymentWeek', $keyword);
    });

    $data->filterColumn('timesheet.paymentWeek', function($payment, $keyword) {
        $payment->where('timesheets.paymentWeek', $keyword);
    });

    $data->editColumn('actions',function($payment){
      switch ($payment->status) {
        case 1:
          $html = "<a href='javascript:void(0)' data-paymentid='".$payment->paymentId."' class='btn btn-primary approve-btn btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Approve</a>";
          break;

        case 2:
          $html = "<a href='javascript:void(0)' data-paymentid='".$payment->paymentId."' class='btn btn-success approve-btn btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Approved</a>";
          break;

        case 3:
          $html = "<a href='javascript:void(0)' data-paymentid='".$payment->paymentId."' class='btn btn-danger verify-btn btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Verify</a>";
          break;

        default:
          $html = "<a href='javascript:void(0)' data-paymentid='".$payment->paymentId."' class='btn btn-warning verify-btn btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Verify</a>";
          break;
      }
      return $html;
    });
    $tableData =$data->make(true);
    return $tableData;
  }

  /* Payee verify POPup Action */
  public static function varifyPayeePayment(Request $req){
      $payment = Payment::find($req->get('paymentId'));
      $payment->hourlyRate =  $req->hourlyRate;
      $payment->ta =  $req->ta;
      $payment->extraTa =  $req->extraTa;
      $payment->bonus =  $req->bonus;
      $payment->otherPay = $req->otherPay;
      if($req->has('otherPayAmount')){
        $payment->otherPayAmount = $req->otherPayAmount;
      }else{
        $payment->otherPayAmount = 0.00;
      }

      $payment->remarks =  $req->remarks;
      $payment->paymentWeek = $req->paymentWeek;
      $payment->paymentYear = $req->paymentYear;
      $payment->saved = 1;
      if($req->type==0){
        $payment->verifiedBy =  Auth::user()->adminId;
        $payment->verifiedOn =  date('Y-m-d H:i:s');
        $payment->status =  1;
      }

      $payment->save();

      if($req->type==0) {
        BookingLog::create([
            'bookingId' =>$req->bookId,
            'content' =>"<span class='logHgt'>Payment Marked </span> as <strong>Send for Approval</strong> ",
            'author' =>Auth::guard('admin')->user()->adminId,
        ]);
      } 
      if($req->type==1) {
        BookingLog::create([
            'bookingId' =>$req->bookId,
            'content' =>"<span class='logHgt'>Payment Marked </span> as <strong>Save for later</strong> ",
            'author' =>Auth::guard('admin')->user()->adminId,
        ]);
      }

      return ['status'=>true];
  }

  public static function approvePayeePayment(Request $req){
      $payment = Payment::find($req->get('paymentId'));
      $payment->hourlyRate =  $req->hourlyRate;
      $payment->ta =  $req->ta;
      $payment->extraTa =  $req->extraTa;
      $payment->bonus =  $req->bonus;
      $payment->otherPay = $req->otherPay;
      $payment->otherPayAmount = $req->otherPayAmount;
      $payment->remarks =  $req->remarks;
      $payment->paymentWeek = $req->paymentWeek;
      $payment->paymentYear = $req->paymentYear;
      if($req->type==3) {
        $payment->status =  3;
      }
      if($req->type==0) {
        $payment->status = 2;
        $payment->approvedBy =  Auth::user()->adminId;
        $payment->approvedOn =  date('Y-m-d H:i:s');
      }
      $payment->save();

      if($req->type==0) {
        BookingLog::create([
            'bookingId' =>$req->bookId,
            'content' =>"<span class='logHgt'>Payment Marked </span> as <strong>Proceed to Payment</strong> ",
            'author' =>Auth::guard('admin')->user()->adminId,
        ]);
      } 
      if($req->type==1) {
        BookingLog::create([
            'bookingId' =>$req->bookId,
            'content' =>"<span class='logHgt'>Payment Marked </span> as <strong>Save for later</strong> ",
            'author' =>Auth::guard('admin')->user()->adminId,
        ]);
      }
      if($req->type==3) {
        BookingLog::create([
            'bookingId' =>$req->bookId,
            'content' =>"<span class='logHgt'>Payment Marked </span> as <strong>Revert to Verifier</strong> ",
            'author' =>Auth::guard('admin')->user()->adminId,
        ]);
      }

      return ['status'=>true];
  }
}
?>
