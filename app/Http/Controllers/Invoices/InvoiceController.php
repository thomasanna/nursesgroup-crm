<?php

namespace App\Http\Controllers\Invoices;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\TaxYear;
use App\Models\Payment;
use App\Models\ClientUnit;
use App\Models\Admin;
use App\Models\ClientUnitSchedule;
use App\Models\Shift;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\ClientUnitPayment;
use App\Models\BookingLog;
use App\Http\Controllers\Payments\PaymentHelper;

use Yajra\Datatables\Facades\Datatables;

use Auth;

class InvoiceController
{
  public function invoiceList(Request $request){
    $taxYears = TaxYear::all();
    $shifts = Shift::all();
    $units = ClientUnit::where('status',1)->orderBy('name','ASC')->get();
    $staffs = Staff::where('status',1)->orderBy('forname','ASC')->get();
    $staffCategory = StaffCategory::all();
    return view('invoices.list',compact('taxYears','staffs','staffCategory','shifts','units'));
  }

  public function invoiceData(Request $request){
    $query = Invoice::Join('bookings','bookings.bookingId','invoices.bookingId')
            ->with(['timesheet','booking','booking.shift','booking.unit','booking.category','booking.staff'])
            ->where('status','0');
            // ->whereNull('approvedBy');
    if(empty($request->columns[1]['search']['value'])){
      $query->whereDate('bookings.date','=',date('Y-m-d',strtotime("-1 days")));
    }
    $data = Datatables::of($query);
    $data->addIndexColumn();
    $data->filterColumn('booking.date', function($payment, $keyword) {
      $dates = explode(" - ",$keyword);
      $payment->whereBetween('bookings.date',[date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))]);
    });
    $data->editColumn('booking.date',function($payment){
        if($payment->booking->date) { return date('d-M-Y, D',strtotime($payment->booking->date)); }
    });
    $data->editColumn('timesheet.status',function($payment){
        if($payment->timesheet->status ==0){ return "<span class='label label-primary'>New</span>";}
        if($payment->timesheet->status ==1){ return "<span class='label label-warning'>Checked</span>";}
        if($payment->timesheet->status ==2){ return "<span class='label label-success'>Verified</span>";}
    });

    $data->filterColumn('booking.shift.name', function($payment, $keyword) {
        $payment->where('bookings.shiftId', $keyword);
    });

    $data->filterColumn('booking.category.name', function($payment, $keyword) {
        $payment->where('bookings.categoryId', $keyword);
    });

    $data->filterColumn('booking.staff.name', function($payment, $keyword) {
        $payment->where('bookings.staffId', $keyword);
    });

    $data->editColumn('booking.staff.name',function($payment){
        return $payment->booking->staff->forname." ".$payment->booking->staff->surname;
    });

    $data->filterColumn('booking.unit.alias', function($payment, $keyword) {
      $payment->where('bookings.unitId', $keyword);
    });

    $data->editColumn('timesheet.startTime',function($payment){
        if($payment->timesheet->startTime){ return date('H:i',strtotime($payment->timesheet->startTime)); }
    });
    $data->editColumn('timesheet.endTime',function($payment){
        if($payment->timesheet->endTime){ return date('H:i',strtotime($payment->timesheet->endTime)); }
    });
    $data->editColumn('timesheet.paymentWeek',function($payment){
        return "Week ".$payment->timesheet->paymentWeek;
    });
    $data->editColumn('timesheet.tslog',function($payment){
        return "<button class='btn btn-primary btn-xs mrs openLogModal name='".$payment->booking->staff->forname." ".$payment->booking->staff->surname.  
          "' category='".$payment->booking->category->name."'  booking='".$payment->booking->bookingId."' shift='".$payment->booking->shift->name."' unit='".$payment->booking->unit->name."'>Shift Log</button>";
    
    });
    $data->editColumn('actions',function($payment){
      if($payment->saved == 1) { $clsBtn = 'warning'; } else { $clsBtn= 'success'; }
      $html = "";
      $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($payment->booking->bookingId)])." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Allocate</a>";
      $html .= "<a href='javascript:void(0)' data-invoice='".$payment->invoiceId."' class='btn btn-".$clsBtn." verify-btn btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Verify</a>";
      return $html;
    });
    $tableData =$data->make(true);
    return $tableData;
  }

  public function getSinglePayment(Request $request){
    $payment = Invoice::with(['timesheet','booking','booking.shift','booking.unit','booking.category','booking.staff'])
                        ->find($request->get('invoiceId'));
    $shifType = 'day';
    switch (strtolower($payment->booking->shift->name)) {
      case 'early':
        $shifType = "day";
        break;
      case 'late':
        $shifType = "day";
        break;
      case 'longday':
        $shifType = "day";
        break;
      case 'night':
        $shifType = "night";
        break;
    }
    $payment->booking->date = date('d-M-Y, D',strtotime($payment->booking->date));
    $payment->timesheet->startTime = date('H:i',strtotime($payment->timesheet->startTime));
    $payment->timesheet->endTime = date('H:i',strtotime($payment->timesheet->endTime));
    $schedules = ClientUnitSchedule::where('clientUnitId',$payment->booking->unit->clientUnitId)
                                ->where('staffCategoryId',$payment->booking->category->categoryId)
                                ->where('shiftId',$payment->booking->shiftId)
                                ->first();
    $clientPaymentsRate = ClientUnitPayment::where('clientUnitId',$payment->booking->unit->clientUnitId)
                                ->where('staffCategoryId',$payment->booking->category->categoryId)->where('rateType',1)->first();
    $clientPaymentsEnic = ClientUnitPayment::where('clientUnitId',$payment->booking->unit->clientUnitId)
                                ->where('staffCategoryId',$payment->booking->category->categoryId)->where('rateType',2)->first();

    $payment->booking->unit->unitHours = $schedules->totalHoursUnit;
    $payment->booking->unit->hourlyRate = number_format($this->calculateHourlyRate($clientPaymentsRate,date('N',strtotime($payment->booking->date)),$shifType),2);
    if(!empty($clientPaymentsEnic)){
      $payment->booking->unit->enic = $this->calculateHourlyRate($clientPaymentsEnic,date('N',strtotime($payment->booking->date)),$shifType);
    }else{ $payment->booking->unit->enic =  "0.00";}

    if($clientPaymentsRate->taPerMile){
      $payment->booking->transportAllowence = number_format(($clientPaymentsRate->taPerMile*$clientPaymentsRate->taNoOfMiles),2);
    }else{
      $payment->booking->transportAllowence = "0.00";
    }

    $payment->booking->taDistnceViz = number_format(($payment->booking->distenceToWorkPlace*$clientPaymentsRate->taPerMile),2);
    $payment->booking->totalHrate = number_format(($schedules->totalHoursUnit*$payment->booking->unit->hourlyRate),2);
    if(!empty($clientPaymentsEnic)){
      $payment->booking->totEnic = number_format(($schedules->totalHoursUnit*$payment->booking->unit->enic),2);
    }else{ $payment->booking->totEnic =  0.00;}

    if($payment->saved==0){  // Not Saved
      //$payment->booking->transportAllowence = number_format($payment->booking->transportAllowence,2);
      $payment->booking->unit->enic = number_format($payment->booking->unit->enic,2);
      $payment->booking->taDistnceViz = number_format($payment->booking->taDistnceViz,2);
      $payment->booking->distenceToWorkPlace = $payment->booking->distenceToWorkPlace;
      $payment->booking->unit->invoiceFrequency = $payment->booking->unit->invoiceFrequency;
      $payment->paymentYear = '4';
      if($payment->booking->unit->invoiceFrequency == 1) {
        $payment->weekNumbr = $request->weekNum;
        $payment->monthNumbr = $request->monthNum;
      } else {
        $payment->weekNumbr = $request->weekNum;
        $payment->monthNumbr = $request->monthNum;
      }

    }else{
      //$payment->booking->transportAllowence = number_format($payment->ta,2);
      $payment->booking->taDistnceViz = number_format($payment->taDistnceViz,2);
      $payment->booking->distenceToWorkPlace = number_format($payment->unitDistence,2);
      $payment->booking->unit->enic = number_format($payment->enic,2);
      $payment->booking->unit->invoiceFrequency = $payment->invceFrqncy;
      $payment->paymentYear = $payment->weekYear;
      if($payment->booking->unit->invoiceFrequency == 1) {
        $payment->weekNumbr = $payment->weekNumbr;
        $payment->monthNumbr = $request->monthNum;;
      } else {
        $payment->weekNumbr = $request->weekNum;
        $payment->monthNumbr = $payment->monthNumbr;
      }

    }
    $payment->booking->lineTotal = number_format(($payment->booking->totalHrate+$payment->booking->totEnic+$payment->booking->transportAllowence),2);
    $payment->timesheet->tsCheckedBy = PaymentHelper::getAdminName($payment->timesheet->editedBy);
    $payment->timesheet->tsVrfdBy = PaymentHelper::getAdminName($payment->timesheet->verifiedBy);
    $payment->pymnetVrfdBy = PaymentHelper::getAdminName(Auth::user()->adminId);

    $payment->addiotnalStaff = PaymentHelper::additionalStaff($payment->booking);
    $payment->scheduleStaffHours = ClientUnitSchedule::where('clientUnitId',$payment->booking->unitId)
                                        ->where('staffCategoryId',$payment->booking->categoryId)
                                        ->where('shiftId',$payment->booking->shiftId)
                                        ->first();

    $payment->weeks = $this->isWeekend($payment->booking->date,$payment->booking->shift->name);
    // return $payment->weeks;
    return ['status'=>true,"data"=>$payment];
  }

  public  function isWeekend($date,$shift) {
    $dt1 = strtotime($date);
    $dt2 = date("l", $dt1);
    $dt3 = strtolower($dt2);
    if((($shift == 'Early') || ($shift == 'Late') || ($shift == 'Longday')) && (($dt3 != "saturday" ) || ($dt3 != "sunday")))
    {
       $weeks = "( Week ) ( Day )"; //black
    }
    else if((($dt3 == 'saturday') || ($dt3 == 'sunday')) && ($shift == 'Night') )
    {
      $weeks = "( Weekend) ( Night) "; // red
    }
    else if( ($shift == 'Night') && (($dt3 != "saturday" ) || ($dt3 != "sunday") || ($dt3 != 'friday')) )
    {
      $weeks = "( Week ) ( Night )"; // black
    }
    else if(($dt3 == 'friday') && ($shift == 'Night'))
    {
      $weeks = "( Friday) ( Night )"; // red
    }
    else if((($dt3 == 'saturday') && ($dt3 == 'sunday')) && (($shift == 'Early') || ($shift == 'Late') || ($shift == 'Longday')) )
    {
      $weeks = "( Weekend) ( Day) "; // red
    }

    else {
      $weeks = '';
    }
        return $weeks ;
  }

  public function calculateHourlyRate($rate,$day,$shifType){
    switch ($day) {
      case 1:
        if($shifType=="day"){ return $rate->dayMonday; }else{ return $rate->nightMonday; }
        break;
      case 2:
        if($shifType=="day"){ return $rate->dayTuesday; }else{ return $rate->nightTuesday; }
        break;
      case 3:
        if($shifType=="day"){ return $rate->dayWednesday; }else{ return $rate->nightWednesday; }
        break;
      case 4:
        if($shifType=="day"){ return $rate->dayThursday; }else{ return $rate->nightThursday; }
        break;
      case 5:
        if($shifType=="day"){ return $rate->dayFriday; }else{ return $rate->nightFriday; }
        break;
      case 6:
        if($shifType=="day"){ return $rate->daySaturday; }else{ return $rate->nightSaturday; }
        break;
      case 7:
        if($shifType=="day"){ return $rate->daySunday; }else{ return $rate->nightSunday; }
        break;
    }
  }

  public function varifyInvoice(Request $req){
    $invoice = Invoice::find($req->get('invoiceId'));
    $invoice->hourlyRate =  $req->hRate;
    $invoice->enic =  $req->enic;
    $invoice->ta =  $req->ta;
    $invoice->unitDistence =  $req->unitDistence;
    $invoice->invceFrqncy =  $req->invceFrqncy;
    $invoice->weekYear =  $req->paymentYear;
    if($req->invceFrqncy==1){
      $invoice->weekNumbr =  $req->weekNumbr;
      $invoice->monthNumbr =  '';
    }else{
      $invoice->monthNumbr =  $req->monthNumbr;
      $invoice->weekNumbr =  '';
    }

    if($req->type==1) {
      $invoice->status= 1; // Verified
      $invoice->verifiedBy =  Auth::user()->adminId;
      $invoice->verifiedOn =  date('Y-m-d');
    } else {
      $invoice->status = 0;
    }
    $invoice->saved=1;
    $invoice->remarks=$req->remarks;
    $invoice->save();
// return $invoice;
    if($req->type == 0) {
      BookingLog::create([
        'bookingId' =>$req->bookId,
        'content' =>"<span class='logHgt'>Unit Bills </span> as <strong>Save for later</strong> ",
        'author' =>Auth::guard('admin')->user()->adminId,
      ]);
    } 
    if($req->type == 1) {
      BookingLog::create([
        'bookingId' =>$req->bookId,
        'content' =>"<span class='logHgt'>Unit Bills </span> as <strong>Proceed to Payment</strong> ",
        'author' =>Auth::guard('admin')->user()->adminId,
      ]);
    }
    
    return ['status'=>true];
  }

}
