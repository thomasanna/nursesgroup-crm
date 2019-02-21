<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\ClientUnit;
use App\Models\Client;
use App\Models\Branch;
use App\Models\BranchZone;
use App\Models\Staff;
use App\Models\StaffPreferredUnit;
use App\Models\Zone;
use App\Models\ClientUnitZone;
use App\Models\ClientUnitContact;
use App\Models\ClientUnitLog;
use Auth;
use Hash;

class ClientUnitController
{
  public function index(){
    return view('clients.units.index');
  }
  public function indexStaff(){
    return view('clients.units.staffUnits');
  }
  public function data(Request $req){
    return Datatables::of(ClientUnit::with(['client','contact'])->orderBy('name'))
      ->addIndexColumn()
      ->editColumn('status',function($unit){
        if($unit->status ==1){ return "<span class='label label-success'>Active</span>";}
        if($unit->status ==0){ return "<span class='label label-warning'>Inactive</span>";}
      })

      ->editColumn('Utlog',function($unit){
             return "<button class='btn btn-primary btn-xs mrs openLogModal 'clientunitid='".encrypt($unit->clientUnitId)."' name='".$unit->name."' phone='".$unit->contact->phone."'>Unit Log</button>";
       })
      ->editColumn('actions',function($unit){
        $html = "<a href=".route('client_unit_contact.home',encrypt($unit->clientUnitId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Contacts</a>";
        $html .= "<a href=".route('client_unit_schedules.new',encrypt($unit->clientUnitId))." class='btn btn-success btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Schedules</a>";
        if(Auth::guard('admin')->user()->type!=12 && Auth::guard('admin')->user()->type!=6){
          $html .= "<a href=".route('client_unit_payments.new',encrypt($unit->clientUnitId))." class='btn btn-warning btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Payments</a>";
        }
        $html .= "<a href=".route('client_units.edit',encrypt($unit->clientUnitId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
        if(Auth::guard('admin')->user()->type!=12){
          $html .= "<a action=".route('client_units.delete',encrypt($unit->clientUnitId))." class='btn btn-danger btn-xs mrs'";
          $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        }
        return $html;
      })->make(true);
  }
  public function dataStaff(Request $req){
    return Datatables::of(ClientUnit::with(['client','contact'])->orderBy('name'))
      ->addIndexColumn()
      ->editColumn('status',function($unit){
        if($unit->status ==1){ return "<span class='label label-success'>Active</span>";}
        if($unit->status ==0){ return "<span class='label label-warning'>Inactive</span>";}
      })
      ->editColumn('actions',function($unit){
        $html = "<a href=".route('client_unit_contact.home',[encrypt($unit->clientUnitId),1])." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Contacts</a>";
        $html .= "<a href=".route('client_unit_schedules.new',[encrypt($unit->clientUnitId),1])." class='btn btn-success btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Schedules</a>";
        $html .= "<a href='javascript:void(0)' class='btn btn-danger btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Reports</a>";
        return $html;
      })->make(true);
  }

  public function new(){
    $branches= Branch::where('status',1)->get();
    $clients= Client::where('status',1)->get();
    $zones= Zone::where('status',1)->get();
    return view('clients.units.new',compact('branches','clients','zones'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'name' => 'required',
    ]);
       $input = $request->except('_token','password');
      $input['password'] = Hash::make($request->password);
      $user = ClientUnit::create($input);
      $clientUnit = ClientUnit::create($request->except('_token'));
      if($request->zoneId){
      foreach ($request->zoneId as $value) {
        ClientUnitZone::create(['clientUnitId'=>$clientUnit->clientUnitId,'zoneId'=>$value]);
      }
    }

    $staffs = Staff::select('staffId')->whereIn('zoneId',$request->zoneId)->get();
    foreach ($staffs as $staff) {
      StaffPreferredUnit::create(['staffId'=>$staff->staffId,'unitId'=>$clientUnit->clientUnitId,'status'=>1]);
    }

    return redirect(route('client_units.home'))->with('message','Succesfully created new Unit !!');
  }

  public function edit($id){
    $unit = ClientUnit::find(decrypt($id));
    $branches= Branch::where('status',1)->get();
    $clients= Client::where('status',1)->get();
    $zones= BranchZone::where('status',1)->get();
    $unitZonesAlter = ClientUnitZone::where('clientUnitId',$unit->clientUnitId)->pluck('zoneId');
    $unitZones= [];
    foreach ($unitZonesAlter as $value) {
      $unitZones[] = $value;
    }
    return view('clients.units.edit',compact('unit','branches','clients','unitZones','zones'));
  }

  public function update(Request $request){    
    $unit = ClientUnit::find(decrypt($request->pkId));
    $unit->update([
      'username' => $request->username,
      'password' => Hash::make($request->password),
    ]);
    $unit->update($request->except('_token','pkId','username','password'));
    if($request->zoneId){
      ClientUnitZone::where('clientUnitId',decrypt($request->pkId))->delete();
      foreach ($request->zoneId as $value) {
        ClientUnitZone::create(['clientUnitId'=>decrypt($request->pkId),'zoneId'=>$value]);
      }

      $staffs = Staff::select('staffId')->whereIn('zoneId',$request->zoneId)->get();
      StaffPreferredUnit::where('unitId',decrypt($id))->delete();
      foreach ($staffs as $staff) {
        StaffPreferredUnit::create(['staffId'=>$staff->staffId,'unitId'=>decrypt($request->pkId),'status'=>1]);
      }

    }
    return redirect(route('client_units.home'))->with('message','Succesfully updated Unit !!');
  }

  public function delete($id){
    ClientUnit::find(decrypt($id))->delete();
    // ClientUnitPhone::where('clientUnitId',decrypt($id))->delete();
    StaffPreferredUnit::where('unitId',decrypt($id))->delete();

    session()->flash('message', 'Succesfully deleted Unit !!');
    return ['url'=>route('client_units.home')];
  }

  public function getUnitsWithZone(Request $req){
    $units = ClientUnit::Join('client_unit_zones','client_units.clientUnitId','=','client_unit_zones.clientUnitId')
            ->where('client_unit_zones.zoneId',$req->zoneId)->where('client_unit_zones.status',1)->orderBy('clientUnitId','DESC')
            ->get(['client_units.clientUnitId','client_units.name']);
    $html ="";
    for ($i=0; $i < count($units); $i++) {
      $html.="<option value=".$units[$i]->clientUnitId.">".$units[$i]->name."</option>";
    }
    return $html;
  }

  public function getUnitsWithBranch(Request $req){
    $units = BranchZone::where('status',1)->orderBy('id','DESC')->get(['id','name','branchId']);
    $html ="";
    for ($i=0; $i < count($units); $i++) {
      $html.="<option value=".$units[$i]->id;
      if($units[$i]->branchId==$req->branchId){
        $html .=" selected='selected'";
      }
      $html.=">".$units[$i]->name."</option>";
    }
    return $html;
  }
    public function getUnitLog(Request $req){
        $logs = ClientUnitLog::with('admin')->where('clientUnitId',decrypt(request('clientUnitId')))->latest()->get();
        $html = view('clients.units.logTemplate',compact('logs'));
        return $html;
    }

    public function unitLogEntry(Request $req){
      ClientUnitLog::create([
        'clientUnitId' =>decrypt($req->unitId),
        'author' =>Auth::guard('admin')->user()->adminId,
        'content' =>$req->content,
        'type'=>2,
      ]);
      $logs = ClientUnitLog::with('admin')->where('clientUnitId',decrypt(request('unitId')))->latest()->get();
      $html = view('clients.units.logTemplate',compact('logs'));
      return $html;
    }

}
?>
