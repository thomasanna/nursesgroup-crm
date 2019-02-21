<?php

namespace App\Http\Controllers\UnitArea\Account;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\ClientUnit;
use App\Models\Client;
use App\Models\Branch;
use App\Models\BranchZone;
use App\Models\Zone;
use App\Models\ClientUnitZone;
use App\Models\ClientUnitContact;
use App\Models\ClientUnitLog;
use Auth;
use Hash;


class AccountController{
    public function accountView() {
        // $id = Auth::guard('unit')->user()->clientUnitId;
        $id = 1;
        $unit = ClientUnit::find($id);
        $branches= Branch::where('status',1)->get();
        $clients= Client::where('status',1)->get();
        $zones= BranchZone::where('status',1)->get();
        $unitZonesAlter = ClientUnitZone::where('clientUnitId',$unit->clientUnitId)->pluck('zoneId');
        $unitZones= [];
        foreach ($unitZonesAlter as $value) {
        $unitZones[] = $value;
        }
        for ($i=0; $i < count($clients); $i++) {
            $clients[$i]['unitType'] = 'Nursing Home';
        }
        if($unit->type == 1) { $unit['unitType'] = 'Nursing Home'; } elseif($unit->type == 2) { $unit['unitType'] = 'Care Home';
        } elseif($unit->type == 3) { $unit['unitType'] = 'Residential'; } elseif($unit->type == 4) { $unit['unitType'] = 'Dialysis';
        } elseif($unit->type == 5) { $unit['unitType'] = 'NHS'; } elseif($unit->type == 6) { $unit['unitType'] = 'Private';
        } elseif($unit->type == 7) { $unit['unitType'] = 'Others'; }
        // return $unit; 
        $unit['branchName']= Branch::select('name')->where('branchId',$unit->branchId)->first();
        $unit['clientName']= Client::select('name')->where('clientId',$unit->clientId)->first();
        return view('unitArea.account.myAccount',compact('unit','branches','clients','unitZones','zones'));
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
        }
        return redirect(route('unit.area.account.account'))->with('message','Succesfully updated Unit !!');
    }

}
