<?php
namespace App\Http\Controllers\Payments\Selfie;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Mail\SendPayeeRemittenceAdvice;
use Auth;
use PDF;
use Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Mail;
use App\Models\PaymentArchive;
use App\Models\ClientUnitSchedule;
use App\Models\Timesheet;
use App\Models\Booking;
use App\Models\TaxWeek;
use App\Models\TaxYear;
use App\Models\BookingLog;
use App\Models\Admin;
use App\Http\Controllers\Payments\Payee\PayeeWeek;

trait SelfieWeek{
  public static  function data(Request $request){
      session()->forget('payments');
      $query = Payment::group()->with(['booking','booking.unit','booking.category','archive'])
                ->join('timesheets','timesheets.timesheetId','payments.timesheetId')
                ->join('bookings','bookings.bookingId','payments.bookingId')
                ->join('staffs','staffs.staffId','bookings.staffId')
                // ->join('payment_archives','payment_archives.paymentId','payments.paymentId')
                ->selectRaw('ng_payments.*,count(*) as numberOfVerifiedBookings')
                ->addSelect('timesheets.staffHours')
                ->where('staffs.paymentMode',1)
                ->where('payments.status',2)
                // ->where('payment_archives.paymentDate','>=', date('Y-m-d', strtotime('-7 day')))
                // ->orWhere('payment_archives.paymentDate','=', null)
                ->selectRaw('(SUM(ng_payments.ta)+SUM(ng_payments.extraTa)+SUM(ng_payments.bonus)+SUM(ng_payments.hourlyRate)) as amtotal')
                ->groupBy('bookings.staffId');

      if($request->draw == 1){
        $deafultYear = TaxYear::where('default', 1)->first();
        $taxCurrentWeek = TaxWeek::where('taxYearId',$deafultYear->taxYearId)->groupBy('weekNumber')->where('date',date('Y-m-d'))->first();
        $query->where('paymentWeek','=',$taxCurrentWeek->weekNumber);
      }

      $data = Datatables::of($query);
      $data->addIndexColumn();

      $data->editColumn('archive.statusBtn',function($payment){
          if($payment->archive->raStatus == 0 && $payment->archive->isPaymentRecorded == 0){
            return "<a class='btn btn-danger btn-xs mrs'>To be Generate RA</a>" ;
          }
          if($payment->archive->raStatus == 1 && $payment->archive->isPaymentRecorded == 0){
            return "<a class='btn btn-primary btn-xs mrs'>Ready to Payment</a>" ;
          }
          if($payment->archive->raStatus == 1 && $payment->archive->isPaymentRecorded == 1){
            return "<a class='btn btn-success btn-xs mrs'>Payment Recorded</a>" ;
          }
      });
      $data->filterColumn('archive.statusBtn', function($payment) {
        if($payment->archive->isPaymentRecorded ==1){
          $payment->where('payment_archives.paymentDate','>=', date('Y-m-d', strtotime('-7 day')));
        }
      });
      $data->filterColumn('booking.staff.name', function($payment, $keyword) {
          $payment->where('bookings.staffId', $keyword);
      });

      $data->editColumn('booking.staff.name',function($payment){
          return $payment->booking->staff->forname." ".$payment->booking->staff->surname;
      });

      $data->editColumn('companyName',function($payment){
          return $payment->booking->staff->selfPaymentCompanyName;
      });

      $data->editColumn('noOfActualBooking',function($payment){
        $dates = TaxWeek::where('taxYearId',$payment->paymentYear)->where('weekNumber',$payment->paymentWeek)->pluck('date')->toArray();
        $bookings = Booking::where('staffId',$payment->booking->staffId)->whereIn('date',$dates)->count();
        return $bookings;
      });

      $data->editColumn('paymentWeek',function($payment){
          return "Week ".$payment->paymentWeek;
      });

      $data->filterColumn('paymentWeek',function($payment,$keyword){
          $payment->where('paymentWeek', $keyword);
      });

      $data->editColumn('amtotal',function($payment){
          return number_format((($payment->amtotal * $payment->staffHours)+$payment->otherPayAmount),2);
      });

      $data->editColumn('actions',function($payment){
          $html = "";
          $html .= "<a href='".route('payment.selfie.week.review',[$payment->paymentWeek,$payment->booking->staffId])."' class='btn btn-success btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Review</a>";

          if($payment->archive->raStatus == 1){ $rcBtn = "success";$actn = route('payment.selfie.ra',[$payment->paymentWeek,$payment->booking->staffId]); }else{$rcBtn = "warning";$actn = "javascript:void(0)";}

          $html .= "<a href='".$actn."' class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> RA</a>";

          if($payment->archive->isEmailSent == 1){ $rcBtn = "success";}else{ $rcBtn = "warning";}
          if($payment->archive->raStatus == 1){ $actn = route('payment.selfie.ra.email',[$payment->paymentWeek,$payment->booking->staffId]);}else{ $actn = "javascript:void(0)";}

          $html .= "<a href='".$actn."' class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Email</a>";

          if($payment->archive->isPaymentRecorded == 1){ $rcBtn = "success";}else{$rcBtn = "warning";}
          if($payment->archive->raStatus == 1){ $actn = route('payment.selfie.ra.record.payment',[$payment->paymentWeek,$payment->booking->staffId]);}else{ $actn = "javascript:void(0)";}

          $html .= "<a href='".$actn."' class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Record Payment</a>";
          return $html;
      });
      $tableData =$data->make(true);
      return $tableData;
  }

