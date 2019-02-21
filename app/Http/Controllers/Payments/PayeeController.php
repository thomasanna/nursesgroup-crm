<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Payments\Selfie;
use App\Http\Controllers\Payments\PaymentHelper;
use App\Http\Controllers\Payments\Payee\Payee;
use App\Http\Controllers\Payments\Payee\PayeeWeek;
use App\Http\Controllers\Payments\Payee\PayeeArchive;

use App\Models\Timesheet;
use App\Models\ClientUnitSchedule;
use App\Models\Payment;
use App\Models\PaymentArchive;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\Shift;
use App\Models\Driver;

use App\Models\TaxYear;
use App\Models\TaxWeek;
use Auth;

class PayeeController
{
    public function payeeList(Request $request){
      $taxYears = TaxYear::all();
      $deafultYear = $taxYears->where('default', 1)->values()->all();
      $taxWeeks = TaxWeek::where('taxYearId',$deafultYear[0]->taxYearId)->groupBy('weekNumber')->get();
      $shifts = Shift::all();
      $thisWeek = TaxWeek::where('taxYearId',$deafultYear[0]->taxYearId)->whereDate('date',date('Y-m-d'))->first();
      $staffs = Staff::where('status',1)->orderBy('forname','ASC')->get();
      $staffCategory = StaffCategory::all();
      return view('payments.payee',compact('taxYears','taxWeeks','thisWeek','staffs','staffCategory','shifts'));
    }
    public function payeeData(Request $request){
      return Payee::data($request,1);
    }

    public function generateRAPayee($week=null,$staffId=null){
      return PayeeWeek::generateRAPayee($week,$staffId);
    }
    public function raEmailPayee($week=null,$staffId=null){
      return PayeeWeek::raEmailPayee($week,$staffId);
    }
    public function rAPdfPayee($week=null,$staffId=null){
      return PayeeWeek::rAPdfPayee($week,$staffId);
    }
    public function raRecordPayment($week=null,$staffId=null){
      return PayeeWeek::raRecordPayment($week,$staffId);
    }
    public function raRecordPaymentAction(Request $request){
      return PayeeWeek::raRecordPaymentAction($request);
    }
    public function getSinglePayment(Request $request){
      $payment = Payment::with([
        'timesheet','booking','booking.shift',
        'timesheet.checkin','timesheet.verify',
        'booking.unit','booking.category','booking.staff'
        ])->find($request->get('paymentId'));
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
      $payment->addiotnalStaff = PaymentHelper::additionalStaff($payment->booking);
      $payment->booking->date = date('Y-m-d',strtotime($payment->booking->date));
      $payment->booking->day = date('D',strtotime($payment->booking->date));
      $payment->timesheet->startTime = date('H:i',strtotime($payment->timesheet->startTime));
      $payment->timesheet->endTime = date('H:i',strtotime($payment->timesheet->endTime));
      $payment->booking->staff->hourlyRate = number_format(PaymentHelper::calculateHourlyRate($payment->booking,$shifType),2);
      $payment->booking->distenceToWorkPlace = number_format($payment->booking->distenceToWorkPlace,2);

      if($payment->saved==0){  // Not Saved
        if($payment->booking->modeOfTransport =='1'){ // IF Self
          $distanceToUnit = $payment->booking->distenceToWorkPlace;
          if (0 < $distanceToUnit && $distanceToUnit <= 15.0) {
            $defaultTa = "1.00";
          } else if(15.1 <= $distanceToUnit && $distanceToUnit <= 30.0) {
            $defaultTa = "2.00";
          } else if(30.1 <= $distanceToUnit && $distanceToUnit <= 50.0) {
            $defaultTa = "3.00";
          }else{
            $defaultTa = "4.00";
          }
          $payment->booking->transportAllowence = number_format($defaultTa,2);
        }else{
          $payment->booking->transportAllowence = number_format($payment->booking->transportAllowence,2);
        }
        $payment->booking->extraTA = number_format(($payment->addiotnalStaff['count']*0.50),2);
        $payment->booking->bonus = number_format($payment->booking->bonus,2);
        if($payment->booking->bonus == 0){ $payment->booking->bonusAuthorizedBy = "NA"; }
        else{ $payment->booking->bonusAuthorizedBy = PaymentHelper::getAdminName($payment->booking->bonusAuthorizedBy);}
        if($payment->otherPay==NULL) $payment->otherPay = "N/A"; else $payment->otherPay = $payment->otherPay;
        $payment->booking->otherPayAmount = number_format($payment->otherPayAmount,2);
        $deafultYear = TaxYear::where('default', 1)->first();
        $thisWeek = TaxWeek::where('taxYearId',$deafultYear->taxYearId)->whereDate('date',$payment->booking->date)->first();
        $payment->paymentWeek = $thisWeek->weekNumber;
        
      }else{
        $payment->paymentWeek = $payment->paymentWeek;
        $payment->booking->transportAllowence = number_format($payment->ta,2);
        $payment->booking->extraTA = number_format($payment->extraTa,2);
        $payment->booking->bonus = number_format($payment->bonus,2);
        if($payment->otherPay==NULL) $payment->otherPay = "N/A"; else $payment->otherPay = $payment->otherPay;
        $payment->booking->otherPayAmount = number_format($payment->otherPayAmount,2);
        if($payment->booking->bonus == 0){ $payment->booking->bonusAuthorizedBy = "NA"; }
        else{ $payment->booking->bonusAuthorizedBy = PaymentHelper::getAdminName($payment->booking->bonusAuthorizedBy);}
        $payment->booking->staff->historical_rate = PaymentHelper::getHistoricalRate($payment->booking->staffId);
      }

      switch ($payment->booking->modeOfTransport) {

        case 1:  // Self
          $transportMode = "";
          if($payment->booking->outBoundDriverType == null){
            $transportMode = "Out - SELF | ";
          }
          else if($payment->booking->outBoundDriverType == 1) {
            $getDriver = Driver::find($payment->booking->outBoundDriverId);
            $transportMode = 'Out - Private Driver ('.$getDriver->forname.") | ";
          }else if ($payment->booking->outBoundDriverType == 2) {
            $transportMode = 'Out - Possible Lift | ';
          }else if ($payment->booking->outBoundDriverType == 2) {
            $transportMode = 'Out - Public Transport | ';
          }

          if($payment->booking->inBoundDriverType == null){
            $transportMode .= "In - SELF";
          }
          else if($payment->booking->inBoundDriverType == 1) {
            $transportMode .= 'In - Private Driver ';
            $getDriver = Driver::find($payment->booking->inBoundDriverId);
            if($getDriver) $transportMode .= '('.$getDriver->forname.")";

          } else if ($payment->booking->inBoundDriverType == 2) {
            $transportMode .= 'In - Possible Lift';
          } else if ($payment->booking->inBoundDriverType == 2) {
            $transportMode .= 'In - Public Transport';
          }

          $payment->booking->transportMode = $transportMode;
          break;

        case 2:  // Transport Required
          $transportMode = "";
          if($payment->booking->outBoundDriverType == 1) {
            $getDriver = Driver::find($payment->booking->outBoundDriverId);
            $transportMode = 'Out - Private Driver ('.$getDriver->forname.") | ";
          } else if ($payment->booking->outBoundDriverType == 2) {
            $transportMode = 'Out - Possible Lift | ';
          } else if ($payment->booking->outBoundDriverType == 2) {
            $transportMode = 'Out - Public Transport | ';
          }

          if($payment->booking->inBoundDriverType == 1) {
            $getDriver = Driver::find($payment->booking->inBoundDriverId);
            $transportMode .= 'In - Private Driver ('.$getDriver->forname.")";
          } else if ($payment->booking->inBoundDriverType == 2) {
            $transportMode .= 'In - Possible Lift';
          } else if ($payment->booking->inBoundDriverType == 2) {
            $transportMode .= 'In - Public Transport';
          }

          $payment->booking->transportMode = $transportMode;

          break;
      }

      $payment->scheduleStaffHours = ClientUnitSchedule::where('clientUnitId',$payment->booking->unitId)
                                      ->where('staffCategoryId',$payment->booking->categoryId)
                                      ->where('shiftId',$payment->booking->shiftId)
                                      ->first();

      return ['status'=>true,"data"=>$payment];
    }
    
