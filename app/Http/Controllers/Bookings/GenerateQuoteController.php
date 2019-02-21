<?php

namespace App\Http\Controllers\Bookings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClientUnitPayment;
use App\Models\ClientUnitContact;
use App\Models\ClientUnitSchedule;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Booking\GenerateQuote;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use App\Events\GeneratePayeeReportEvent;
use App\Events\GenerateDailyReport;
use App\Events\GenerateFurtherReport;
use App\Mail\SendUnitReport;
use App\Mail\SendPlainSMS;
use Auth;
use Mail;

class GenerateQuoteController
{
    public function quotePreview(Request $request){
        return view('bookings.generateQuote');
    }

    public function quotePreviewData(){
      $day = date('N');
      session()->put('checkBooking',request('ids'));
      $bookingList = Booking::with(['unit','category'])
                    ->where('unitStatus',4)
                    ->whereIn('bookingId',request('ids'))
                    ->orderBy('date','ASC')
                    ->orderBy('shiftId','ASC')
                    ->get();

        for ($i=0; $i < count($bookingList); $i++) {
            $times = ClientUnitSchedule::where('clientUnitId',$bookingList[$i]->unit->clientUnitId)
                                    ->where('staffCategoryId',$bookingList[$i]->category->categoryId)
                                    ->where('shiftId',$bookingList[$i]->shiftId)
                                    ->first();
            $paymentColumn = $this->getPaymentColumn($day,$bookingList[$i]->shiftId);
            $payment = ClientUnitPayment::where('clientUnitId',$bookingList[$i]->unit->clientUnitId)
                                    ->select($paymentColumn)
                                    ->where('staffCategoryId',$bookingList[$i]->category->categoryId)
                                    ->first();
            $ta = ClientUnitPayment::where('clientUnitId',$bookingList[$i]->unit->clientUnitId)
                                    ->where('staffCategoryId',1)
                                    ->select('taPerMile','taNoOfMiles')->first();

            //return $ta;
            if(!empty($times) && !empty($times) && $ta){
                $bookingList[$i]->startTime = $times->startTime;
                $bookingList[$i]->endTime = $times->endTime;
                $bookingList[$i]->totalHoursUnit = $times->totalHoursUnit;
                $bookingList[$i]->hourlyRate = $payment->$paymentColumn;
                $bookingList[$i]->ta = $ta->taPerMile*$ta->taNoOfMiles;
                $bookingList[$i]->lineTotal = ($times->totalHoursUnit*$payment->$paymentColumn)+$ta->taPerMile*$ta->taNoOfMiles;
            }else{
                $bookingList[$i]->startTime = '';
                $bookingList[$i]->endTime = '';
                $bookingList[$i]->totalHoursUnit = '';
                $bookingList[$i]->hourlyRate = '';
                $bookingList[$i]->ta = '';
                $bookingList[$i]->lineTotal = '';
            }
        }
        return view('bookings.quoteContent',compact("bookingList"));
    }

    public function getPaymentColumn($day,$shiftId){
      switch ($shiftId) {
        case 1:
          $shift = 'day';
          break;
        case 2:
          $shift = 'day';
          break;
        case 3:
            $shift = 'day';
            break;
        case 4:
            $shift = 'night';
            break;
      }

      switch ($day) {
        case 1:
          $column = $shift."Monday";
          break;
        case 2:
          $column = $shift."Tuesday";
          break;
        case 3:
          $column = $shift."Wednesday";
          break;
        case 4:
          $column = $shift."Thursday";
          break;
        case 5:
          $column = $shift."Friday";
          break;
        case 6:
          $column = $shift."Saturday";
          break;
        case 7:
          $column = $shift."Sunday";
          break;
      }

      return $column;
    }

