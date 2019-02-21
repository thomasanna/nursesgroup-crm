<?php
namespace App\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\HrPerformance;
use App\Models\Applicant;
use App\Models\ApplicantPreferredUnit;
use App\Models\ApplicantTraining;
use App\Models\ApplicantDbs;
use App\Models\ApplicantReference;
use App\Models\ApplicantRightToWork;
use App\Models\StaffCategory;
use App\Models\StaffTransport;
use App\Models\Staff;
use App\Models\StaffPreferredUnit;
use App\Models\StaffTraining;
use App\Models\StaffDbs;
use App\Models\StaffReference;
use App\Models\StaffRightToWork;
use App\Models\StaffBand;
use App\Models\Branch;
use App\Models\BranchZone;
use App\Models\Client;
use App\Models\ClientUnit;
use Storage;
use Auth;

class ApplicantController
{
  public function dashBoard(){
    $activeApplicants = Applicant::where('status',1)->count();
    $terminatedApplicants = Applicant::where('status',2)->count();
    $activeStaff = Staff::where('status',1)->count();
    $inActiveStaff = Staff::where('status',0)->count();
    $terminatedStaff = Staff::onlyTrashed()->count();
    return view('applicants.dashboard',compact([
      'activeApplicants',
      'terminatedApplicants',
      'activeStaff',
      'inActiveStaff',
      'terminatedStaff',
    ]));
  }

  public function index(){
    return view('applicants.index');
  }

  public function data(Request $req){
    return Datatables::of(Applicant::with('category','band','branch')->where('status',1)->orderBy('forname'))
      ->addIndexColumn()
      ->editColumn('email',function($applicant){
        $html ="<a href='mailto:$applicant->email'>".$applicant->email."<a>";
        return $html;
      })
      ->editColumn('mobile',function($applicant){
        $html ="<a href='tel:$applicant->mobile'>".$applicant->mobile."<a>";
        return $html;
      })
      ->editColumn('actions',function($applicant){
        // REFERENCE COLORS
        if($applicant->referenceProgress==1) $referenceProgress = 'danger';
        if($applicant->referenceProgress==2) $referenceProgress = 'warning';
        if($applicant->referenceProgress==3) $referenceProgress = 'success';
        // REFERENCE COLORS
        if($applicant->rtwProgress==1) $rtwProgress = 'danger';
        if($applicant->rtwProgress==2) $rtwProgress = 'warning';
        if($applicant->rtwProgress==3) $rtwProgress = 'success';
        // REFERENCE COLORS
        if($applicant->dbsProgress==1) $dbsProgress = 'danger';
        if($applicant->dbsProgress==2) $dbsProgress = 'warning';
        if($applicant->dbsProgress==3) $dbsProgress = 'success';
        // REFERENCE COLORS
        if($applicant->trainingProgress==1) $trainingProgress = 'danger';
        if($applicant->trainingProgress==2) $trainingProgress = 'warning';
        if($applicant->trainingProgress==3) $trainingProgress = 'success';
        // PERSONAL
        if($applicant->personalProgress==1) $personalProgress = 'danger';
        if($applicant->personalProgress==2) $personalProgress = 'warning';
        if($applicant->personalProgress==3) $personalProgress = 'success';

        $html = "<a href=".route('applicants.edit',[encrypt($applicant->applicantId),0])." class='btn btn-".$personalProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Personal</a>";
        $html .= "<a href=".route('applicant.references.home',encrypt($applicant->applicantId))." class='btn btn-".$referenceProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> References</a>";
        $html .= "<a href=".route('applicant.right.to.work.form',encrypt($applicant->applicantId))." class='btn btn-".$rtwProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Right To Work</a>";
        $html .= "<a href=".route('applicant.dbs.home',encrypt($applicant->applicantId))." class='btn btn-".$dbsProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> DBS</a>";
        $html .= "<a href=".route('applicant.training.home',encrypt($applicant->applicantId))." class='btn btn-".$trainingProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Trainings</a>";
        return $html;
      })->make(true);
  }

  public function indexTerminated(){
    return view('applicants.terminated.index');
  }

