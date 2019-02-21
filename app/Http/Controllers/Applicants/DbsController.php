<?php

namespace App\Http\Controllers\Applicants;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Applicant;
use App\Models\ApplicantDbs;
use Storage;

use Illuminate\Http\Request;

class DbsController
{
  public function index($applicantId){
    $applicant = Applicant::with('category')->find(decrypt($applicantId));
    return view('applicants.dbs.index',compact('applicant'));
  }

  public function data(Request $req){
    return Datatables::of(ApplicantDbs::where('applicantId',decrypt($req->applicant))->orderBy('applicantDbsId'))
      ->addIndexColumn()
      ->editColumn('number',function($dbs){
        if($dbs->dbsType==1) return $dbs->apctnNumber;
        if($dbs->dbsType==2) return $dbs->validDbsNumber;
        if($dbs->dbsType==3) return $dbs->updateServiceNumber;
      })
      ->editColumn('dbsType',function($dbs){
        switch ($dbs->dbsType) {
          case 1:
            return "<span class='label label-primary'>New Application</span>";
            break;
          case 2:
            return "<span class='label label-success'>Valid DBS Available</span>";
            break;
          case 3:
            return "<span class='label label-warning'>Update Service User</span>";
            break;
        }
      })
      ->editColumn('actions',function($dbs){
        $html ="";
        if($dbs->validCertificate) $html .= "<a target='_blank' href=".asset('storage/app/applicant/applicant_dbs/'.$dbs->validCertificate)." class='btn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-search'></i> Certificate</a>";
        $html .= "<a href=".route('applicant.dbs.edit',encrypt($dbs->applicantDbsId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('applicant.dbs.delete',encrypt($dbs->applicantDbsId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function new($applicantId){
    $applicant = Applicant::with('category')->find(decrypt($applicantId));
    return view('applicants.dbs.new',compact('applicant'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'dbsType' => 'required',
    ]);
    switch ($request->dbsType) {
      case 1:
        $input = [
          'applicantId' =>decrypt($request->applicantId),
          'dbsType' =>$request->dbsType,
          'apctnNumber'  =>$request->apctnNumber,
          'apctnAppliedDate'  =>date('Y-m-d',strtotime($request->apctnAppliedDate)),
          'apctnSubmittedBy'  =>$request->apctnSubmittedBy,
          'apctnAmountPaid'  =>$request->apctnAmountPaid,
          'apctnPaidBy'  =>$request->apctnPaidBy,
          'apctnFollowUpDate'  =>date('Y-m-d',strtotime($request->apctnFollowUpDate)),
          'apctnStatus'  =>$request->apctnStatus
        ];
        break;
      case 2:
        $input = [
          'applicantId' =>decrypt($request->applicantId),
          'dbsType' =>$request->dbsType,
          'validDbsNumber'  =>$request->validDbsNumber,
          'validIssueDate'  =>date('Y-m-d',strtotime($request->validIssueDate)),
          'validRegisteredBody'  =>$request->validRegisteredBody,
          'validType'  =>$request->validType,
          'validCertificate'  =>$request->validCertificate,
          'validPoliceRecordsOption'  =>$request->validPoliceRecordsOption,
          'validSection142Option'  =>$request->validSection142Option,
          'validChildActListOption'  =>$request->validChildActListOption,
          'validVulnerableAdultOption'  =>$request->validVulnerableAdultOption,
          'validCpoRelevantOption'  =>$request->validCpoRelevantOption
        ];
        if ($request->hasFile('validCertificate')) {
          $filename = time()."_".str_random(40).'.pdf';
          $request->validCertificate->storeAs('applicant/applicant_dbs',$filename);
          $input['validCertificate'] = $filename;
        }
        break;
      case 3:
        $input = [
          'applicantId' =>decrypt($request->applicantId),
          'dbsType' =>$request->dbsType,
          'updateServiceNumber'  =>$request->updateServiceNumber,
          'updateServiceCheckedDate'  =>date('Y-m-d',strtotime($request->updateServiceCheckedDate)),
          'updateServiceCheckedBy'  =>$request->updateServiceCheckedBy,
          'updateServiceStatus'  =>$request->updateServiceStatus,
          'updateServicePoliceRecordsOption'  =>$request->updateServicePoliceRecordsOption,
          'updateServiceSection142Option'  =>$request->updateServiceSection142Option,
          'updateServiceChildActListOption'  =>$request->updateServiceChildActListOption,
          'updateServiceVulnerableAdultOption'  =>$request->updateServiceVulnerableAdultOption,
          'updateServiceCpoRelevantOption'  =>$request->updateServiceCpoRelevantOption
        ];
        break;
    }
    $dbs = ApplicantDbs::create($input);
    return redirect(route('applicant.dbs.home',$request->applicantId))
      ->with('message','Succesfully created new DBS !!');
  }

  public function edit($dbsId){
    $dbs = ApplicantDbs::find(decrypt($dbsId));
    $applicant = Applicant::with('category')->find($dbs->applicantId);
    return view('applicants.dbs.edit',compact('dbs','applicant'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'dbsType' => 'required',
    ]);
    $dbs = ApplicantDbs::find(decrypt($request->dbsId));
    switch ($request->dbsType) {
      case 1:
        $input = [
          'dbsType' =>$request->dbsType,
          'apctnNumber'  =>$request->apctnNumber,
          'apctnAppliedDate'  =>date('Y-m-d',strtotime($request->apctnAppliedDate)),
          'apctnSubmittedBy'  =>$request->apctnSubmittedBy,
          'apctnAmountPaid'  =>$request->apctnAmountPaid,
          'apctnPaidBy'  =>$request->apctnPaidBy,
          'apctnFollowUpDate'  =>date('Y-m-d',strtotime($request->apctnFollowUpDate)),
          'apctnStatus'  =>$request->apctnStatus
        ];
        break;
      case 2:
        $input = [
          'dbsType' =>$request->dbsType,
          'validDbsNumber'  =>$request->validDbsNumber,
          'validIssueDate'  =>date('Y-m-d',strtotime($request->validIssueDate)),
          'validRegisteredBody'  =>$request->validRegisteredBody,
          'validType'  =>$request->validType,
          'validCertificate'  =>$request->validCertificate,
          'validPoliceRecordsOption'  =>$request->validPoliceRecordsOption,
          'validSection142Option'  =>$request->validSection142Option,
          'validChildActListOption'  =>$request->validChildActListOption,
          'validVulnerableAdultOption'  =>$request->validVulnerableAdultOption,
          'validCpoRelevantOption'  =>$request->validCpoRelevantOption
        ];
        if ($request->hasFile('validCertificate')) {
          if($dbs->validCertificate){
            $exists = Storage::disk('local')->exists('applicant/applicant_dbs/'.$dbs->validCertificate);
            if($exists){
              Storage::disk('local')->delete('applicant/applicant_dbs/'.$dbs->validCertificate);
            }
          }
          $filename = time()."_".str_random(40).'.pdf';
          $request->validCertificate->storeAs('applicant/applicant_dbs',$filename);
          $input['validCertificate'] = $filename;
        }
        break;
      case 3:
        $input = [
          'dbsType' =>$request->dbsType,
          'updateServiceNumber'  =>$request->updateServiceNumber,
          'updateServiceCheckedDate'  =>date('Y-m-d',strtotime($request->updateServiceCheckedDate)),
          'updateServiceCheckedBy'  =>$request->updateServiceCheckedBy,
          'updateServiceStatus'  =>$request->updateServiceStatus,
          'updateServicePoliceRecordsOption'  =>$request->updateServicePoliceRecordsOption,
          'updateServiceSection142Option'  =>$request->updateServiceSection142Option,
          'updateServiceChildActListOption'  =>$request->updateServiceChildActListOption,
          'updateServiceVulnerableAdultOption'  =>$request->updateServiceVulnerableAdultOption,
          'updateServiceCpoRelevantOption'  =>$request->updateServiceCpoRelevantOption
        ];
        break;
    }
    $dbs->update($input);
    return redirect(route('applicant.dbs.home',encrypt($dbs->applicantId)))
      ->with('message','Succesfully updated DBS !!');
  }

  public function delete($id){
    $dbs = ApplicantDbs::find(decrypt($id));
    if($dbs->validCertificate){
      $exists = Storage::disk('local')->exists('applicant/applicant_dbs/'.$dbs->validCertificate);
      if($exists){
        Storage::disk('local')->delete('applicant/applicant_dbs/'.$dbs->validCertificate);
      }
    }
    $dbs->delete();
    session()->flash('message', 'Succesfully deleted DBS !!');
    return ['url'=>route('applicant.dbs.home',encrypt($dbs->applicantId))];
  }
}
