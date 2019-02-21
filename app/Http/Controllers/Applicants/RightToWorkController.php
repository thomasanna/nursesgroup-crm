<?php

namespace App\Http\Controllers\Applicants;

use Illuminate\Http\Request;

use App\Models\Applicant;
use App\Models\ApplicantRightToWork;
use App\Models\Country;

class RightToWorkController
{
    public function form($applicantId){
      $rtw = ApplicantRightToWork::with('country')->where('applicantId',decrypt($applicantId))->first();
      if(!$rtw){
        $newRtw = ApplicantRightToWork::with('country')->create(['applicantId'=>decrypt($applicantId)]);
        $rtw = ApplicantRightToWork::with('country')->where('applicantId',decrypt($applicantId))->first();
      }
      $applicant = Applicant::with('category')->find(decrypt($applicantId));
      $countries = Country::all();
      return view('applicants.right_to_work.form',compact('rtw','applicant','countries'));
    }

    public function update(Request $req){
      $rtw = ApplicantRightToWork::find(decrypt($req->applicantRightToWorkId));
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
        $req->passportDocumentFile->storeAs('applicant/applicant_rtw/passport',$filename);
        $rtw->passportDocumentFile = $filename;
      }

      if ($req->hasFile('visaDocumentFile')) {
        $filename = time()."_".str_random(40).'.pdf';
        $req->visaDocumentFile->storeAs('applicant/applicant_rtw/visa',$filename);
        $rtw->visaDocumentFile = $filename;
      }

      $rtw->save();

      return redirect(route('applicant.right.to.work.form',encrypt($rtw->applicantId)))->with('message','Succesfully Updated Right to Work Details !!');
    }
}