    public function generateQuote(Request $request){

      $ids= session()->get('checkBooking');

      $bookingList = Booking::with(['unit','category'])->whereIn('bookingId',$ids)->orderBy('date','ASC')->orderBy('shiftId','ASC')->get();
      $day = date('N');

      for ($i=0; $i < count($bookingList); $i++) {
          $times = ClientUnitSchedule::where('clientUnitId',$bookingList[$i]->unit->clientUnitId)
                                  ->where('staffCategoryId',$bookingList[$i]->category->categoryId)
                                  ->where('shiftId',$bookingList[$i]->shiftId)
                                  ->first();
          $paymentColumn = $this->getPaymentColumn($day,$bookingList[$i]->shiftId);
          $payment = ClientUnitPayment::where('clientUnitId',$bookingList[$i]->unit->clientUnitId)
                                  ->select($paymentColumn)
                                  ->where('staffCategoryId',$bookingList[$i]->category->categoryId)
                                  ->first();
          $ta = ClientUnitPayment::where('clientUnitId',$bookingList[$i]->unit->clientUnitId)
                                  ->where('staffCategoryId',1)
                                  ->select('taPerMile','taNoOfMiles')->first();

          //return $ta;
          if(!empty($times) && !empty($times) && $ta){
              $bookingList[$i]->startTime = $times->startTime;
              $bookingList[$i]->endTime = $times->endTime;
              $bookingList[$i]->totalHoursUnit = $times->totalHoursUnit;
              $bookingList[$i]->hourlyRate = $payment->$paymentColumn;
              $bookingList[$i]->ta = $ta->taPerMile*$ta->taNoOfMiles;
              $bookingList[$i]->lineTotal = ($times->totalHoursUnit*$payment->$paymentColumn)+$ta->taPerMile*$ta->taNoOfMiles;
          }else{
              $bookingList[$i]->startTime = '';
              $bookingList[$i]->endTime = '';
              $bookingList[$i]->totalHoursUnit = '';
              $bookingList[$i]->hourlyRate = '';
              $bookingList[$i]->ta = '';
              $bookingList[$i]->lineTotal = '';
          }
      }

      (new FastExcel($bookingList))->download($bookingList[0]->unit->alias."-".time().".xlsx", function ($booking) {
			$staff = "Searching Now";
			if($booking->staffId != 0)
        $staff = $booking->staff->forname." ".$booking->staff->surname;

  			switch($booking->unitStatus){
  				case 1: $unitStatus =  'Temporary'; break;
  				case 2: $unitStatus =  'Cancelled';  break;
  				case 3: $unitStatus =  'Unable to Cover';  break;
  				case 4: $unitStatus =  'Confirmed';  break;
  				case 5: $unitStatus =  'Booking Error';  break;
  				default:  $unitStatus = "";
  			}

        if(Auth::guard('admin')->user()->type==1){
          return [
            'ID'           => $booking->bookingId,
            'Unit'         => $booking->unit->alias,
            'Date'         => date('d-M-Y, D',strtotime($booking->date)),
            'Shift'        => $booking->shift->name,
            'Category'     => $booking->category->name,
            'Shift Status' => $unitStatus,
            'Staff'        => $staff,
            'Start Time'   => date('H:i',strtotime($booking->startTime)) ,
            'End Time'     => date('H:i',strtotime($booking->endTime)),
            'Hours Worked' => $booking->totalHoursUnit ,
            'Hourly Rate'  => "£ ".$booking->hourlyRate ,
            'Transport'    => "£ ".number_format((float)$booking->ta,2) ,
            'Line Total'    => "£ ".$booking->lineTotal ,
          ];
        }else{
          return [
            'ID'           => $booking->bookingId,
            'Unit'         => $booking->unit->alias,
            'Date'         => date('d-M-Y, D',strtotime($booking->date)),
            'Shift'        => $booking->shift->name,
            'Category'     => $booking->category->name,
            'Shift Status' => $unitStatus,
            'Staff'        => $staff,
          ];
        }
        });
    }

    public function downloadPayeeReport(){
      $ids= session()->get('checkBooking');
      event(new GeneratePayeeReportEvent($ids));
    }

    public function downloadDailyReport(){
      $ids= session()->get('checkBooking');
      event(new GenerateDailyReport($ids));
    }

    public function downloadFurtherReport(){
      $ids= session()->get('checkBooking');
      event(new GenerateFurtherReport($ids));
    }

    public function fetchUnitReport(){
      return view('bookings.reports.unitReport');
    }
    public function fetchUnitReportData(){
      session()->put('checkBooking',request('ids'));
      $bookings = Booking::with(['unit','category'])->whereIn('bookingId',request('ids'))->orderBy('date','ASC')->orderBy('shiftId','ASC')->get();
      return ['html'=>view('bookings.reports.singleRaw',compact('bookings'))->render(),'unitAlias'=>$bookings[0]->unit->alias];
    }

    public function sendUnitReport(){
      $ids= session()->get('checkBooking');
      $bookings = Booking::with(['unit','category'])->whereIn('bookingId',$ids)->orderBy('date','ASC')->orderBy('shiftId','ASC')->get();
      $unitContact = ClientUnitContact::with('unit')->where('clientUnitId',$bookings[0]->unitId)->first();

      Mail::to('office@nursesgroup.co.uk')->bcc('info@codebreaze.com')->queue(new SendUnitReport($bookings,$unitContact));
      return redirect(route('booking.current'))->with('message','Succesfully sent mail to Unit');
    }

    // FS SMS
    public function generateFsSmS(){
      return view('bookings.reports.fsSms');
    }

    public function FsSmSData(){
      session()->put('checkBooking',request('ids'));
      $bookings = Booking::with(['unit','category','staff'])->whereIn('bookingId',request('ids'))->orderBy('date','ASC')->orderBy('shiftId','ASC')->get();
      $head = $bookings[0]->staff->forname." ".$bookings[0]->staff->surname." ".$bookings[0]->staff->mobile;
      $sms = $this->generateSMSHelper($bookings);
      return ['heading'=>$head,'sms'=>$sms];
    }

    public function generateSMSHelper($bookings){
      $sms = "Dear ".$bookings[0]->staff->forname." ".$bookings[0]->staff->surname.",";
      for ($i=0; $i < count($bookings); $i++) {
        if($i==0){
          $sms.="\nThe following shifts are booked for you,\n";
        }
        $sms .="     ".date('d-m-Y D',strtotime($bookings[$i]->date))." - ";
        $sms .=$bookings[$i]->shift->name." - ";
        if($i != count($bookings)-1){
          $sms .=$bookings[$i]->unit->alias.",";
        }else{
          $sms .=$bookings[$i]->unit->alias;
        }
        if($i==count($bookings)-1){
          $sms.=".";
        }
      }
      $sms .="Thank you,";
      $sms .="Nurses Group.";
      return $sms;
    }

    public function sendFsSms(){
      $ids= session()->get('checkBooking');
      $bookings = Booking::with(['unit','category'])->whereIn('bookingId',$ids)
          ->orderBy('date','ASC')->orderBy('shiftId','ASC')->get();
      $sms = $this->generateSMSHelper($bookings);
      $emailAddress = "0044".substr($bookings[0]->staff->mobile, 1).'@mail.mightytext.net';
      $emailAddress = str_replace(" ","",$emailAddress);
      Mail::to($emailAddress)->queue(new SendPlainSMS($sms));
      return redirect(route('booking.current'))->with('message','Succesfully sent sms to Staff');
    }
    // FS SMS
}
