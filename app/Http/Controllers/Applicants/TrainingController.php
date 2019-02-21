<?php

namespace App\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Storage;

use App\Models\ApplicantTraining;
use App\Models\Applicant;
use App\Models\TrainingCourse;

class TrainingController
{
  public function index($applicantId){
    $applicant = Applicant::with('category')->find(decrypt($applicantId));
    return view('applicants.trainings.index',compact('applicant'));
  }

  public function data(Request $req){
    return Datatables::of(ApplicantTraining::with('course')->where('applicantId',decrypt($req->applicant))->orderBy('applicantTrainingId'))
      ->addIndexColumn()
      ->editColumn('status',function($training){
        if($training->status ==1){ return "<span class='label label-primary'>Allocated</span>";}
        if($training->status ==2){ return "<span class='label label-warning'>In Progress</span>";}
        if($training->status ==3){ return "<span class='label label-success'>Completed</span>";}
      })
      ->editColumn('expiryDate',function($training){
        return date('d-M-Y',strtotime($training->expiryDate));
      })
      ->editColumn('actions',function($applicant){
        $html ="";
        if($applicant->documentFile) $html .= "<a target='_blank' href=".asset('storage/app/applicant/applicant_trainings/'.$applicant->documentFile)." class='btn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-search'></i> Certificate</a>";
        $html .= "<a href=".route('applicant.training.edit',encrypt($applicant->applicantTrainingId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('applicant.training.delete',encrypt($applicant->applicantTrainingId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function new($applicantId){
    $applicant = Applicant::with('category')->find(decrypt($applicantId));
    $courses = TrainingCourse::where('staffCategoryId',$applicant->categoryId)->get();
    return view('applicants.trainings.new',compact('applicant','courses'));
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
      'applicantId'  =>decrypt($request->applicantId),
      'provider'  =>$request->provider,
      'issueDate'  =>date('Y-m-d',strtotime($request->issueDate)),
      'expiryDate'  =>date('Y-m-d',strtotime($request->expiryDate)),
      'status'  =>$request->status,
      'comment'  =>$request->comment,
    ];
    if ($request->hasFile('documentFile')) {
      $filename = time()."_".str_random(40).'.pdf';
      $request->documentFile->storeAs('applicant/applicant_trainings',$filename);
      $input['documentFile'] = $filename;
    }
    $training = ApplicantTraining::create($input);
    return redirect(route('applicant.training.home',$request->applicantId))
      ->with('message','Succesfully created new Training !!');
  }

  public function edit($trainingId){
    $training = ApplicantTraining::find(decrypt($trainingId));
    $applicant = Applicant::find($training->applicantId);
    $courses = TrainingCourse::where('staffCategoryId',$applicant->categoryId)->get();
    return view('applicants.trainings.edit',compact('training','courses','applicant'));
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

    $training = ApplicantTraining::find(decrypt($request->trainingId));
    if ($request->hasFile('documentFile')) {
      if($training->documentFile){
        $exists = Storage::disk('local')->exists('applicant/applicant_trainings/'.$training->documentFile);
        if($exists){
          Storage::disk('local')->delete('applicant/applicant_trainings/'.$training->documentFile);
        }
      }
      $filename = time()."_".str_random(40).'.pdf';
      $request->documentFile->storeAs('applicant/applicant_trainings',$filename);
      $input['documentFile'] = $filename;
    }

    $training->update($input);
    return redirect(route('applicant.training.home',encrypt($training->applicantId)))
      ->with('message','Succesfully updated training !!');
  }

  public function delete($id){
    $training = ApplicantTraining::find(decrypt($id));
    $exists = Storage::disk('local')->exists('applicant/applicant_trainings/'.$training->documentFile);
    if($exists){
      Storage::disk('local')->delete('applicant/applicant_trainings/'.$training->documentFile);
    }
    $training->delete();
    session()->flash('message', 'Succesfully deleted Training !!');
    return ['url'=>route('applicant.training.home',encrypt($training->applicantId))];
  }
}
