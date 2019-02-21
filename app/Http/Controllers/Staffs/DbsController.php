<?php

namespace App\Http\Controllers\Staffs;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Staff;
use App\Models\StaffDbs;
use Storage;

use Illuminate\Http\Request;

class DbsController
{
  public function index($staffId,$searchKeyword=""){
    $staff = Staff::with('category')->find(decrypt($staffId));
    return view('staffs.dbs.index',compact('staff','searchKeyword'));
  }

  public function data(Request $req){
    return Datatables::of(StaffDbs::where('staffId',decrypt($req->staffId))->orderBy('staffId'))
      ->addIndexColumn()
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
        if($dbs->documentFile) $html .= "<a target='_blank' href=".asset('storage/app/applicant/staff_dbs/'.$dbs->documentFile)." class='btn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-search'></i> Certificate</a>";
        $html .= "<a href=".route('staffs.dbs.edit',encrypt($dbs->staffDbsId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('staffs.dbs.delete',encrypt($dbs->staffDbsId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function new($staffId){
    $staff = Staff::with('category')->find(decrypt($staffId));
    return view('staffs.dbs.new',compact('staff'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'dbsType' => 'required',
    ]);
    switch ($request->dbsType) {
      case 1:
        $input = [
          'staffId' =>decrypt($request->staffId),
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
          'staffId' =>decrypt($request->staffId),
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
          'staffId' =>decrypt($request->staffId),
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

    $dbs = StaffDbs::create($input);
    return redirect(route('staffs.dbs.home',$request->staffId))
      ->with('message','Succesfully created new DBS !!');
  }

  public function edit($dbsId){
    $dbs = StaffDbs::find(decrypt($dbsId));
    $staff = Staff::with('category')->find($dbs->staffId);
    return view('staffs.dbs.edit',compact('dbs','staff'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'dbsType' => 'required',
    ]);
    $dbs = StaffDbs::find(decrypt($request->dbsId));

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
    return redirect(route('staffs.dbs.home',encrypt($dbs->staffId)))
      ->with('message','Succesfully updated DBS !!');
  }

  public function delete($id){
    $dbs = StaffDbs::find(decrypt($id));
    if($dbs->documentFile){
      $exists = Storage::disk('local')->exists('staff/staff_dbs/'.$dbs->documentFile);
      if($exists){
        Storage::disk('local')->delete('staff/staff_dbs/'.$dbs->documentFile);
      }
    }
    $dbs->delete();
    session()->flash('message', 'Succesfully deleted DBS !!');
    return ['url'=>route('staffs.dbs.home',encrypt($dbs->staffId))];
  }
}
