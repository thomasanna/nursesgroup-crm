<?php
namespace App\Http\Controllers\Drivers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Driver;
use App\Models\DriverPreferredUnit;
use App\Models\Branch;
use App\Models\BranchZone;
use App\Models\Client;
use App\Models\ClientUnit;
use Storage;
use Auth;

class DriverController
{
  public function index(){
    return view('drivers.index');
  }

  public function data(Request $req){
    return Datatables::of(Driver::with(['branch'])->where('status',1)->orderBy('forname'))
      ->addIndexColumn()
      ->editColumn('actions',function($driver){
        // REFERENCE COLORS
        if($driver->referenceProgress==1) $referenceProgress = 'danger';
        if($driver->referenceProgress==2) $referenceProgress = 'warning';
        if($driver->referenceProgress==3) $referenceProgress = 'success';
        $referenceProgress = 'success';
        // REFERENCE COLORS
        if($driver->rtwProgress==1) $rtwProgress = 'danger';
        if($driver->rtwProgress==2) $rtwProgress = 'warning';
        if($driver->rtwProgress==3) $rtwProgress = 'success';
        $rtwProgress = 'success';
        // REFERENCE COLORS
        if($driver->dbsProgress==1) $dbsProgress = 'danger';
        if($driver->dbsProgress==2) $dbsProgress = 'warning';
        if($driver->dbsProgress==3) $dbsProgress = 'success';
        $dbsProgress = 'success';
        // REFERENCE COLORS
        if($driver->trainingProgress==1) $trainingProgress = 'danger';
        if($driver->trainingProgress==2) $trainingProgress = 'warning';
        if($driver->trainingProgress==3) $trainingProgress = 'success';
        $trainingProgress = 'success';
        // PERSONAL
        if($driver->personalProgress==1) $personalProgress = 'danger';
        if($driver->personalProgress==2) $personalProgress = 'warning';
        if($driver->personalProgress==3) $personalProgress = 'success';
        $personalProgress = 'success';

        $html = "<a href=".route('drivers.edit',[encrypt($driver->driverId),0])." class='btn btn-".$personalProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Personal</a>";
        $html .= "<a href=".route('driver.vehicles.home',encrypt($driver->driverId))." class='btn btn-".$referenceProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Vehicles</a>";
        $html .= "<a href=".route('driver.right.to.work.form',encrypt($driver->driverId))." class='btn btn-".$rtwProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Right To Work</a>";
        $html .= "<a href=".route('driver.licences.home',encrypt($driver->driverId))." class='btn btn-".$dbsProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Licences</a>";
        // $html .= "<a href=".route('applicant.training.home',encrypt($driver->applicantId))." class='btn btn-".$trainingProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Trainings</a>";
        return $html;
      })->make(true);
  }

  public function new(){
    $branches = Branch::where('status',1)->get();
    return view('drivers.new',compact('branches'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'forname' => 'required',
        'surname' => 'required',
        'dateOfBirth' => 'required',
        'email' => 'required|email',
        'mobile' => 'required',
        'whatsappNumber' => 'required',
        'gender' => 'required',
        'address' => 'required',
        'zoneId' => 'required',
        'paymentMode' => 'required',
        'ratePerMile' => 'required',
        'extraStaffRate' => 'required',
    ]);
    $input = $request->except('_token','photo');
    if ($request->hasFile('photo')) {
      $filename = time()."_".str_random(40).'.jpg';
      $request->photo->storeAs('drivers/photo',$filename);
      $input['photo'] = $filename;
    }
    if ($request->hasFile('niDocumentFile')) {
      $filename = time()."_".str_random(40).'.pdf';
      $request->niDocumentFile->storeAs('drivers/driver_ni',$filename);
      $input['niDocumentFile'] = $filename;
    }
    if($request->has('dateOfBirth')){
      $input['dateOfBirth'] = date('Y-m-d',strtotime($request->dateOfBirth));
    }
    if($request->has('joinedDate')){
      $input['joinedDate'] = date('Y-m-d',strtotime($request->joinedDate));
    }
    $driver = Driver::create($input);
    if($request->units){
      foreach ($request->units as $value) {
        DriverPreferredUnit::create(['driverId'=>$driver->driverId,'unitId'=>$value]);
      }
    }
    return redirect(route('drivers.home'))->with('message','Succesfully created new Driver !!');
  }

