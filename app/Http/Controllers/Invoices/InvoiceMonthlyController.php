<?php

namespace App\Http\Controllers\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceArchive;
use App\Models\Admin;
use App\Models\Client;
use App\Models\ClientUnit;
use App\Models\TaxYear;
use App\Models\TaxWeek;
use App\Models\Booking;
use App\Models\ClientUnitPayment;
use App\Mail\SendUnitInvoice;
use PDF;
use Auth;
use Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Mail;
use Carbon\Carbon;
use DateTime;
use App\Models\Shift;
use App\Models\Staff;
use App\Models\StaffCategory;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;

class InvoiceMonthlyController
{
    public function invoiceMonthlyList(Request $request){
      $units = ClientUnit::where('status',1)->orderBy('name','ASC')->get();
	    return view('invoices.monthly.list',compact('units'));
	}
	public function invoiceMonthlyData(Request $request){
	    $query = Invoice::Join('bookings','bookings.bookingId','invoices.bookingId')
              ->with(['timesheet','booking','booking.shift','booking.unit','booking.category','booking.staff','archive'])
              ->groupBy('bookings.unitId')
              ->groupBy('invoices.monthNumbr')
              ->select('invoices.*')
              ->where('invceFrqncy',2)->verified();

	    $data = Datatables::of($query);
	    $data->addIndexColumn();

      $data->filterColumn('booking.unit.alias', function($payment, $keyword) {
          $payment->where('bookings.unitId', $keyword);
      });
      $data->filterColumn('monthName', function($payment, $keyword) {
          $payment->where('monthNumbr', $keyword);
      });

	    $data->editColumn('monthName',function($invoice){
        $dateObj   = DateTime::createFromFormat('!m', $invoice->monthNumbr);
        $monthName = $dateObj->format('F'); // March
	        return $monthName;
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

      // $data->editColumn('log',function($invoice){
      //   return "<button class='btn btn-primary btn-xs mrs'>Shift Log</button>";
      // });
      // $data->editColumn('noOfActualBooking',function($invoice){
      //   $year
      //   $a_date = "2009-".$invoice->monthNumbr."-23";
      //   return date("Y-m-t", strtotime($a_date));
      //   // $bookings = Booking::where('staffId',$payment->booking->staffId)->whereIn('date',)->count();
      //   // return $bookings;
      // });
	    $data->editColumn('actions',function($invoice){
			  $html = "";
              $html .= "<a href='".route('invoices.month.review',[$invoice->monthNumbr,$invoice->booking->unitId])."' class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Review</a>";

              if($invoice->archive->invoiceStatus == 1){ $rcBtn = "success";$actn = route('invoices.month.invoice',[$invoice->monthNumbr,$invoice->booking->unitId]);}else{ $rcBtn = "warning";$actn = "javascript:void(0)";}

              $html .= "<a href='".$actn."' class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Invoice</a>";

              if($invoice->archive->isEmailSent == 1){ $rcBtn = "success"; }else{ $rcBtn = "warning"; $actn = "javascript:void(0)";}
              if($invoice->archive->invoiceStatus == 1){ $actn = route('invoices.monthly.email',[$invoice->monthNumbr,$invoice->booking->unitId]);}else{ $actn = "javascript:void(0)";}

              $html .= "<a href='".$actn."' class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Email</a>";


	          $class = "btn-warning";
	          if($invoice->archive->isPaymentRecorded ==1){ $class="btn-success";}
	          $html .= "<a href='".route('invoices.monthly.record-payment',[$invoice->monthNumbr,$invoice->booking->unitId])."' class='btn ".$class." verify-btn btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Record Payment</a>";
	          return $html;
	    });
	    $tableData =$data->make(true);
	    return $tableData;
	}

  public function invoiceMonthReview($month,$unitId){
    $taxDefaultYear = TaxYear::where('default',1)->first();
    $archives = InvoiceArchive::pluck('invoiceId')->toArray();
    $invoices = Invoice::whereHas('booking.unit', function ($query) use ($unitId){
                $query->where('unitId',$unitId);
            })
            ->where('monthNumbr',$month)
            ->where('weekYear',$taxDefaultYear->taxYearId)
            ->verified()->get();

    $invoices = $this->processPaymentArray($invoices);

    $invoiceIds = [];
    for ($i=0; $i < count($invoices); $i++) {
      $clientPaymentsRate = ClientUnitPayment::where('clientUnitId',$invoices[$i]->booking->unit->clientUnitId)
                                ->where('staffCategoryId',$invoices[$i]->booking->category->categoryId)->where('rateType',1)->first();
      $shiftType = $this->getShiftType($invoices[$i]->booking->shift->name);
      if(in_array($invoices[$i]->invoiceId,$archives)){
        $invoices[$i]['archived'] = 1;
        $invoiceIds[] = $invoices[$i]->invoiceId;
      }else{
        $invoices[$i]['archived'] = 0;
      }
      $invoices[$i]->totalLine = ($invoices[$i]->hourlyRate * $invoices[$i]->timesheet->unitHours) + ($invoices[$i]->hourlyRate * $invoices[$i]->booking->unit->enic) + ($invoices[$i]->booking->transportAllowence);
      $invoices[$i]->booking->unit->hourlyRate = number_format($this->calculateHourlyRate($clientPaymentsRate,date('N',strtotime($invoices[$i]->booking->date)),$shiftType),2);
    }
    $archived = $invoices->where('archived',1)->sum('totalLine');
    $dateObj   = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F'); // March
    session()->put('invoices',$invoiceIds);
    return view('invoices.monthly.review_month',compact(['invoices','month','monthName','taxDefaultYear']));
  }

  public static function moveToNextMonth($invoiceId){
    $invoice = Invoice::with(['timesheet','booking'])->find($invoiceId);
    $currentMonth = $invoice->monthNumbr;
    if($currentMonth < 13){
      $invoice->monthNumbr = $currentMonth+1;
      $invoice->save();
      return redirect(route('invoices.month.review',[$currentMonth,$invoice->booking->unitId]))->with('message','Succesfully Moved to Next Month');
    }else{
      return redirect(route('invoices.month.review',[$currentMonth,$invoice->booking->unitId]))->with('message','Ooops ! Reached Year End');
    }

  }

  public function revertToVA($invoiceId) {
    $invoice = Invoice::with(['timesheet','booking'])->find($invoiceId);
    $currentMonth = $invoice->monthNumbr;

    $invoice->status = 0;
    $invoice->save();

    InvoiceArchive::where('invoiceId',$invoiceId)->delete();

    return redirect(route('invoices.monthly.list'))->with('message','Successfuly recorded payment');

  }

  public static function moveToArchives($invoiceId){
    $invoice = Invoice::with(['timesheet','booking'])->find($invoiceId);
    $currentMonth = $invoice->monthNumbr;
    InvoiceArchive::firstOrCreate([
      'invoiceId' =>$invoiceId
    ]);

    $number = InvoiceArchive::whereHas('invoice.booking', function ($query) use($invoice) {
                $query->where('unitId', $invoice->booking->unitId);
              })->groupBy('invoiceNumber')->whereNotNull('invoiceNumber')->get();

    if(count($number) ==0){  // No Invoice for the Staff
      $archive = InvoiceArchive::firstOrCreate([
        'invoiceId' =>$invoiceId
      ]);
      $archive->invoiceNumber = strtoupper(substr($invoice->booking->unit->name, 0, 3))."1";
      $archive->invoiceDate = Carbon::now()->endOfMonth()->toDateString();
      $archive->save();
    }else{  // IF Invoice Exist for that Staff
      $existInThisWeek = InvoiceArchive::whereHas('invoice', function ($query) use($invoice) {
          $query->where('monthNumbr', $invoice->monthNumbr);
          $query->where('weekYear', $invoice->weekYear);
      })->first();
      if(isset($existInThisWeek)){  // Already a Invoice Generated for this year & Week
        $archive = InvoiceArchive::firstOrCreate([
          'invoiceId' =>$invoiceId
        ]);
        $archive->invoiceNumber = $existInThisWeek->invoiceNumber;
        $archive->save();

        InvoiceArchive::whereHas('invoice', function ($query) use($invoice,$archive) {
            $query->where('monthNumbr', $invoice->monthNumbr);
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
          'invoiceDate'=>Carbon::now()->endOfMonth()->toDateString(),
        ]);
      }
    }
    return redirect(route('invoices.month.review',[$currentMonth,$invoice->booking->unitId]))->with('message','Succesfully Moved to Archives');
  }

	public function generateInvoice($month,$unitId){
    $sessionId = session()->get('invoices');
		if($sessionId){
  		InvoiceArchive::whereIn('invoiceId',$sessionId)->update([
  			'invoiceStatus'  =>1
  		]);
      $invoices = Invoice::with(['timesheet'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->whereIn('invoice_archives.invoiceId',$sessionId)->where('invoice_archives.invoiceStatus',1)->get();
      $from  = 0;
      $invoices = InvoiceMonthlyController::processPaymentArray($invoices);
      $weekYearID =  TaxYear::where('taxYearId',$invoices[0]->weekYear)->first();
      $year = $weekYearID->taxYearFrom;
      $invoices[0]['startDate'] = $year."-".$month."-01";
      $invoices[0]['endDate'] =  date("Y-m-t", strtotime($invoices[0]['startDate']));

      $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['startDate']))  . " - " . date('d-m-Y',strtotime($invoices[0]['endDate'])) ;
      $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();
      // return $invoices;
  		return view('invoices.monthly.generateInvoice',compact('invoices','from','month'));
		}else{
  		$invoices = Invoice::with(['timesheet','archive','booking','booking.shift','booking.unit','booking.category','booking.staff'])
  					->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
  					->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
  					->Join('bookings','invoices.bookingId','bookings.bookingId')
  					->where('bookings.unitId',$unitId)
  					->where('invoices.weekNumbr',$month)
  					->where('invoice_archives.invoiceStatus',1)->get();
  		$from  = 1;
  		if(count($invoices) != 0){
        $invoices = InvoiceMonthlyController::processPaymentArray($invoices);
        $weekYearID =  TaxYear::where('taxYearId',$invoices[0]->weekYear)->first();
        $year = $weekYearID->taxYearFrom;
        $invoices[0]['startDate'] = $year."-".$month."-01";
        $invoices[0]['endDate'] =  date("Y-m-t", strtotime($invoices[0]['startDate']));
        $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();
        $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['startDate']))  . " - " . date('d-m-Y',strtotime($invoices[0]['endDate'])) ;
  			return view('invoices.monthly.generateInvoice',compact('invoices','from'));
  		}else{
  			return redirect(route('invoices.month.review',[$month,$unitId]))->with('message','Error !!. please generate Inovice before this action');
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
          $invoice['tas'] = "";
        }

        $invoice['sum'] = "Hourly rate agreed including TA - £(".$invoice['stdHrlyRate']." ".$invoice['tas'].", ";
        if($invoice->otherPayAmount != '') {
          $invoice['otherPayAmounts'] = "£".$invoice->otherPayAmount. " for parking ticket, ";
        } else {
          $invoice['otherPayAmounts'] = '';
        }
        $invoice['timesheetNum'] = " Timesheet Number - " . $invoice->timesheetId;

        return $invoice['Time']. " ".$invoice['paidHrs']. " ".$invoice['sum']. " ".$invoice['otherPayAmounts']." ".$invoice['timesheetNum'] ;
  }

  public static function invoiceEmailMonthly($month=null,$unitId=null){
    $sessionId = session()->get('invoices');
    if($sessionId){
      $invoices = Invoice::with(['timesheet'])
                ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
                ->where('invoice_archives.invoiceStatus',1)
                ->whereIn('invoice_archives.invoiceId',session()->get('invoices'))->get();
    }else{
      $invoices = Invoice::with(['timesheet','archive'])
              ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
              ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
              ->Join('bookings','invoices.bookingId','bookings.bookingId')
              ->where('bookings.unitId',$unitId)
              ->where('invoices.monthNumbr',$month)->get();

    }
    $invoices = InvoiceMonthlyController::processPaymentArray($invoices);
    $month = $invoices[0]->monthNumbr;
    $weekYearID =  TaxYear::where('taxYearId',$invoices[0]->weekYear)->first();
    $year = $weekYearID->taxYearFrom;
    $invoices[0]['startDate'] = $year."-".$month."-01";
    $invoices[0]['endDate'] =  date("Y-m-t", strtotime($invoices[0]['startDate']));

    $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['startDate']))  . " - " . date('d-m-Y',strtotime($invoices[0]['endDate'])) ;
    $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();

