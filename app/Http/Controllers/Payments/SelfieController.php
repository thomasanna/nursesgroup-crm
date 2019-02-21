<?php
namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Payments\Selfie\Selfie;
use App\Http\Controllers\Payments\Selfie\SelfieWeek;
use App\Http\Controllers\Payments\Selfie\SelfieArchive;
use App\Models\Timesheet;
use App\Models\Payment;
use App\Models\PaymentArchive;

use App\Models\Shift;
use App\Models\TaxYear;
use App\Models\TaxWeek;
use App\Models\Staff;
use App\Models\StaffCategory;
use Auth;

class SelfieController
{
  public function selfieList(Request $request){
    $taxYears = TaxYear::all();
    $deafultYear = $taxYears->where('default', 1)->values()->all();
    $taxWeeks = TaxWeek::where('taxYearId',$deafultYear[0]->taxYearId)->groupBy('weekNumber')->get();
    $thisWeek = TaxWeek::where('taxYearId',$deafultYear[0]->taxYearId)->whereDate('date',date('Y-m-d'))->first();
    $shifts = Shift::all();
    $staffs = Staff::where('status',1)->orderBy('forname','ASC')->get();
    $staffCategory = StaffCategory::all();
    return view('payments.selfie',compact('taxYears','taxWeeks','thisWeek','staffs','staffCategory','shifts'));
  }
  public function selfieData(Request $request){
    return Selfie::data($request,1);
  }

  public function generateRASelfie($week=null,$staffId=null){
    return SelfieWeek::generateRASelfie($week,$staffId);
  }
  public function raEmailSelfie($week=null,$staffId=null){
    return SelfieWeek::raEmailSelfie($week,$staffId);
  }
  public function rAPdfSelfie($week=null,$staffId=null){
    return SelfieWeek::rAPdfSelfie($week,$staffId);
  }
  public function raRecordPayment($week=null,$staffId=null){
    return SelfieWeek::raRecordPayment($week,$staffId);
  }
  public function raRecordPaymentAction(Request $request){
    return SelfieWeek::raRecordPaymentAction($request);
  }
  public function varifySelfiePayment(Request $request){
    return Selfie::varifySelfiePayment($request);
  }
  public function saveApproveSelfiePayment(Request $request){
    return Selfie::saveApproveSelfiePayment($request);
  }

  public function approveSelfiePayment(Request $request){
    return Selfie::approveSelfiePayment($request);
  }

  public function selfieWeekList(){
    $staffs = Staff::where('status',1)->orderBy('forname','ASC')->get();
    $deafultYear = TaxYear::where('default', 1)->first();
    $taxWeeks = TaxWeek::where('taxYearId',$deafultYear->taxYearId)->groupBy('weekNumber')->get();
    $taxCurrentWeek = TaxWeek::where('taxYearId',$deafultYear->taxYearId)->groupBy('weekNumber')->where('date',date('Y-m-d'))->first();
    return view('payments.selfie.weeks',compact('staffs','taxWeeks','taxCurrentWeek'));
  }

  public function selfieWeekData(Request $request){
    return SelfieWeek::data($request,1);
  }

  public function selfieWeekReview($week,$staffId){
    return SelfieWeek::selfieWeekReview($week,$staffId);
  }

  public function moveToNextWeek($paymentId){
    return SelfieWeek::moveToNextWeek($paymentId);
  }

  public function moveToArchives($paymentId){
    return SelfieWeek::moveToArchives($paymentId);
  }
  public function revertToVA($paymentId) {
    return SelfieWeek::revertToVA($paymentId);
  }

  public function archives(){
    $staffs = Staff::where('status',1)->where('paymentMode',1)->orderBy('forname','ASC')->get();
    return view('payments.selfie.archives.list',compact('staffs'));
  }
  public function archivesData(Request $request){
    return SelfieArchive::data();
  }
  public function archivesAll($staffId){
    return SelfieArchive::archivesAll($staffId);
  }
  public function archivesAllWeeks(Request $request){
    return SelfieArchive::archivesAllWeeks($request);
  }
  public function archivesAllWeeksDetails(Request $request){
      return SelfieArchive::archivesAllWeeksDetails($request);
  }
  public function weekReportSelfie($weekNum) {
    return SelfieWeek::weekReportSelfie($weekNum);
  }
  public function weekSelfiePaymentReport($weekNum) {
    return SelfieWeek::weekSelfiePaymentReport($weekNum);
  }

}
