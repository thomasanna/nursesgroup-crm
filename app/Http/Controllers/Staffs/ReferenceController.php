<?php

namespace App\Http\Controllers\Staffs;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Staff;
use App\Models\StaffReference;
use Storage;

class ReferenceController
{
  public function index($staffId,$searchKeyword=""){
    $staff = Staff::with('category')->find(decrypt($staffId));
    return view('staffs.references.index',compact('staff','searchKeyword'));
  }

  public function data(Request $req){
    return Datatables::of(StaffReference::where('staffId',decrypt($req->staffId))->orderBy('staffReferenceId'))
      ->addIndexColumn()
      ->editColumn('status',function($reference){
        switch ($reference->status) {
          case 1:
            return "<span class='label label-primary'>Sent</span>";
            break;
          case 2:
            return "<span class='label label-primary'>1st Follow Up</span>";
            break;
          case 3:
            return "<span class='label label-primary'>2nd Follow Up</span>";
            break;
          case 4:
            return "<span class='label label-warning'>Inform Staff</span>";
            break;
          case 5:
            return "<span class='label label-primary'>Rejected</span>";
            break;
          case 6:
            return "<span class='label label-success'>Success</span>";
            break;
        }
      })
      ->editColumn('modeOfReference',function($reference){
        switch ($reference->modeOfReference) {
          case 1:
            return "<span class='label label-primary'>Phone</span>";
            break;
          case 2:
            return "<span class='label label-primary'>Email</span>";
            break;
          case 3:
            return "<span class='label label-primary'>Letter</span>";
            break;
          case 4:
            return "<span class='label label-warning'>Verbal</span>";
            break;
        }
      })
      ->editColumn('actions',function($reference){
        $html ="";
        if($reference->documentFile) $html .= "<a target='_blank' href=".asset('storage/app/staff/staff_references/'.$reference->documentFile)." class='btn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-search'></i> Certificate</a>";
        $html .= "<a href=".route('staffs.references.edit',encrypt($reference->staffReferenceId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('staffs.references.delete',encrypt($reference->staffReferenceId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function new($staffId){
    $count = StaffReference::where('staffId',decrypt($staffId))->count();
    if($count==4){
      return redirect(route('staff.references.home',$staffId))
        ->with('message','Sorry..You are exceeded to add references..,Kindly please delete one and add new reference.!!');
    }
    $staff = Staff::with('category')->find(decrypt($staffId));
    return view('staffs.references.new',compact('staff'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'fullName' => 'required',
    ]);
    $input = [
      'fullName'  =>$request->fullName,
      'staffId'  =>decrypt($request->staffId),
      'sentDate'  =>date('Y-m-d',strtotime($request->sentDate)),
      'position'  =>$request->position,
      'address'  =>$request->address,
      'phone'  =>$request->phone,
      'email'  =>$request->email,
      'website'  =>$request->website,
      'modeOfReference'  =>$request->modeOfReference,
      'sentBy'  =>$request->sentBy,
      'status'  =>$request->status,
      'followUpDate'  =>date('Y-m-d',strtotime($request->followUpDate)),
      'comment'  =>$request->comment,
    ];

    if ($request->hasFile('documentFile')) {
      $filename = time()."_".str_random(40).'.pdf';
      $request->documentFile->storeAs('Staff/Staff_references',$filename);
      $input['documentFile'] = $filename;
    }

    $dbs = StaffReference::create($input);
    return redirect(route('staffs.references.home',$request->staffId))
      ->with('message','Succesfully created new Reference !!');
  }

  public function edit($referenceId){
    $reference = StaffReference::find(decrypt($referenceId));
    $staff = Staff::with('category')->find($reference->staffId);
    return view('staffs.references.edit',compact('reference','staff'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'fullName' => 'required',
    ]);
    $reference = StaffReference::find(decrypt($request->referenceId));

    $input = [
      'fullName'  =>$request->fullName,
      'sentDate'  =>date('Y-m-d',strtotime($request->sentDate)),
      'position'  =>$request->position,
      'address'  =>$request->address,
      'phone'  =>$request->phone,
      'email'  =>$request->email,
      'website'  =>$request->website,
      'modeOfReference'  =>$request->modeOfReference,
      'sentBy'  =>$request->sentBy,
      'status'  =>$request->status,
      'followUpDate'  =>date('Y-m-d',strtotime($request->followUpDate)),
      'comment'  =>$request->comment,
    ];

    // if($reference->status != $request->status){
    //   $input['onStatusChanged']  =date('Y-m-d H:i:s');
    // }

    if ($request->hasFile('documentFile')) {
      if($reference->documentFile){
        $exists = Storage::disk('local')->exists('staff/staff_references/'.$reference->documentFile);
        if($exists){
          Storage::disk('local')->delete('staff/staff_references/'.$reference->documentFile);
        }
      }
      $filename = time()."_".str_random(40).'.pdf';
      $request->documentFile->storeAs('staff/staff_references',$filename);
      $input['documentFile'] = $filename;
    }

    $reference->update($input);
    return redirect(route('staffs.references.home',encrypt($reference->staffId)))
      ->with('message','Succesfully updated Reference !!');
  }

  public function delete($id){
    $reference = StaffReference::find(decrypt($id));
    if($reference->documentFile){
      $exists = Storage::disk('local')->exists('staff/staff_references/'.$reference->documentFile);
      if($exists){
        Storage::disk('local')->delete('staff/staff_references/'.$reference->documentFile);
      }
    }
    $reference->delete();
    session()->flash('message', 'Succesfully deleted Reference !!');
    return ['url'=>route('staffs.references.home',encrypt($reference->staffId))];
  }
}