  public function dataTerminated(Request $req){
    return Datatables::of(Applicant::with('category','band')->where('status',2)->orderBy('forname'))
      ->addIndexColumn()
      ->editColumn('actions',function($applicant){
        $html = "<a href=".route('applicant.training.home',encrypt($applicant->applicantId))." class='btn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Trainings</a>";
        $html .= "<a href=".route('applicant.dbs.home',encrypt($applicant->applicantId))." class='btn btn-warning btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> DBS</a>";
        $html .= "<a href=".route('applicant.references.home',encrypt($applicant->applicantId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> References</a>";
        $html .= "<a href=".route('applicant.right.to.work.form',encrypt($applicant->applicantId))." class='btn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Right To Work</a>";
        $html .= "<a href=".route('applicants.edit',[encrypt($applicant->applicantId),1])." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Personal</a>";
        return $html;
      })->make(true);
  }

  public function new(){
    $categories = StaffCategory::where('status',1)->get();
    $transports = StaffTransport::where('status',1)->get();
    $branches = Branch::where('status',1)->get();
    $bands = StaffBand::where('status',1)->get();
    return view('applicants.new',compact('categories','bands','transports','branches'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'title' => 'required',
        'forname' => 'required',
        'surname' => 'required',
        'categoryId' => 'required',
        'dateOfBirth' => 'required',
        'email' => 'required|email',
        'mobile' => 'required',
        'whatsappNumber' => 'required',
        'gender' => 'required',
        'address' => 'required',
        'modeOfTransport' => 'required',
        'pickupLocation' => 'required',
        'zoneId' => 'required',
        'bandId' => 'required',
        'paymentMode' => 'required',
        'payRateWeekday' => 'required',
        'payRateWeekNight' => 'required',
        'payRateWeekendDay' => 'required',
        'payRateWeekendNight' => 'required',
        'payRateSpecialBhday' => 'required',
        'payRateSpecialBhnight' => 'required',
        'payRateBhday' => 'required',
        'payRateBhnight' => 'required',
    ]);
    $input = $request->except('_token','photo');
    if ($request->hasFile('photo')) {
      $filename = time()."_".str_random(40).'.jpg';
      $request->photo->storeAs('applicant/photo',$filename);
      $input['photo'] = $filename;
    }
    if ($request->hasFile('niDocumentFile')) {
      $filename = time()."_".str_random(40).'.pdf';
      $request->niDocumentFile->storeAs('applicant/applicant_ni',$filename);
      $input['niDocumentFile'] = $filename;
    }
    if($request->has('dateOfBirth')){
      $input['dateOfBirth'] = date('Y-m-d',strtotime($request->dateOfBirth));
    }
    if($request->has('joinedDate')){
      $input['joinedDate'] = date('Y-m-d',strtotime($request->joinedDate));
    }
    if($request->has('nmcPinExpiryDate')){
      $input['nmcPinExpiryDate'] = date('Y-m-d',strtotime($request->nmcPinExpiryDate));
    }
    if($request->has('nmcPinReValidationDate')){
      $input['nmcPinReValidationDate'] = date('Y-m-d',strtotime($request->nmcPinReValidationDate));
    }
    $applicant = Applicant::create($input);
    if($request->units){
      foreach ($request->units as $value) {
        ApplicantPreferredUnit::create(['applicantId'=>$applicant->applicantId,'unitId'=>$value]);
      }
    }
    return redirect(route('applicants.home'))->with('message','Succesfully created new Applicant !!');
  }

  public function edit($id,$page){
    $categories = StaffCategory::where('status',1)->get();
    $bands = StaffBand::where('status',1)->get();
    $branches = Branch::where('status',1)->get();
    $transports = StaffTransport::where('status',1)->get();
    $applicant = Applicant::find(decrypt($id));
    $zones = BranchZone::where('branchId',$applicant->branchId)->where('status',1)->get();
    $units = ClientUnit::Join('client_unit_zones','client_units.clientUnitId','=','client_unit_zones.clientUnitId')
            ->where('client_unit_zones.zoneId',$applicant->zoneId)->where('client_unit_zones.status',1)->orderBy('clientUnitId','DESC')
            ->get(['client_units.clientUnitId','client_units.name']);
    $applicantPclients = ApplicantPreferredUnit::where('applicantId',decrypt($id))->pluck('unitId')->toArray();
    ($applicant->niDocumentFile==null) ? $applicant->niDocumentExist = 0 : $applicant->niDocumentExist = 1;
    ($applicant->photo==null) ? $applicant->photoExist = 0 : $applicant->photoExist = 1;
    return view('applicants.edit',compact('applicant','categories','bands','transports','branches','zones','units','applicantPclients','page'));
  }

  public function update(Request $request){

    $validator = $request->validate([
        'title' => 'required',
        'forname' => 'required',
        'surname' => 'required',
        'categoryId' => 'required',
        'email' => 'required|email',
        'mobile' => 'required',
        'whatsappNumber' => 'required',
        'gender' => 'required',
        'address' => 'required',
        'modeOfTransport' => 'required',
        'pickupLocation' => 'required',
        'zoneId' => 'required',
        'bandId' => 'required',
        'paymentMode' => 'required',
        'payRateWeekday' => 'required',
        'payRateWeekNight' => 'required',
        'payRateWeekendDay' => 'required',
        'payRateWeekendNight' => 'required',
        'payRateSpecialBhday' => 'required',
        'payRateSpecialBhnight' => 'required',
        'payRateBhday' => 'required',
        'payRateBhnight' => 'required',
    ]);
    $input = $request->except('_token','pkId','photo','dateOfBirth');
    $applicant =Applicant::find(decrypt($request->pkId));
    if ($request->hasFile('photo')) {
      if($applicant->photo){
        $exists = Storage::disk('local')->exists('applicant/photo'.$applicant->photo);
        if($exists){
          Storage::disk('local')->delete('applicant/photo/'.$applicant->photo);
        }
      }
      $filename = time()."_".str_random(40).'.jpg';
      $request->photo->storeAs('applicant/photo/',$filename);
      $input['photo'] = $filename;
    }

    if ($request->hasFile('niDocumentFile')) {
      if($applicant->niDocumentFile){
        $exists = Storage::disk('local')->exists('applicant/applicant_ni/'.$applicant->niDocumentFile);
        if($exists){
          Storage::disk('local')->delete('applicant/applicant_ni/'.$applicant->niDocumentFile);
        }
      }

      $filename = time()."_".str_random(40).'.pdf';
      $request->niDocumentFile->storeAs('applicant/applicant_ni',$filename);
      $input['niDocumentFile'] = $filename;
    }
    if($request->has('dateOfBirth')){
      $input['dateOfBirth'] = date('Y-m-d',strtotime($request->dateOfBirth));
      $input['joinedDate'] = date('Y-m-d',strtotime($request->joinedDate));
    }
    if($request->has('joinedDate')){
      $input['joinedDate'] = date('Y-m-d',strtotime($request->joinedDate));
    }
    if($request->has('nmcPinExpiryDate')){
      $input['nmcPinExpiryDate'] = date('Y-m-d',strtotime($request->nmcPinExpiryDate));
    }
    if($request->has('nmcPinReValidationDate')){
      $input['nmcPinReValidationDate'] = date('Y-m-d',strtotime($request->nmcPinReValidationDate));
    }

    $applicant->update($input);
    if($request->unitId){
      ApplicantPreferredUnit::where('applicantId',decrypt($request->pkId))->delete();
      foreach ($request->unitId as $value) {
        ApplicantPreferredUnit::create(['applicantId'=>decrypt($request->pkId),'unitId'=>$value]);
      }
    }
    return redirect(route('applicants.home'))->with('message','Succesfully updated Applicant !!');
  }

  public function moveToActiveStaff($id){
    $applicant =Applicant::find(decrypt($id));
    $performance = ['applicantId'=>decrypt($id)];
    $fillAplicants = array_except(Applicant::find(decrypt($id)), ['applicantId','deleted_at','created_at','updated_at','photo'])->toArray();
    if($applicant->photo){
      $exists = Storage::disk('local')->exists('applicant/photo/'.$applicant->photo);
      if($exists){
        $filename = time()."_".str_random(40).'.jpg';
        Storage::disk('local')->move('applicant/photo/'.$applicant->photo, 'staff/photo/'.$filename);
        $fillAplicants['photo'] = $filename;
      }
    }
    if($applicant->niDocumentFile){
      $exists = Storage::disk('local')->exists('applicant/applicant_ni/'.$applicant->niDocumentFile);
      if($exists){
        $filename = time()."_".str_random(40).'.pdf';
        Storage::disk('local')->move('applicant/applicant_ni/'.$applicant->niDocumentFile, 'staff/staff_ni/'.$filename);
        $fillAplicants['niDocumentFile'] = $filename;
      }
    }
    $staff = Staff::create($fillAplicants);
    $performance['staffId'] = $staff->staffId;
    $this->moveRelatedEntries($id,$staff);
    $applicant->delete();
    ApplicantPreferredUnit::where('applicantId',decrypt($id))->delete();
    ApplicantTraining::where('applicantId',decrypt($id))->delete();
    ApplicantDbs::where('applicantId',decrypt($id))->delete();
    ApplicantReference::where('applicantId',decrypt($id))->delete();
    ApplicantRightToWork::where('applicantId',decrypt($id))->delete();
    $performance['hrId'] = Auth::guard('admin')->user()->adminId;
    $performance['action'] = 2;
    $performance['actionDate'] = date('Y-m-d');
    HrPerformance::create($performance);
    return redirect(route('applicants.home'))->with('message','Succesfully moved to active Staff!!');
  }

  public function moveToTerminated($applicantId){
    $applicant =Applicant::find(decrypt($applicantId));
    $applicant->status = 2;
    $applicant->save();
    $performance = ['applicantId'=>decrypt($applicantId)];
    $performance['hrId'] = Auth::guard('admin')->user()->adminId;
    $performance['action'] = 1;
    $performance['actionDate'] = date('Y-m-d');
    return redirect(route('applicants.home.terminated'))->with('message','Succesfully moved to terminated applicants!!');
  }

  public function moveToActiveApplicant($applicantId){
    $applicant =Applicant::find(decrypt($applicantId));
    $applicant->status = 1;
    $applicant->save();
    $performance = ['applicantId'=>decrypt($applicantId)];
    $performance['hrId'] = Auth::guard('admin')->user()->adminId;
    $performance['action'] = 5;
    $performance['actionDate'] = date('Y-m-d');
    return redirect(route('applicants.home'))->with('message','Succesfully moved to active staff!!');
  }

  public function moveRelatedEntries($id,$staff){
    // MOVING PREFERED UNITS
    $preferedUnits =ApplicantPreferredUnit::where('applicantId',decrypt($id))->get();
    foreach ($preferedUnits as $preferedUnit) {
      StaffPreferredUnit::create([
        'staffId' =>$staff->staffId,
        'unitId' =>$preferedUnit->unitId,
      ]);
    }
    // MOVING PREFERED UNITS
    // MOVING TRAININGS
    $applicantTrainings =ApplicantTraining::where('applicantId',decrypt($id))->get();
    foreach ($applicantTrainings as $applicantTraining) {
      $fillAplicantTraining = array_except(ApplicantTraining::find($applicantTraining->applicantTrainingId),
        ['applicantId','deleted_at','created_at','updated_at','documentFile'])->toArray();
      if($applicantTraining->documentFile){
        $exists = Storage::disk('local')->exists('applicant/applicant_trainings/'.$applicantTraining->documentFile);
        if($exists){
          $filename = time()."_".str_random(40).'.pdf';
          Storage::disk('local')->move('applicant/applicant_trainings/'.$applicantTraining->documentFile, 'staff/staff_trainings/'.$filename);
          $fillAplicantTraining['documentFile'] = $filename;
        }
      }
      $fillAplicantTraining['staffId'] = $staff->staffId;
      StaffTraining::create($fillAplicantTraining);
    }
    // MOVING TRAININGS
    //MOVING DBS
    $applicantDbs =ApplicantDbs::where('applicantId',decrypt($id))->get();
    foreach ($applicantDbs as $applicantDbsItem) {
      $fillAplicantDbs = array_except(ApplicantDbs::find($applicantDbsItem->applicantDbsId),
        ['applicantId','deleted_at','created_at','updated_at','documentFile'])->toArray();
      if($applicantDbsItem->documentFile){
        $exists = Storage::disk('local')->exists('applicant/applicant_dbs/'.$applicantDbsItem->documentFile);
        if($exists){
          $filename = time()."_".str_random(40).'.pdf';
          Storage::disk('local')->move('applicant/applicant_dbs/'.$applicantDbsItem->documentFile, 'staff/staff_dbs/'.$filename);
          $fillAplicantDbs['documentFile'] = $filename;
        }
      }
      $fillAplicantDbs['staffId'] = $staff->staffId;
      StaffDbs::create($fillAplicantDbs);
    }
    //MOVING DBS

    //MOVING REFERENCES
    $applicantReferls =ApplicantReference::where('applicantId',decrypt($id))->get();
    foreach ($applicantReferls as $applicantReferl) {
      $fillAplicantRefrls = array_except(ApplicantReference::find($applicantReferl->applicantReferenceId),
        ['applicantId','deleted_at','created_at','updated_at','documentFile'])->toArray();
      if($applicantReferl->documentFile){
        $exists = Storage::disk('local')->exists('applicant/applicant_references/'.$applicantReferl->documentFile);
        if($exists){
          $filename = time()."_".str_random(40).'.pdf';
          Storage::disk('local')->move('applicant/applicant_references/'.$applicantReferl->documentFile, 'staff/staff_references/'.$filename);
          $fillAplicantRefrls['documentFile'] = $filename;
        }
      }
      $fillAplicantRefrls['staffId'] = $staff->staffId;
      StaffReference::create($fillAplicantRefrls);
    }
    //MOVING REFERENCES

    //MOVING RTW
    $applicantRwt =ApplicantRightToWork::where('applicantId',decrypt($id))->first();
    if($applicantRwt){
      $fillApplicantRwt = array_except(ApplicantRightToWork::where('applicantId',decrypt($id))->first(),
        ['applicantId','deleted_at','created_at','updated_at','passportDocumentFile','visaDocumentFile'])->toArray();
      if($applicantRwt->passportDocumentFile){
        $filename = time()."_".str_random(40).'.pdf';
        Storage::disk('local')->move('applicant/applicant_rtw/passport/'.$applicantRwt->passportDocumentFile, 'staff/staff_rtw/passport/'.$filename);
        $fillApplicantRwt['passportDocumentFile'] = $filename;
      }
      if($applicantRwt->visaDocumentFile){
        $filename = time()."_".str_random(40).'.pdf';
        Storage::disk('local')->move('applicant/applicant_rtw/visa/'.$applicantRwt->visaDocumentFile, 'staff/staff_rtw/visa/'.$filename);
        $fillApplicantRwt['visaDocumentFile'] = $filename;
      }
      $fillApplicantRwt['staffId'] = $staff->staffId;
      StaffRightToWork::create($fillApplicantRwt);
    }
    //MOVING RTW
  }

  public function changeApplicantProgress(Request $req){
    $applicant =Applicant::find(decrypt($req->applicant));
    switch ($req->page) {
      case 1:
        $applicant->referenceProgress = $req->progress;
        break;
      case 2:
        $applicant->rtwProgress = $req->progress;
        break;
      case 3:
        $applicant->dbsProgress = $req->progress;
        break;
      case 4:
        $applicant->trainingProgress = $req->progress;
        break;
      case 5:
        $applicant->personalProgress = $req->progress;
        $applicant->save();
        session()->flash('message', 'Succesfully updated Progress !!');
        return route('applicants.home');
        break;
    }
    $applicant->save();
    session()->flash('message', 'Succesfully updated Progress !!');
    return 1;
  }
}