    public function varifyPayeePayment(Request $request){
      return Payee::varifyPayeePayment($request);
    }
    public function saveApprovePayeePayment(Request $request){
      return Payee::saveApprovePayeePayment($request);
    }

    public function approvePayeePayment(Request $request){
      return Payee::approvePayeePayment($request);
    }

    public function payeeWeekList(){
      $staffs = Staff::where('status',1)->orderBy('forname','ASC')->get();
      $deafultYear = TaxYear::where('default', 1)->first();
      $taxWeeks = TaxWeek::where('taxYearId',$deafultYear->taxYearId)->groupBy('weekNumber')->get();
      $taxCurrentWeek = TaxWeek::where('taxYearId',$deafultYear->taxYearId)->groupBy('weekNumber')->where('date',date('Y-m-d'))->first();
      return view('payments.payee.weeks',compact(['staffs','taxWeeks','taxCurrentWeek']));
    }

    public function payeeWeekData(Request $request){
      return PayeeWeek::data($request,1);
    }

    public function payeeWeekReview($week,$staffId){
      return PayeeWeek::payeeWeekReview($week,$staffId);
    }

    public function moveToNextWeek($paymentId){
      return PayeeWeek::moveToNextWeek($paymentId);
    }

    public function moveToArchives($paymentId){
      return PayeeWeek::moveToArchives($paymentId);
    }
    public function revertToVA($paymentId) {
      return PayeeWeek::revertToVA($paymentId);
    }

    public function archives(){
      $staffs = Staff::where('status',1)->where('paymentMode',2)->orderBy('forname','ASC')->get();
      return view('payments.payee.archives.list',compact('staffs'));
    }
    public function archivesData(Request $request){
      return PayeeArchive::data();
    }
    public function archivesAll($staffId){
      return PayeeArchive::archivesAll($staffId);
    }
    public function archivesAllWeeks(Request $request){
      return PayeeArchive::archivesAllWeeks($request);
    }
    public function archivesAllWeeksDetails(Request $request){
      return PayeeArchive::archivesAllWeeksDetails($request);
    }
    public function weekReportPayee($weekNum) {
      return PayeeWeek::weekReportPayee($weekNum);
    }
    public function weekPayeeBrightpay($weekNum) {
      return PayeeWeek::weekPayeeBrightpay($weekNum);
    }
    public function brightPayDetails(Request $request) {
      return PayeeWeek::brightPayDetails($request);
    }


}
