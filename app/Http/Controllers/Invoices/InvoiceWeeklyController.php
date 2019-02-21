<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceArchive;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientUnit;
use App\Models\ClientUnitPayment;
use App\Models\Admin;
use App\Mail\SendUnitInvoice;
use App\Models\Shift;
use App\Models\Staff;
use App\Models\StaffCategory;
use PDF;
use Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use Mail;
use Yajra\Datatables\Facades\Datatables;
use App\Models\TaxYear;
use App\Models\TaxWeek;

class InvoiceWeeklyController
{
    public function invoiceWeeklyList(Request $request){
      $units = ClientUnit::where('status',1)->orderBy('name','ASC')->get();
      $defYear = TaxYear::where('default',1)->first();
      $weeks = TaxWeek::where('taxYearId',$defYear->taxYearId)->groupBy('weekNumber')->get();
	    return view('invoices.weekly.list',compact('weeks','units'));
	}
	public function invoiceWeeklyData(Request $request){
	    $query = Invoice::Join('bookings','bookings.bookingId','invoices.bookingId','archive')
			  ->with(['timesheet','booking','booking.shift','booking.unit','booking.category','booking.staff'])
              ->groupBy('bookings.unitId')
              ->groupBy('invoices.weekNumbr')
              ->select('invoices.*')
              ->where('invceFrqncy',1)->verified();

	    $data = Datatables::of($query);
	    $data->addIndexColumn();

      $data->filterColumn('booking.unit.alias', function($invoice, $keyword) {
          $invoice->where('bookings.unitId', $keyword);
      });
	    $data->editColumn('weekNumbr',function($invoice){
          return "Week ".$invoice->weekNumbr;
      });
      $data->filterColumn('weekNumbr',function($invoice,$keyword){
          $invoice->where('weekNumbr', $keyword);
	    });

      $data->editColumn('statusBtn',function($invoice){
          if($invoice->archive->invoiceStatus == 0 && $invoice->archive->isPaymentRecorded == 0){
            return "<a class='btn btn-danger btn-xs mrs'>To be Generate RA</a>" ;
          }
          if($invoice->archive->invoiceStatus == 1 && $invoice->archive->isPaymentRecorded == 0){
            return "<a class='btn btn-primary btn-xs mrs'>Ready to Payment</a>" ;
          }
          if($invoice->archive->invoiceStatus == 1 && $invoice->archive->isPaymentRecorded == 1){
            return "<a class='btn btn-success btn-xs mrs'>Payment Recorded</a>" ;
          }
      });

	    $data->editColumn('actions',function($invoice){
	          $html = "";
              $html .= "<a href='".route('invoices.week.review',[$invoice->weekNumbr,$invoice->booking->unitId])."' class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Review</a>";

              if($invoice->archive->invoiceStatus == 1){ $rcBtn = "success";$actn = route('invoices.week.invoice',[$invoice->weekNumbr,$invoice->booking->unitId]);}else{ $rcBtn = "warning";$actn = "javascript:void(0)";}

              $html .= "<a href='".$actn."' class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Invoice</a>";

              if($invoice->archive->isEmailSent == 1){ $rcBtn = "success"; }else{ $rcBtn = "warning"; $actn = "javascript:void(0)";}
              if($invoice->archive->invoiceStatus == 1){ $actn = route('invoices.weekly.email',[$invoice->weekNumbr,$invoice->booking->unitId]);}else{ $actn = "javascript:void(0)";}

              $html .= "<a href='".$actn."' class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Email</a>";


	          $class = "btn-warning";
	          if($invoice->archive->isPaymentRecorded ==1){ $class="btn-success";}
	          $html .= "<a href='".route('invoices.weekly.record-payment',[$invoice->weekNumbr,$invoice->booking->unitId])."' class='btn ".$class." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Record Invoice</a>";
	          return $html;
	    });
	    $tableData =$data->make(true);
	    return $tableData;
	}