  public static function selfieWeekReview($week,$staffId){
    $archives = PaymentArchive::pluck('paymentId')->toArray();
    $payments = Payment::join('timesheets','timesheets.timesheetId','payments.timesheetId')
            ->whereHas('booking.staff', function ($query) use ($staffId){
                $query->where('paymentMode',1);
                $query->where('staffId',$staffId);
            })
            ->whereHas('timesheet', function ($query) use ($week){
                $query->where('paymentWeek',$week);
            })->get();
    $paymentIds = [];
    for ($i=0; $i < count($payments); $i++) {
      if(in_array($payments[$i]->paymentId,$archives)){
        $payments[$i]['archived'] = 1;
        $paymentIds[] = $payments[$i]->paymentId;
      }else{
        $payments[$i]['archived'] = 0;
      }
      $payments[$i]['DateDay'] = date('d-m-Y D',strtotime($payments[$i]->booking->date));
      $payments[$i]['grossPay'] = ($payments[$i]->hourlyRate+$payments[$i]->bonus)*$payments[$i]->timesheet->staffHours;
      $payments[$i]['totalTA'] = $payments[$i]->ta * $payments[$i]->timesheet->staffHours;
      $payments[$i]['grossTA'] = ($payments[$i]->ta + $payments[$i]->extraTa) * $payments[$i]->timesheet->staffHours;
      $payments[$i]['shiftGrandTotal'] = $payments[$i]['grossPay']+$payments[$i]['grossTA']+$payments[$i]->otherPayAmount;
      $payments[$i]['hldyPay'] = ($payments[$i]['grossPay'] * 12.08)/100 ;
      $payments[$i]['weeklyPay'] = $payments[$i]['grossPay'] - $payments[$i]['hldyPay'];
      $payments[$i]['ratePerHr'] = ($payments[$i]->hourlyRate) + ($payments[$i]->ta) + ($payments[$i]->extraTa) + ($payments[$i]->bonus);

      $payments[$i]['verifiedBy'] = Admin::find($payments[$i]->timesheet->verifiedBy);
      $payments[$i]['verifiedByName'] = $payments[$i]['verifiedBy']['name'];

      $payments[$i]['approvedBy'] = Admin::find($payments[$i]->approvedBy);
      $payments[$i]['approvedByName'] = $payments[$i]['approvedBy']['name'];
      $payments[$i]->scheduleStaffHours = ClientUnitSchedule::where('clientUnitId',$payments[$i]->booking->unitId)
                                      ->where('staffCategoryId',$payments[$i]->booking->categoryId)
                                      ->where('shiftId',$payments[$i]->booking->shiftId)
                                      ->first();

    }
    if(count($payments) > 0){
      //Number of shifts to be paid for this week
      $dates = TaxWeek::where('taxYearId',$payments[0]->paymentYear)->where('weekNumber',$week)->pluck('date')->toArray();
      $payments[0]['bookingsNum'] = Booking::where('staffId',$staffId)->whereIn('date',$dates)->count();

      //Number of shift included this week payment
      $payments[0]['weekPayment'] = Booking::Join('payments','payments.bookingId','bookings.bookingId')
          ->where('payments.paymentWeek',$week)
          ->where('payments.paymentYear',$payments[0]->paymentYear)
          ->where('staffId',$staffId)->whereNotIn('date',$dates)->count();

      session()->put('payments',$paymentIds);

      //return $payments;
      return view('payments.selfie.review_week',compact('payments'));
    }else{
      return redirect(route('payment.payee.weeks.list'));
    }

  }
  public static function moveToNextWeek($paymentId){
    $payment = Payment::with(['timesheet','booking'])->find($paymentId);
    $currentWeek = $payment->timesheet->paymentWeek;
    $timesheet = Timesheet::find($payment->timesheet->timesheetId);
    $timesheet->paymentWeek = $currentWeek+1;
    $timesheet->save();

    BookingLog::create([
      'bookingId' =>$payment->booking->bookingId,
      'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong>Move to Next week</strong> ",
      'author' =>Auth::guard('admin')->user()->adminId,
    ]);

    return redirect(route('payment.selfie.week.review',[$currentWeek,$payment->booking->staffId]))->with('message','Succesfully Moved to Next Week');

  }