  public function edit($id,$page){
    $branches = Branch::where('status',1)->get();
    $driver = Driver::find(decrypt($id));
    $zones = BranchZone::where('branchId',$driver->branchId)->where('status',1)->get();
    $units = ClientUnit::Join('client_unit_zones','client_units.clientUnitId','=','client_unit_zones.clientUnitId')
            ->where('client_unit_zones.zoneId',$driver->zoneId)->where('client_unit_zones.status',1)->orderBy('clientUnitId','DESC')
            ->get(['client_units.clientUnitId','client_units.name']);
    $driverPUnits = DriverPreferredUnit::where('driverId',decrypt($id))->pluck('unitId')->toArray();
    ($driver->niDocumentFile==null) ? $driver->niDocumentExist = 0 : $driver->niDocumentExist = 1;
    ($driver->photo==null) ? $driver->photoExist = 0 : $driver->photoExist = 1;
    return view('drivers.edit',compact('driver','branches','zones','units','driverPUnits','page'));
  }

  public function update(Request $request){

    $validator = $request->validate([
      'forname' => 'required',
      'surname' => 'required',
      'dateOfBirth' => 'required',
      'email' => 'required|email',
      'mobile' => 'required',
      'whatsappNumber' => 'required',
      'gender' => 'required',
      'address' => 'required',
      'zoneId' => 'required',
      'paymentMode' => 'required',
      'ratePerMile' => 'required',
      'extraStaffRate' => 'required',
    ]);
    $input = $request->except('_token','pkId','photo','dateOfBirth');
    $driver =Driver::find(decrypt($request->pkId));
    if ($request->hasFile('photo')) {
      if($driver->photo){
        $exists = Storage::disk('local')->exists('drivers/photo'.$driver->photo);
        if($exists){
          Storage::disk('local')->delete('drivers/photo/'.$driver->photo);
        }
      }
      $filename = time()."_".str_random(40).'.jpg';
      $request->photo->storeAs('drivers/photo/',$filename);
      $input['photo'] = $filename;
    }

    if ($request->hasFile('niDocumentFile')) {
      if($driver->niDocumentFile){
        $exists = Storage::disk('local')->exists('drivers/driver_ni/'.$driver->niDocumentFile);
        if($exists){
          Storage::disk('local')->delete('drivers/driver_ni/'.$driver->niDocumentFile);
        }
      }

      $filename = time()."_".str_random(40).'.pdf';
      $request->niDocumentFile->storeAs('drivers/driver_ni',$filename);
      $input['niDocumentFile'] = $filename;
    }
    if($request->has('dateOfBirth')){
      $input['dateOfBirth'] = date('Y-m-d',strtotime($request->dateOfBirth));
      $input['joinedDate'] = date('Y-m-d',strtotime($request->joinedDate));
    }

    $driver->update($input);
    if($request->unitId){
      DriverPreferredUnit::where('driverId',decrypt($request->pkId))->delete();
      foreach ($request->unitId as $value) {
        DriverPreferredUnit::create(['driverId'=>decrypt($request->pkId),'unitId'=>$value]);
      }
    }
    return redirect(route('drivers.home'))->with('message','Succesfully updated Driver !!');
  }

  public function changeApplicantProgress(Request $req){
    $driver =Driver::find(decrypt($req->driver));
    switch ($req->page) {
      case 1:
        $driver->referenceProgress = $req->progress;
        break;
      case 2:
        $driver->rtwProgress = $req->progress;
        break;
      case 3:
        $driver->dbsProgress = $req->progress;
        break;
      case 4:
        $driver->trainingProgress = $req->progress;
        break;
      case 5:
        $driver->personalProgress = $req->progress;
        $driver->save();
        session()->flash('message', 'Succesfully updated Progress !!');
        return route('drivers.home');
        break;
    }
    $driver->save();
    session()->flash('message', 'Succesfully updated Progress !!');
    return 1;
  }
}