	public function invoiceWeekReview($week,$unitId){
    $taxDefaultYear = TaxYear::where('default',1)->first();
    $archives = InvoiceArchive::pluck('invoiceId')->toArray();
    $invoices = Invoice::whereHas('booking.unit', function ($query) use ($unitId){
                $query->where('unitId',$unitId);
            })
            ->where('weekNumbr',$week)
            ->where('weekYear',$taxDefaultYear->taxYearId)
            ->verified()->get();

    $invoices = $this->processPaymentArray($invoices);
    // return $invoices;

    $invoiceIds = [];
    $rgnCount = $hcaCount = $shcaCount = $rgnSum = $hcaSum = $shcaSum = 0;
    for ($i=0; $i < count($invoices); $i++) {
      $clientPaymentsRate = ClientUnitPayment::where('clientUnitId',$invoices[$i]->booking->unit->clientUnitId)
                                ->where('staffCategoryId',$invoices[$i]->booking->category->categoryId)->where('rateType',1)->first();
      $shifType = 'day';
      switch (strtolower($invoices[$i]->booking->shift->name)) {
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


      if(in_array($invoices[$i]->invoiceId,$archives)){
        $invoices[$i]['archived'] = 1;
        $invoiceIds[] = $invoices[$i]->invoiceId;
      }else{
        $invoices[$i]['archived'] = 0;
      }

      if($invoices[$i]->booking->categoryId==1){   //RGN
        $rgnCount++;
        $rgnSum = $rgnSum+$invoices[$i]->lineTotal;
      }if($invoices[$i]->booking->categoryId==2){   //HCA
        $hcaCount++;
        $hcaSum = $hcaSum+$invoices[$i]->lineTotal;
      }if($invoices[$i]->booking->categoryId==3){   //SHCA
        $shcaCount++;
        $shcaSum = $shcaSum+$invoices[$i]->lineTotal;
      }
      $invoices[$i]->totalLine = ($invoices[$i]->hourlyRate * $invoices[$i]->timesheet->unitHours) + ($invoices[$i]->hourlyRate * $invoices[$i]->booking->unit->enic) + ($invoices[$i]->booking->transportAllowence);

      $invoices[$i]->booking->unit->hourlyRate = number_format($this->calculateHourlyRate($clientPaymentsRate,date('N',strtotime($invoices[$i]->booking->date)),$shifType),2);
    }
    // return $invoices;
      session()->put('invoices',$invoiceIds);
      return view('invoices.weekly.review_week',compact([
        'invoices','week','rgnCount','hcaCount','shcaCount','rgnSum','hcaSum','shcaSum','taxDefaultYear'
      ]));

    }

  public static function moveToNextWeek($invoiceId){
    $invoice = Invoice::with(['timesheet','booking'])->find($invoiceId);
    $currentWeek = $invoice->weekNumbr;
    $invoice->weekNumbr = $currentWeek+1;
    $invoice->save();
    return redirect(route('invoices.week.review',[$currentWeek,$invoice->booking->unitId]))->with('message','Succesfully Moved to Next Week');
  }

  public function revertToVA($invoiceId) {
    $invoice = Invoice::with(['timesheet','booking'])->find($invoiceId);
    $currentWeek = $invoice->weekNumbr;

    $invoice->status = 0;
    $invoice->save();
    $invoiceArchives = InvoiceArchive::where('invoiceId',$invoiceId)->delete();

    return redirect(route('invoices.weekly.list'))->with('message','Successfuly recorded payment');
  }

  public static function moveToArchives($invoiceId){
    $invoice = Invoice::find($invoiceId);
    //return $invoice->booking->unit->name;
    $currentWeek = $invoice->weekNumbr;
    InvoiceArchive::firstOrCreate([
      'invoiceId' =>$invoiceId
    ]);
    $number = InvoiceArchive::whereHas('invoice.booking', function ($query) use($invoice) {
              $query->where('unitId', $invoice->booking->unitId);
            })->groupBy('invoiceNumber')->whereNotNull('invoiceNumber')->get();

    if(count($number) ==0){  // No INVOICE for the UNIT
      $archive = InvoiceArchive::firstOrCreate([
        'invoiceId' =>$invoiceId
      ]);

      $defaultYear = TaxYear::where('default',1)->first();
      $lastDay = TaxWeek::where('taxYearId',$defaultYear->taxYearId)->where('weekNumber',$currentWeek)->orderBy('date','desc')->first();
      $archive->invoiceNumber = strtoupper(substr($invoice->booking->unit->name, 0, 3))."1";
      $archive->invoiceDate = $lastDay->date;
      $archive->save();
    }else{  // IF INVOICE Exist for that Staff
      $existInThisWeek = InvoiceArchive::whereHas('invoice', function ($query) use($invoice) {
          $query->where('weekNumbr', $invoice->weekNumbr);
          $query->where('weekYear', $invoice->weekYear);
      })->first();
      if(isset($existInThisWeek)){  // Already a INVOICE Generated for this year & Week
        $archive = InvoiceArchive::firstOrCreate([
          'invoiceId' =>$invoiceId
        ]);
        $archive->invoiceNumber = $existInThisWeek->invoiceNumber;
        $archive->save();

        InvoiceArchive::whereHas('invoice', function ($query) use($invoice,$archive) {
            $query->where('weekNumbr', $invoice->weekNumbr);
            $query->where('weekYear', $invoice->weekYear);
        })->update(['invoiceDate'=>date('Y-m-d')]);
      }else{
        $lastRa = InvoiceArchive::whereHas('invoice.booking', function ($query) use($invoice) {
          $query->where('unitId', $invoice->booking->unitId);
        })->groupBy('invoiceNumber')->whereNotNull('invoiceNumber')->latest()->first();

        $lastInvoiceNumber = substr($lastRa->invoiceNumber,3,3);
        $archive = InvoiceArchive::firstOrCreate([
          'invoiceId' =>$invoiceId,
          'invoiceNumber'  =>strtoupper(substr($invoice->booking->unit->name, 0, 3)).($lastInvoiceNumber+1),
          'invoiceDate'=>date('Y-m-d')
        ]);
      }
    }
    return redirect(route('invoices.week.review',[$currentWeek,$invoice->booking->unitId]))->with('message','Succesfully Moved to Archives');
  }

  public  function generateInvoice($week,$unitId){
		  $sessionId = session()->get('invoices');
		  if($sessionId){
    		InvoiceArchive::whereIn('invoiceId',$sessionId)->update([
    			'invoiceStatus'  =>1
    		]);
    		$invoices = Invoice::with(['timesheet'])
    				->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
    				->whereIn('invoice_archives.invoiceId',$sessionId)->where('invoice_archives.invoiceStatus',1)->get();
    		$from  = 0;
        $invoices = InvoiceWeeklyController::processPaymentArray($invoices);

        $invoices[0]['taxWeeks'] = TaxWeek::select('date')->where('taxYearId',$invoices[0]->weekYear)->where('weekNumber',$invoices[0]->weekNumbr)->orderBy('taxWeekId','asc')->get();
        $invoices[0]['periodStart'] = $invoices[0]->taxWeeks[0]->date;
        $invoices[0]['periodEnd'] = $invoices[0]->taxWeeks[count($invoices[0]['taxWeeks']) - 1]->date;
        $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['periodStart']))." - ". date('d-m-Y',strtotime($invoices[0]['periodEnd']));
        $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();

    		return view('invoices.weekly.generateInvoice',compact('invoices','from'));
		  }else{
    		$invoices = Invoice::with(['timesheet','archive'])
    					->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
    					->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
    					->Join('bookings','invoices.bookingId','bookings.bookingId')
    					->where('bookings.unitId',$unitId)
    					->where('invoices.weekNumbr',$week)
    					->where('invoice_archives.invoiceStatus',1)->get();
    		$from  = 1;
    		if(count($invoices) != 0){
          $invoices = InvoiceWeeklyController::processPaymentArray($invoices);
          $invoices[0]['taxWeeks'] = TaxWeek::select('date')->where('taxYearId',$invoices[0]->weekYear)->where('weekNumber',$invoices[0]->weekNumbr)->orderBy('taxWeekId','asc')->get();
          $invoices[0]['periodStart'] = $invoices[0]->taxWeeks[0]->date;
          $invoices[0]['periodEnd'] = $invoices[0]->taxWeeks[count($invoices[0]['taxWeeks']) - 1]->date;
          $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['periodStart']))." - ". date('d-m-Y',strtotime($invoices[0]['periodEnd']));
  			 return view('invoices.weekly.generateInvoice',compact('invoices','from'));
  		  }else{
          return redirect(route('invoices.week.review',[$week,$unitId]))->with('message','Error !!. generate RA before this action');
  		  }
  	 }
	}

  public static function generateDescColumn($invoice){
        $invoice['dist'] = $invoice->booking->distenceToWorkPlace;
        $invoice['Time'] = date('H:i',strtotime($invoice->timesheet->startTime))." to ". date('H:i',strtotime($invoice->timesheet->endTime)).", ";
        $invoice['paidHrs'] = "Paid Hours - ".$invoice->timesheet->staffHours.", ";
        $invoice['stdHrlyRate'] = $invoice->hourlyRate;
        if($invoice->ta != ''){
          $invoice['tas'] = " + "."£".$invoice->ta."(TA) ";
        } else {
          $invoice['tas'] = " + "."£0(TA) + ";
        }

        $invoice['sum'] = "Hourly rate agreed including TA - £(".$invoice['stdHrlyRate']." ".$invoice['tas']. "), ";
        if($invoice->otherPayAmount != '') {
          $invoice['otherPayAmounts'] = "£".$invoice->otherPayAmount. " for parking ticket, ";
        } else {
          $invoice['otherPayAmounts'] = '';
        }
        $invoice['timesheetNum'] = " Timesheet Number - " . $invoice->timesheetId;

        return $invoice['Time']. " ".$invoice['paidHrs']. " ".$invoice['sum']. " ".$invoice['otherPayAmounts']." ".$invoice['timesheetNum'] ;
  }

  public function invoiceEmailWeekly($week=null,$unitId=null){
    $sessionId = session()->get('invoices');

    if($sessionId){
      $invoices = Invoice::with(['timesheet'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->whereIn('invoice_archives.invoiceId',session()->get('invoices'))->get();
    }else{
      $invoices = Invoice::with(['timesheet'])
                  ->Join('invoice_archives','invoice_archives.paymentId','payments.paymentId')
                  ->Join('timesheets','payments.timesheetId','timesheets.timesheetId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$staffId)
                  ->where('paymentWeek',$week)
                  ->where('invoice_archives.invoiceStatus',1)->get();
    }

    $invoices = $this->processPaymentArray($invoices);
    $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();

    $invoices[0]['taxWeeks'] = TaxWeek::select('date')->where('taxYearId',$invoices[0]->weekYear)->where('weekNumber',$invoices[0]->weekNumbr)->orderBy('taxWeekId','asc')->get();
      $invoices[0]['periodStart'] = $invoices[0]->taxWeeks[0]->date;
      $invoices[0]['periodEnd'] = $invoices[0]->taxWeeks[count($invoices[0]['taxWeeks']) - 1]->date;
      $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['periodStart']))." - ". date('d-m-Y',strtotime($invoices[0]['periodEnd']));
    if(!empty($invoices)){
      Mail::to('vnair.sruthi@gmail.com')->cc('mcjobi@nursesgroup.co.uk')->cc('mcjobi@nursesgroup.co.uk')->cc('peter@nursesgroup.co.uk')->queue(new SendUnitInvoice($invoices));
      for ($i=0; $i < count($invoices); $i++) {
        $invoices[$i]->archive->isEmailSent = 1;
        $invoices[$i]->archive->save();
    }
    //return $invoices;

      return redirect(route('invoices.week.invoice',[$invoices[0]->weekNumbr,$invoices[0]->booking->unitId]))->with('message','Succesfully Sent Email');
    }else{
      return redirect(route('invoices.week.review',[$week,$unitId]))->with('message','Error sending email. generate RA before sending Email');
    }

  }
  public function invoiceExcelWeekly($week=null,$unitId=null){
    $invoices = Invoice::with(['timesheet','archive','booking','booking.shift','booking.unit','booking.category','booking.staff'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$unitId)
          ->where('invoices.weekNumbr',$week)
          ->where('invoice_archives.invoiceStatus',1)
          ->orderBy('bookings.date')
          ->orderBy('bookings.shiftId')
          ->orderBy('bookings.categoryId')->get();


    for ($i=0; $i < count($invoices); $i++) {
      $clientPaymentsRate = ClientUnitPayment::where('clientUnitId',$invoices[$i]->booking->unit->clientUnitId)
                            ->where('staffCategoryId',$invoices[$i]->booking->category->categoryId)->where('rateType',1)->first();
      $shiftType = $this->getShiftType($invoices[$i]->booking->shift->name);
      $invoices[$i]->totalLine = ($invoices[$i]->hourlyRate * $invoices[$i]->timesheet->unitHours) + ($invoices[$i]->hourlyRate * $invoices[$i]->booking->unit->enic) + ($invoices[$i]->booking->transportAllowence);

      $invoices[$i]->booking->unit->hourlyRate = number_format($this->calculateHourlyRate($clientPaymentsRate,date('N',strtotime($invoices[$i]->booking->date)),$shiftType),2);
    }
    $invoices = $this->processPaymentArray($invoices);
    $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();

    (new FastExcel($invoices))->download($invoices[0]->booking->unit->alias."-".time().".xlsx", function ($invoice) {
    if($invoice->booking->staffId != 0)
     if($invoice->enic != 0) {
      $exels = ['ID'  => $invoice->booking->bookingId,
            'Date'         => date('d-m-Y, D',strtotime($invoice->booking->date)),
            'Unit'         => $invoice->booking->unit->alias,
            'Description'  => $invoice->Desc,
            'ENIC'         => $invoice->enic,
            'Line Total'   => "£ ".$invoice->totalLine ];
      } else {
        $exels = ['ID'  => $invoice->booking->bookingId,
            'Date'         => date('d-m-Y, D',strtotime($invoice->booking->date)),
            'Unit'         => $invoice->booking->unit->alias,
            'Description'  => $invoice->Desc,
            'Line Total'   => "£ ".$invoice->totalLine ];
      }
        $staff = $invoice->booking->staff->forname." ".$invoice->booking->staff->surname;
        return $exels;

    });

  }

  public function recordPayment($week,$unitId){
    session()->put('rp',true);
    $admins = Admin::all();
    $invoices = Invoice::with(['timesheet','archive'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$unitId)
          ->where('invoices.weekNumbr',$week)
          ->where('invoice_archives.invoiceStatus',1)
          ->whereIn('invoice_archives.invoiceId',session()->get('invoices'))->get();

    $invoices = $this->processPaymentArray($invoices);

    $invoices[0]['taxWeeks'] = TaxWeek::select('date')->where('taxYearId',$invoices[0]->weekYear)->where('weekNumber',$invoices[0]->weekNumbr)->orderBy('taxWeekId','asc')->get();
      $invoices[0]['periodStart'] = $invoices[0]->taxWeeks[0]->date;
      $invoices[0]['periodEnd'] = $invoices[0]->taxWeeks[count($invoices[0]['taxWeeks']) - 1]->date;
      $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['periodStart']))." - ". date('d-m-Y',strtotime($invoices[0]['periodEnd']));
      $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();

      if(!empty($invoices)){
      return view('invoices.weekly.record_payment',compact('week','unitId','admins','invoices'));
    }else{
      return redirect(route('invoices.weekly.review',[$month,$unitId]))->with('message','Error !!. generate RA before this action');
    }
  }

  public function recordPaymentActionWeekly(Request $req){
    $invoices = Invoice::with(['timesheet','archive'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$req->unitId)
          ->where('invoices.weekNumbr',$req->week)
          ->where('invoice_archives.invoiceStatus',1)->get();

    foreach ($invoices as $invoice) {
      if($req->paymentDate){
        $invoice->archive->paymentDate = date('Y-m-d',strtotime($req->paymentDate));
      }
      $invoice->archive->bankId = $req->bankId;
      $invoice->archive->transactionNumber = $req->transactionNumber;
      $invoice->archive->handledBy = $req->handledBy;
      $invoice->archive->recordPaymentTime  = date('Y-m-d H:i:s');
      $invoice->archive->isPaymentRecorded = 1;
      $invoice->archive->save();
    }
    return redirect(route('invoices.weekly.list'))->with('message','Successfuly recorded payment');
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

  public function getShiftType($shift){
    $shifType = 'day';
    switch (strtolower($shift)) {
      case 'early':
        return $shifType = "day";
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
    return $shifType;
  }

  public function invoiceExcelInternalWeekly($week=null,$unitId=null){
    $invoices = Invoice::with(['timesheet','archive','booking','booking.shift','booking.unit','booking.category','booking.staff'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$unitId)
          ->where('invoices.weekNumbr',$week)
          ->where('invoice_archives.invoiceStatus',1)->get();

    for ($i=0; $i < count($invoices); $i++) {
      $clientPaymentsRate = ClientUnitPayment::where('clientUnitId',$invoices[$i]->booking->unit->clientUnitId)
                            ->where('staffCategoryId',$invoices[$i]->booking->category->categoryId)->where('rateType',1)->first();
      $shiftType = $this->getShiftType($invoices[$i]->booking->shift->name);
      $invoices[$i]->totalLine = ($invoices[$i]->hourlyRate * $invoices[$i]->timesheet->unitHours) + ($invoices[$i]->hourlyRate * $invoices[$i]->booking->unit->enic) + ($invoices[$i]->booking->transportAllowence);

      $invoices[$i]->booking->unit->hourlyRate = number_format($this->calculateHourlyRate($clientPaymentsRate,date('N',strtotime($invoices[$i]->booking->date)),$shiftType),2);
    }
    $invoices = $this->processPaymentArray($invoices);
    $invoices[0]['taxWeeks'] = TaxWeek::select('date')->where('taxYearId',$invoices[0]->weekYear)->where('weekNumber',$invoices[0]->weekNumbr)->orderBy('taxWeekId','asc')->get();
      $invoices[0]['periodStart'] = $invoices[0]->taxWeeks[0]->date;
      $invoices[0]['periodEnd'] = $invoices[0]->taxWeeks[count($invoices[0]['taxWeeks']) - 1]->date;
      $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['periodStart']))." - ". date('d-m-Y',strtotime($invoices[0]['periodEnd']));
      $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();

      (new FastExcel($invoices))->download($invoices[0]->booking->unit->alias."-".time().".xlsx", function ($invoice) {
      if($invoice->booking->staffId != 0)
         if($invoice->enic != 0) {
          $exels = ['Work ID'  => $invoice->booking->bookingId,
                'Staff'        => $invoice->booking->staff->forname." ".$invoice->booking->staff->surname,
                'Category'     => $invoice->booking->category->name,
                'Date'         => date('d-m-Y, D',strtotime($invoice->booking->date)),
                'Start Time'   => $invoice->timesheet->startTime,
                'End Time'     => $invoice->timesheet->endTime,
                'Hours Worked' => $invoice->timesheet->unitHours,
                'Hourly Rate'  => "£ ".$invoice->hourlyRate,
                'ENIC 13.8% of staff cost'=> "£ ".$invoice->enic,
                'Travel Expenses'=> $invoice->ta,
                'Line Total'   => "£ ".$invoice->totalLine ];
          } else {
            $exels = ['Work ID'  => $invoice->booking->bookingId,
                'Staff'        => $invoice->booking->staff->forname." ".$invoice->booking->staff->surname,
                'Category'     => $invoice->booking->category->name,
                'Date'         => date('d-m-Y, D',strtotime($invoice->booking->date)),
                'Start Time'   => $invoice->timesheet->startTime,
                'End Time'     => $invoice->timesheet->endTime,
                'Hours Worked' => $invoice->timesheet->unitHours,
                'Hourly Rate'  => "£ ".$invoice->hourlyRate,
                'Travel Expenses'=> $invoice->ta,
                'Line Total'   => "£ ".$invoice->totalLine ];
          }

        $staff = $invoice->booking->staff->forname." ".$invoice->booking->staff->surname;

    return $exels;
        });
    }

  public static function processPaymentArray($invoices){
    for ($i=0; $i < count($invoices); $i++) {
      $invoices[$i]['DateDay'] = date('d-m-Y, D',strtotime($invoices[$i]->booking->date));
      $invoices[$i]['Desc'] = InvoiceWeeklyController::generateDescColumn($invoices[$i]);
      $invoices[$i]['lineTotal'] = ($invoices[$i]['hourlyRate'] * $invoices[$i]->timesheet->unitHours) + ($invoices[$i]->hourlyRate * $invoices[$i]->booking->unit->enic) + ($invoices[$i]->booking->transportAllowence);

    }
    return $invoices;
  }

  public function invoicePdf($week=null,$unitId=null){
    $sessionId = session()->get('invoices');

    if($sessionId){
      $invoices = Invoice::with(['timesheet'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->whereIn('invoice_archives.invoiceId',session()->get('invoices'))->get();
    }else{
      $invoices = Invoice::with(['timesheet'])
                  ->Join('invoice_archives','invoice_archives.paymentId','payments.paymentId')
                  ->Join('timesheets','payments.timesheetId','timesheets.timesheetId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$staffId)
                  ->where('paymentWeek',$week)
                  ->where('invoice_archives.invoiceStatus',1)->get();
    }

    $invoices = $this->processPaymentArray($invoices);
    $invoices[0]['taxWeeks'] = TaxWeek::select('date')->where('taxYearId',$invoices[0]->weekYear)->where('weekNumber',$invoices[0]->weekNumbr)->orderBy('taxWeekId','asc')->get();
    $invoices[0]['periodStart'] = $invoices[0]->taxWeeks[0]->date;
    $invoices[0]['periodEnd'] = $invoices[0]->taxWeeks[count($invoices[0]['taxWeeks']) - 1]->date;
    $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['periodStart']))." - ". date('d-m-Y',strtotime($invoices[0]['periodEnd']));
    $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();
    // return $invoices;
    $pdfName = $invoices[0]->booking->staff->forname." ".$invoices[0]->booking->staff->surname." Month ".$invoices[0]->monthNumbr.".pdf" ;
    $pdf = PDF::loadView('invoices.weekly.pdf',compact('invoices'))->setPaper('A4', 'landscape');
    return $pdf->stream($pdfName);
  }
}
