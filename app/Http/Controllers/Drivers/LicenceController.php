<?php

namespace App\Http\Controllers\Drivers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Storage;
use App\Models\Driver;
use App\Models\DriverLicence;

class LicenceController
{
  public function index($driverId){
    $driver = Driver::find(decrypt($driverId));
    return view('drivers.licences.index',compact('driver'));
  }

  public function data(Request $req){
    return Datatables::of(DriverLicence::where('driverId',decrypt($req->driver))->orderBy('driverLicenceId'))
      ->addIndexColumn()
      ->editColumn('status',function($vehicle){
        if($vehicle->status ==1){ return "<span class='label label-primary'>Active</span>";}
        if($vehicle->status ==0){ return "<span class='label label-warning'>In Active</span>";}
      })
      ->editColumn('actions',function($vehicle){
        $html ="";
        $html .= "<a href=".route('driver.licences.edit',encrypt($vehicle->driverLicenceId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('driver.licences.delete',encrypt($vehicle->driverLicenceId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function new($driverId){
    $driver = Driver::find(decrypt($driverId));
    return view('drivers.licences.new',compact('driver'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'number',
        'dateOfIssue',
        'dateOfExpiry',
        'validFrom',
        'validTo',
        'issuedBy'
    ]);
    $input = [
      'driverId'  =>decrypt($request->driverId),
      'number'  =>$request->number,
      'dateOfIssue'  =>date('Y-m-d',strtotime($request->dateOfIssue)),
      'dateOfExpiry'  =>date('Y-m-d',strtotime($request->dateOfExpiry)),
      'validFrom'  =>date('Y-m-d',strtotime($request->validFrom)),
      'validTo'  =>date('Y-m-d',strtotime($request->validTo)),
      'issuedBy'  =>$request->issuedBy,
    ];
    if ($request->hasFile('softCopy')) {
      $filename = time()."_".str_random(40).'.pdf';
      $request->softCopy->storeAs('drivers/driver_licence/',$filename);
      $input['softCopy'] = $filename;
    }

    $licence = DriverLicence::create($input);
    return redirect(route('driver.licences.home',$request->driverId))
      ->with('message','Succesfully created new Licence !!');
  }

  public function edit($licenceId){
    $licence = DriverLicence::with('driver')->find(decrypt($licenceId));
    ($licence->softCopy==null) ? $licence->softCopyExist = 0 : $licence->softCopyExist = 1;
    return view('drivers.licences.edit',compact('licence'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'number',
        'dateOfIssue',
        'dateOfExpiry',
        'validFrom',
        'validTo',
        'issuedBy'
    ]);
    $input = [
      'number'  =>$request->number,
      'dateOfIssue'  =>date('Y-m-d',strtotime($request->dateOfIssue)),
      'dateOfExpiry'  =>date('Y-m-d',strtotime($request->dateOfExpiry)),
      'validFrom'  =>date('Y-m-d',strtotime($request->validFrom)),
      'validTo'  =>date('Y-m-d',strtotime($request->validTo)),
      'issuedBy'  =>$request->issuedBy,
    ];
    $licence = DriverLicence::find(decrypt($request->driverLicenceId));

    if ($request->hasFile('softCopy')) {
      if($licence->softCopy){
        $exists = Storage::disk('local')->exists('drivers/driver_licence/'.$licence->softCopy);
        if($exists){
          Storage::disk('local')->delete('drivers/driver_licence/'.$licence->softCopy);
        }
      }
      $filename = time()."_".str_random(40).'.pdf';
      $request->softCopy->storeAs('drivers/driver_licence/',$filename);
      $input['softCopy'] = $filename;
    }

    $licence->update($input);
    return redirect(route('driver.licences.home',encrypt($licence->driverId)))
      ->with('message','Succesfully updated Vehicle !!');
  }

  public function delete($id){
    $licence = DriverLicence::find(decrypt($id));
    if($licence->softCopy){
      $exists = Storage::disk('local')->exists('drivers/driver_licence/'.$licence->softCopy);
      if($exists){
        Storage::disk('local')->delete('drivers/driver_licence/'.$licence->softCopy);
      }
    }
    $licence->delete();
    session()->flash('message', 'Succesfully deleted Licence !!');
    return ['url'=>route('driver.licences.home',encrypt($licence->driverId))];
  }
}
