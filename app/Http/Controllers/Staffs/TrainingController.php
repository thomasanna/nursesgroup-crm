<?php

namespace App\Http\Controllers\Staffs;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Storage;

use App\Models\StaffTraining;
use App\Models\Staff;
use App\Models\TrainingCourse;

class TrainingController
{
  public function index($staffId,$searchKeyword=""){
    $staff = Staff::with('category')->find(decrypt($staffId));
    return view('staffs.trainings.index',compact('staff','searchKeyword'));
  }

  public function data(Request $req){
    return Datatables::of(StaffTraining::with('course')->where('staffId',decrypt($req->staffId))->orderBy('staffId'))
      ->addIndexColumn()
      ->editColumn('status',function($training){
        if($training->status ==1){ return "<span class='label label-primary'>Allocated</span>";}
        if($training->status ==2){ return "<span class='label label-warning'>In Progress</span>";}
        if($training->status ==3){ return "<span class='label label-success'>Completed</span>";}
      })
      ->editColumn('expiryDate',function($training){
        return date('d-M-Y',strtotime($training->expiryDate));
      })
      ->editColumn('actions',function($training){
        $html ="";
        if($training->documentFile) $html .= "<a target='_blank' href=".asset('storage/app/staff/staff_trainings/'.$training->documentFile)." class='btn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-search'></i> Certificate</a>";
        $html .= "<a href=".route('staffs.training.edit',encrypt($training->staffTrainingId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('staffs.training.delete',encrypt($training->staffTrainingId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function new($staffId){
    $staff = Staff::with('category')->find(decrypt($staffId));
    $courses = TrainingCourse::where('staffCategoryId',$staff->categoryId)->get();
    return view('staffs.trainings.new',compact('staff','courses'));
  }

  public function getCourse(Request $req){
    $course = TrainingCourse::find($req->course)->first();
    return date('d-m-Y', strtotime('+'.$course->validity.' years', strtotime($req->date)));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'courseId' => 'required',
        'provider' => 'required',
        'issueDate' => 'required',
        'expiryDate' => 'required',
        'status' => 'required',
    ]);
    $input = [
      'courseId'  =>$request->courseId,
      'staffId'  =>decrypt($request->staffId),
      'provider'  =>$request->provider,
      'issueDate'  =>date('Y-m-d',strtotime($request->issueDate)),
      'expiryDate'  =>date('Y-m-d',strtotime($request->expiryDate)),
      'status'  =>$request->status,
      'comment'  =>$request->comment,
    ];
    if ($request->hasFile('documentFile')) {
      $filename = time()."_".str_random(40).'.pdf';
      $request->documentFile->storeAs('staff/staff_trainings',$filename);
      $input['documentFile'] = $filename;
    }
    $training = StaffTraining::create($input);
    return redirect(route('staffs.training.home',$request->staffId))
      ->with('message','Succesfully created new Training !!');
  }

  public function edit($trainingId){
    $training = StaffTraining::find(decrypt($trainingId));
    $staff = Staff::find($training->staffId);
    $courses = TrainingCourse::where('staffCategoryId',$staff->categoryId)->get();
    return view('staffs.trainings.edit',compact('training','courses','staff'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'courseId' => 'required',
        'provider' => 'required',
        'issueDate' => 'required',
        'expiryDate' => 'required',
        'status' => 'required',
    ]);

    $input = [
      'courseId'  =>$request->courseId,
      'provider'  =>$request->provider,
      'issueDate'  =>date('Y-m-d',strtotime($request->issueDate)),
      'expiryDate'  =>date('Y-m-d',strtotime($request->expiryDate)),
      'status'  =>$request->status,
      'comment'  =>$request->comment,
    ];

    $training = StaffTraining::find(decrypt($request->trainingId));
    if ($request->hasFile('documentFile')) {
      if($training->documentFile){
        $exists = Storage::disk('local')->exists('staff/staff_trainings/'.$training->documentFile);
        if($exists){
          Storage::disk('local')->delete('staff/staff_trainings/'.$training->documentFile);
        }
      }
      $filename = time()."_".str_random(40).'.pdf';
      $request->documentFile->storeAs('staff/staff_trainings',$filename);
      $input['documentFile'] = $filename;
    }

    $training->update($input);
    return redirect(route('staffs.training.home',encrypt($training->staffId)))
      ->with('message','Succesfully updated training !!');
  }

  public function delete($id){
    $training = StaffTraining::find(decrypt($id));
    $exists = Storage::disk('local')->exists('staff/staff_trainings/'.$training->documentFile);
    if($exists){
      Storage::disk('local')->delete('staff/staff_trainings/'.$training->documentFile);
    }
    $training->delete();
    session()->flash('message', 'Succesfully deleted Training !!');
    return ['url'=>route('staffs.training.home',encrypt($training->staffId))];
  }
}
