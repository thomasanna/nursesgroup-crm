<?php

namespace App\Http\Controllers\Staffs;

use Illuminate\Http\Request;

use App\Models\Staff;
use App\Models\StaffRightToWork;
use App\Models\Country;

class RightToWorkController
{
    public function form($staffId,$searchKeyword=""){
      $rtw = StaffRightToWork::with('country')->where('staffId',decrypt($staffId))->first();
      if(!$rtw){
        $newRtw = StaffRightToWork::with('country')->create(['staffId'=>decrypt($staffId)]);
        $rtw = StaffRightToWork::with('country')->where('staffId',decrypt($staffId))->first();
      }
      $staff = Staff::with('category')->find(decrypt($staffId));
      $countries = Country::all();
      return view('staffs.right_to_work.form',compact('rtw','staff','countries','searchKeyword'));
    }

    public function update(Request $req){
      $rtw = StaffRightToWork::find(decrypt($req->staffRightToWorkId));
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
        $req->passportDocumentFile->storeAs('staff/staff_rtw/passport',$filename);
        $rtw->passportDocumentFile = $filename;
      }

      if ($req->hasFile('visaDocumentFile')) {
        $filename = time()."_".str_random(40).'.pdf';
        $req->visaDocumentFile->storeAs('staff/staff_rtw/visa',$filename);
        $rtw->visaDocumentFile = $filename;
      }

      $rtw->save();

      return redirect(route('staffs.right.to.work.form',encrypt($rtw->staffId)))->with('message','Succesfully Updated Right to Work Details !!');
    }
}