  public static function moveToArchives($paymentId){
    $payment = Payment::with(['timesheet','booking'])->find($paymentId);
    $currentWeek = $payment->paymentWeek;
    $number = PaymentArchive::whereHas('payment.booking', function ($query) use($payment) {
                $query->where('staffId', $payment->booking->staffId);
              })->groupBy('raNumber')->whereNotNull('raNumber')->get();

    if(count($number) ==0){  // No RA for the Staff
      $archive = PaymentArchive::firstOrCreate([
        'paymentId' =>$paymentId
      ]);
      $archive->raNumber = strtoupper(substr($payment->booking->staff->forname, 0, 3))."1";
      $archive->raDate = date('Y-m-d');
      $archive->save();
    }else{  // IF RA Exist for that Staff
      $existInThisWeek = PaymentArchive::whereHas('payment', function ($query) use($payment) {
          $query->where('paymentWeek', $payment->paymentWeek);
          $query->where('paymentYear', $payment->paymentYear);
      })->first();
      if(isset($existInThisWeek)){  // Already a RA Generated for this year & Week
        $archive = PaymentArchive::firstOrCreate([
          'paymentId' =>$payment->paymentId
        ]);
        $archive->raNumber = $existInThisWeek->raNumber;
        $archive->save();

        PaymentArchive::whereHas('payment', function ($query) use($payment,$archive) {
            $query->where('paymentWeek', $payment->paymentWeek);
            $query->where('paymentYear', $payment->paymentYear);
        })->update(['raDate'=>date('Y-m-d')]);
      }else{
        $lastRa = PaymentArchive::whereHas('payment.booking', function ($query) use($payment) {
          $query->where('staffId', $payment->booking->staffId);
        })->groupBy('raNumber')->whereNotNull('raNumber')->latest()->first();

        $lastRaNumber = substr($lastRa->raNumber,3,3);
        $archive = PaymentArchive::firstOrCreate([
          'paymentId' =>$paymentId,
          'raNumber'  =>strtoupper(substr($payment->booking->staff->forname, 0, 3)).($lastRaNumber+1),
          'raDate'=>date('Y-m-d')
        ]);
      }
    }
    BookingLog::create([
      'bookingId' =>$payment->booking->bookingId,
      'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong>Moved to Archives</strong> ",
      'author' =>Auth::guard('admin')->user()->adminId,
    ]);
    return redirect(route('payment.selfie.week.review',[$currentWeek,$payment->booking->staffId]))->with('message','Successfully marked to payment');
  }

