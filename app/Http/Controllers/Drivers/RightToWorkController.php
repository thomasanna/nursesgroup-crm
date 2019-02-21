<?php

namespace App\Http\Controllers\Drivers;

use Illuminate\Http\Request;

use App\Models\Driver;
use App\Models\DriverRightToWork;
use App\Models\Country;

class RightToWorkController
{
    public function form($driverId){
      $rtw = DriverRightToWork::with('country')->where('driverId',decrypt($driverId))->first();
      if(!$rtw){
        $newRtw = DriverRightToWork::with('country')->create(['driverId'=>decrypt($driverId)]);
        $rtw = DriverRightToWork::with('country')->where('driverId',decrypt($driverId))->first();
      }
      $driver = Driver::find(decrypt($driverId));
      $countries = Country::all();
      return view('drivers.right_to_work.form',compact('rtw','driver','countries'));
    }

    public function update(Request $req){
      $rtw = DriverRightToWork::find(decrypt($req->driverRightToWorkId));
      $rtw->nationality = $req->nationality;
      $rtw->passportNumber = $req->passportNumber;
      $rtw->passportPlaceOfIssue = $req->passportPlaceOfIssue;
      $rtw->passportDateOfIssue = date('Y-m-d',strtotime($req->passportDateOfIssue));
      $rtw->passportExpiryDate = date('Y-m-d',strtotime($req->passportExpiryDate));
      $rtw->visaType = $req->visaType;
      $rtw->visaNumber = $req->visaNumber;
      $rtw->visaPlaceOfIssue = $req->visaPlaceOfIssue;
      $rtw->visaDateOfIssue = date('Y-m-d',strtotime($req->visaDateOfIssue));
      $rtw->visaExpiryDate = date('Y-m-d',strtotime($req->visaExpiryDate));
      $rtw->visaComments = $req->visaComments;
      $rtw->visaExternalVerificationRequired = $req->visaExternalVerificationRequired;
      $rtw->visaFollowUpDate = date('Y-m-d',strtotime($req->visaFollowUpDate));
      $rtw->checkedBy = $req->checkedBy;
      $rtw->checkedDate = date('Y-m-d',strtotime($req->checkedDate));
      $rtw->disciplinaryProcedure = $req->disciplinaryProcedure;
      $rtw->disciplinaryProcedureComment = $req->disciplinaryProcedureComment;
      $rtw->pendingInvestigation = $req->pendingInvestigation;
      $rtw->pendingInvestigationComment = $req->pendingInvestigationComment;
      $rtw->medicalCondition = $req->medicalCondition;
      $rtw->medicalConditionComment = $req->medicalConditionComment;
      $rtw->status = $req->status;
      $rtw->maximumPermittedWeeklyHours = $req->maximumPermittedWeeklyHours;

      if ($req->hasFile('passportDocumentFile')) {
        $filename = time()."_".str_random(40).'.pdf';
        $req->passportDocumentFile->storeAs('drivers/driver_rtw/passport',$filename);
        $rtw->passportDocumentFile = $filename;
      }

      if ($req->hasFile('visaDocumentFile')) {
        $filename = time()."_".str_random(40).'.pdf';
        $req->visaDocumentFile->storeAs('drivers/driver_rtw/visa',$filename);
        $rtw->visaDocumentFile = $filename;
      }

      $rtw->save();

      return redirect(route('driver.right.to.work.form',encrypt($rtw->driverId)))->with('message','Succesfully Updated Right to Work Details !!');
    }
}