    $pdfName = $invoices[0]->booking->unit->name."_"."Year_".$invoices[0]->weekYear."_Month_".$invoices[0]->monthNumbr.".pdf" ;
    $pdf = PDF::loadView('invoices.monthly.pdf',compact('invoices'))->save(storage_path()."/app/public/invoices/".$pdfName)->setPaper('A4', 'landscape');

    if(!empty($invoices)){
      Mail::to('vnair.sruthi@gmail.com')->cc('jishadp369@gmail.com')->cc('mcjobi@nursesgroup.co.uk')->cc('peter@nursesgroup.co.uk')->queue(new SendUnitInvoice($invoices));
      for ($i=0; $i < count($invoices); $i++) {
        $invoices[$i]->archive->isEmailSent = 1;
        $invoices[$i]->archive->save();
      }
      return redirect(route('invoices.month.invoice',[$invoices[0]->monthNumbr,$invoices[0]->booking->unitId]))->with('message','Succesfully Sent Email');
    }else{
      return redirect(route('invoices.month.review',[$month,$unitId]))->with('message','Error sending email. generate RA before sending Email');
    }
  }

  public function invoiceExcelMonthly($month=null,$unitId=null){
    $invoices = Invoice::with(['timesheet','archive','booking','booking.shift','booking.unit','booking.category','booking.staff'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$unitId)
          ->where('invoices.monthNumbr',$month)
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
      $staff = $invoice->booking->staff->forname." ".$invoice->booking->staff->surname;

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

  public function recordPayment($month,$unitId){
    session()->put('rp',true);
    $admins = Admin::all();
    $invoices = Invoice::with(['timesheet','archive'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$unitId)
          ->where('invoices.monthNumbr',$month)
          ->where('invoice_archives.invoiceStatus',1)->get();

      $invoices = $this->processPaymentArray($invoices);
      $weekYearID =  TaxYear::where('taxYearId',$invoices[0]->weekYear)->first();
      $year = $weekYearID->taxYearFrom;
      $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();
      $invoices[0]['startDate'] = $year."-".$month."-01";
      $invoices[0]['endDate'] =  date("Y-m-t", strtotime($invoices[0]['startDate']));

      $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['startDate']))  . " - " . date('d-m-Y',strtotime($invoices[0]['endDate'])) ;
      if(!empty($invoices)){
        return view('invoices.monthly.record_payment',compact('month','unitId','admins','invoices'));
      }else{
        return redirect(route('invoices.month.review',[$month,$unitId]))->with('message','Error !!. generate RA before this action');
      }

    // return view('invoices.monthly.record_payment',compact('month','unitId','admins','invoice'));
  }

  public function recordPaymentActionMonthly(Request $req){
    $invoices = Invoice::with(['timesheet','archive'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$req->unitId)
          ->where('invoices.monthNumbr',$req->month)
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
    return redirect(route('invoices.monthly.list'))->with('message','Successfuly recorded payment');
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

  public function invoiceExcelInternalMonthly($month=null,$unitId=null){
    $invoices = Invoice::with(['timesheet','archive','booking','booking.shift','booking.unit','booking.category','booking.staff'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->Join('timesheets','invoices.timesheetId','timesheets.timesheetId')
          ->Join('bookings','invoices.bookingId','bookings.bookingId')
          ->where('bookings.unitId',$unitId)
          ->where('invoices.monthNumbr',$month)
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
    $weekYearID =  TaxYear::where('taxYearId',$invoices[0]->weekYear)->first();
    $year = $weekYearID->taxYearFrom;
    $invoices[0]['startDate'] = $year."-".$month."-01";
    $invoices[0]['endDate'] =  date("Y-m-t", strtotime($invoices[0]['startDate']));

    $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['startDate']))  . " - " . date('d-m-Y',strtotime($invoices[0]['endDate'])) ;

    (new FastExcel($invoices))->download($invoices[0]->booking->unit->alias."-".time().".xlsx", function ($invoice) {
      if($invoice->booking->staffId != 0)
        if($invoice->enic != 0) {
          $exels = ['Work ID'  => $invoice->booking->bookingId,
                'Unit'     => $invoice->booking->unit->alias,
                'Staff'        => $invoice->booking->staff->forname." ".$invoice->booking->staff->surname,
                'Category'     => $invoice->booking->category->name,
                'Date'         => date('d-m-Y, D',strtotime($invoice->booking->date)),
                'Start Time'   => date('H:i',strtotime($invoice->timesheet->startTime)),
                'End Time'     => date('H:i',strtotime($invoice->timesheet->endTime)),
                'Hours Worked' => number_format($invoice->timesheet->unitHours,2),
                'Hourly Rate'  => "£ ".number_format($invoice->hourlyRate,2),
                'ENIC 13.8% of staff cost'=> "£ ".number_format($invoice->enic,2),
                'Travel Expenses'=> "£ ".number_format($invoice->ta,2),
                'Line Total'   => "£ ".number_format($invoice->totalLine,2) ];
          } else {
            $exels = ['Work ID'  => $invoice->booking->bookingId,'Unit'     => $invoice->booking->unit->alias,
                'Staff'        => $invoice->booking->staff->forname." ".$invoice->booking->staff->surname,
                'Category'     => $invoice->booking->category->name,
                'Date'         => date('d-m-Y, D',strtotime($invoice->booking->date)),
                'Start Time'   => date('H:i',strtotime($invoice->timesheet->startTime)),
                'End Time'     => date('H:i',strtotime($invoice->timesheet->endTime)),
                'Hours Worked' => number_format($invoice->timesheet->unitHours,2),
                'Hourly Rate'  => "£ ".number_format($invoice->hourlyRate,2),
                'Travel Expenses'=> "£ ".number_format($invoice->ta,2),
                'Line Total'   => "£ ".number_format($invoice->totalLine,2) ];
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

  public function invoicePdf($month=null,$unitId=null){
    $sessionId = session()->get('invoices');

    if($sessionId){
      $invoices = Invoice::with(['timesheet'])
          ->Join('invoice_archives','invoice_archives.invoiceId','invoices.invoiceId')
          ->where('invoice_archives.invoiceStatus',1)
          ->whereIn('invoice_archives.invoiceId',session()->get('invoices'))->get();
    }else{
      $invoices = Invoice::with(['timesheet'])
                  ->Join('invoice_archives','invoice_archives.paymentId','payments.paymentId')
                  ->Join('timesheets','payments.timesheetId','timesheets.timesheetId')
                  ->Join('bookings','payments.bookingId','bookings.bookingId')
                  ->where('bookings.staffId',$staffId)
                  ->where('paymentmonth',$month)
                  ->where('invoice_archives.invoiceStatus',1)->get();
    }

    $invoices = $this->processPaymentArray($invoices);
    $invoices[0]['clients'] = Client::where('clientId',$invoices[0]->booking->unit->clientId)->first();

    $month = $invoices[0]->monthNumbr;
    $weekYearID =  TaxYear::where('taxYearId',$invoices[0]->weekYear)->first();
    $year = $weekYearID->taxYearFrom;
    $invoices[0]['startDate'] = $year."-".$month."-01";
    $invoices[0]['endDate'] =  date("Y-m-t", strtotime($invoices[0]['startDate']));

    $invoices[0]['periodDates'] = date('d-m-Y',strtotime($invoices[0]['startDate']))  . " - " . date('d-m-Y',strtotime($invoices[0]['endDate'])) ;
    $pdfName = $invoices[0]->booking->staff->forname." ".$invoices[0]->booking->staff->surname." Month ".$invoices[0]->monthNumbr.".pdf" ;
    $pdf = PDF::loadView('invoices.monthly.pdf',compact('invoices'))->setPaper('A4', 'landscape');
    return $pdf->stream($pdfName);
  }
}