  public static function revertToVA($paymentId) {
    $payment = Payment::with(['timesheet','booking'])->find($paymentId);
    $checkExist = PaymentArchive::where('paymentId',$paymentId)->get();

    $currentWeek = $payment->paymentWeek;
    $payment->status =  3;
    $payment->save();

    if(!empty($checkExist)) {
      PaymentArchive::where('paymentId',$paymentId)->delete();
    }

    BookingLog::create([
      'bookingId' =>$payment->booking->bookingId,
      'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong>Revert to V & A</strong> ",
      'author' =>Auth::guard('admin')->user()->adminId,
    ]);

    return redirect(route('payment.selfie.week.review',[$currentWeek,$payment->booking->staffId]))->with('message','Successfully reverted to V & A');
  }

  public static function generateRASelfie($week,$staffId){
    $sessionId = session()->get('payments');
    if($sessionId){
      PaymentArchive::whereIn('paymentId',$sessionId)->update([
        'raStatus'  =>1
      ]);
      $payments = Payment::with(['timesheet'])
              ->Join('payment_archives','payment_archives.paymentId','payments.paymentId')
              ->whereIn('payment_archives.paymentId',$sessionId)->where('payment_archives.raStatus',1)->get();
      $from  = 0;
      $payments = SelfieWeek::processPaymentArray($payments);

      BookingLog::create([
        'bookingId' =>$payments[0]->booking->bookingId,
        'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong>Generate RA</strong> ",
        'author' =>Auth::guard('admin')->user()->adminId,
      ]);

      return view('payments.selfie.remittenceAdvice',compact('payments','from'));
    }else{
      $payments = Payment::with(['timesheet','archive'])
                  ->Join('payment_archives','payment_archives.paymentId','payments.paymentId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$staffId)
                  ->where('paymentWeek',$week)
                  ->where('payment_archives.raStatus',1)->get();
      $from  = 1;

      BookingLog::create([
        'bookingId' =>$payments[0]->booking->bookingId,
        'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong>Generate RA</strong> ",
        'author' =>Auth::guard('admin')->user()->adminId,
      ]);

      if(!empty($payments)){
        $payments = SelfieWeek::processPaymentArray($payments);
        return view('payments.selfie.remittenceAdvice',compact('payments','from'));
      }else{
        return redirect(route('payment.selfie.week.review',[$week,$staffId]))->with('message','Error !!. generate RA before this action');
      }

    }
  }

  public static function raEmailSelfie($week,$staffId){
    $sessionId = session()->get('payments');
    if($sessionId){
      $payments = Payment::with(['timesheet'])->whereIn('paymentId',session()->get('payments'))->get();
    }else{
      $payments = Payment::with(['timesheet','archive'])
                  ->Join('payment_archives','payment_archives.paymentId','payments.paymentId')
                  ->Join('timesheets','payments.timesheetId','timesheets.timesheetId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$staffId)
                  ->where('paymentWeek',$week)
                  ->where('payment_archives.raStatus',1)->get();
    }
    $payments = SelfieWeek::processPaymentArray($payments);

    BookingLog::create([
      'bookingId' =>$payments[0]->booking->bookingId,
      'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong> Email RA </strong> ",
      'author' =>Auth::guard('admin')->user()->adminId,
    ]);

    if(!empty($payments)){
      Mail::to('jishadp369@gmail.com')->cc('mcjobi@nursesgroup.co.uk')->queue(new SendPayeeRemittenceAdvice($payments));
      for ($i=0; $i < count($payments); $i++) {
        $payments[$i]->archive->isEmailSent = 1;
        $payments[$i]->archive->save();
      }
      return redirect(route('payment.selfie.ra',[$week,$staffId]))->with('message','Succesfully Sent Email');
    }else{
      return redirect(route('payment.selfie.week.review',[$week,$staffId]))->with('message','Error sending email. generate RA before sending Email');
    }

  }
  public static function rAPdfSelfie($week,$staffId){
    $sessionId = session()->get('payments');

    if($sessionId){
      $payments = Payment::with(['timesheet'])->whereIn('paymentId',session()->get('payments'))->get();
    }else{
      $payments = Payment::with(['timesheet'])
                  ->Join('payment_archives','payment_archives.paymentId','payments.paymentId')
                  ->Join('timesheets','payments.timesheetId','timesheets.timesheetId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$staffId)
                  ->where('paymentWeek',$week)
                  ->where('payment_archives.raStatus',1)->get();
    }

    $payments = SelfieWeek::processPaymentArray($payments);
    $pdfName = $payments[0]->booking->staff->forname." ".$payments[0]->booking->staff->surname." Week ".$week.".pdf";

    BookingLog::create([
      'bookingId' =>$payments[0]->booking->bookingId,
      'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong> Download RA </strong> ",
      'author' =>Auth::guard('admin')->user()->adminId,
    ]);

    $pdf = PDF::loadView('email.payee_ra',compact('payments'))->setPaper('A4', 'landscape');
    return $pdf->stream($pdfName);
  }

  public static function raRecordPayment($week,$staffId){
    session()->put('rp',true);
    $admins = Admin::all();
    $payments = Payment::with(['timesheet','archive'])
                  ->Join('payment_archives','payment_archives.paymentId','payments.paymentId')
                  ->Join('timesheets','payments.timesheetId','timesheets.timesheetId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$staffId)
                  ->where('paymentWeek',$week)
                  ->where('payment_archives.raStatus',1)->get();
      $from  = 1;

      $payments = SelfieWeek::processPaymentArray($payments);

      if(!empty($payments)){
        return view('payments.selfie.record_payment',compact('payments','from','admins'));
      }else{
        return redirect(route('payment.selfie.week.review',[$week,$staffId]))->with('message','Error !!. generate RA before this action');
      }
  }

  public static function raRecordPaymentAction($request){
    $payments = Payment::with(['timesheet','archive'])
                  ->Join('payment_archives','payment_archives.paymentId','payments.paymentId')
                  ->Join('timesheets','payments.timesheetId','timesheets.timesheetId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$request->staffId)
                  ->where('paymentWeek',$request->week)
                  ->where('payment_archives.raStatus',1)->get();
    $from  = 0;
    for ($i=0; $i < count($payments); $i++) {
      if($request->paymentDate){
        $payments[$i]->archive->paymentDate = date('Y-m-d',strtotime($request->paymentDate));
      }
      $payments[$i]->archive->bankId = $request->bankId;
      $payments[$i]->archive->transactionNumber = $request->transactionNumber;
      $payments[$i]->archive->handledBy = $request->handledBy;
      $payments[$i]->archive->recordPaymentTime  = date('Y-m-d H:i:s');
      $payments[$i]->archive->isPaymentRecorded = 1;
      $payments[$i]->archive->save();
    }

    BookingLog::create([
      'bookingId' =>$payments[0]->booking->bookingId,
      'content' =>"<span class='logHgt'>Selfie Marked </span> as <strong> Record Payment </strong> ",
      'author' =>Auth::guard('admin')->user()->adminId,
    ]);

    return redirect(route('payment.selfie.ra.record.payment',[$request->week,$request->staffId]))->with('message','Sucesfully Record Payment');
  }

  public static function processPaymentArray($payments){
    for ($i=0; $i < count($payments); $i++) {
      $payments[$i]['DateDay'] = date('d-m-Y D',strtotime($payments[$i]->booking->date));
      $payments[$i]['Desc'] = PayeeWeek::generateDescColumn($payments[$i]);
      $payments[$i]['grossTa'] = ($payments[$i]->ta+$payments[$i]->extraTa)*$payments[$i]->timesheet->staffHours;
      $payments[$i]['grossPay'] = ($payments[$i]->hourlyRate+$payments[$i]->bonus)*$payments[$i]->timesheet->staffHours;
      $payments[$i]['shiftTotal'] = number_format($payments[$i]['grossPay'] + $payments[$i]['grossTa'] + $payments[$i]->otherPayAmount,2);
    }
    return $payments;
  }

    public static function weekReportSelfie($weekNum) {
    $taxDefaultYear = TaxYear::where('default',1)->get();
    $taxYearId =  $taxDefaultYear[0]->taxYearId;

    $payments = Payment::with(['timesheet','booking','booking.shift','booking.unit','booking.category','booking.staff'])
            ->join('timesheets','timesheets.timesheetId','payments.timesheetId')
            ->where('paymentWeek',$weekNum)
            ->where('paymentYear',$taxYearId)
            ->where('payments.status',2)
            ->get();
            // return $payments;

    if(!empty($payments)) {
    (new FastExcel($payments))->download("Selfie Week Report-".time().".xlsx", function ($payment) {
      if($payment->timesheet->breakHours != '') {
        $breakHrs = $payment->timesheet->breakHours;
      } else {
        $breakHrs = 0;
      }
      if($payment->timesheet->unitHours != '') {
        $workdHrs = $payment->timesheet->unitHours;
      } else {
        $workdHrs = 0;
      }
      if($payment->otherPayAmount != '') {
        $otherPayAmount = $payment->otherPayAmount;
      } else {
        $otherPayAmount = 0;
      }
          $exels = ['Shift ID' => $payment->booking->bookingId,
                'Nursing Home' => $payment->booking->unit->name,
                'TS No'        => $payment->timesheetId,
                'Date'         => date('d-m-Y, D',strtotime($payment->booking->date)),
                'Start Time'   => $payment->timesheet->startTime,
                'End Time'     => $payment->timesheet->endTime,
                'Break'        => $breakHrs,
                'Hours Worked' => $workdHrs,
                'Hourly Rate'  => "£ ".number_format($payment->hourlyRate,2),
                'TA'           => "£ ".number_format($payment->ta,2),
                'Extra TA'     => "£ ".number_format($payment->extraTa,2),
                'Bonus'        => "£ ".number_format($payment->bonus,2),
                'Otherpay'     => "£ ".number_format($otherPayAmount,2) ];
      return $exels;
      });
    }
    else {
      return redirect(route('payment.selfie.weeks.list'));
    }
  }

  public static function weekSelfiePaymentReport($weekNum) {
    $taxDefaultYear = TaxYear::where('default',1)->get();
    $taxYearId =  $taxDefaultYear[0]->taxYearId;

    $payments = Payment::with(['timesheet','booking','booking.shift','booking.unit','booking.category','booking.staff'])
            ->join('timesheets','timesheets.timesheetId','payments.timesheetId')
            ->where('paymentWeek',$weekNum)
            ->where('paymentYear',$taxYearId)
            ->where('payments.status',2)
            ->get();

    // return $payments;

    if(!empty($payments)) {
    (new FastExcel($payments))->download("Selfie Payment Report-".time().".xlsx", function ($payment) {

      $staff = $payment->booking->staff->forname." ".$payment->booking->staff->surname;
      $grossPay = ($payment->hourlyRate+$payment->bonus)*$payment->timesheet->staffHours;
      $totalTA = $payment->ta * $payment->timesheet->staffHours;
      $grossTA = ($payment->ta + $payment->extraTa) * $payment->timesheet->staffHours;
      $shiftGrandTotal = $grossPay+$grossTA+$payment->otherPayAmount;
      if($payment->booking->staff->selfPaymentCompanyName != '') {
        $company = $payment->booking->staff->selfPaymentCompanyName;
      } else {
        $company = '';
      }
      $sortcode = $payment->booking->staff->bankSortCodeA.$payment->booking->staff->bankSortCodeB.$payment->booking->staff->bankSortCodeC;

          $exels = ['Sl No'    => 1,
                'Staff Name'   => $staff,
                'Company'      => $company,
                'Amount'       => "£ ".number_format($shiftGrandTotal,2),
                'Sort Code'    => $sortcode,
                'A/C No'       => $payment->booking->staff->bankAccountNumber,
                 ];

      return $exels;
      });
    }
    else {
      return redirect(route('payment.selfie.weeks.list'));
    }

  }

}
?>
