<?php

namespace App\Http\Controllers\Drivers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Storage;
use App\Models\Driver;
use App\Models\Vehicle;

class VehicleController
{
  public function index($driverId){
    $driver = Driver::find(decrypt($driverId));
    return view('drivers.vehicles.index',compact('driver'));
  }

  public function data(Request $req){
    return Datatables::of(Vehicle::where('driverId',decrypt($req->driver))->orderBy('vehicleId'))
      ->addIndexColumn()
      ->editColumn('status',function($vehicle){
        if($vehicle->status ==1){ return "<span class='label label-primary'>Active</span>";}
        if($vehicle->status ==0){ return "<span class='label label-warning'>In Active</span>";}
      })
      ->editColumn('actions',function($vehicle){
        $html ="";
        $html .= "<a href=".route('driver.vehicles.edit',encrypt($vehicle->vehicleId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('driver.vehicles.delete',encrypt($vehicle->vehicleId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function new($driverId){
    $driver = Driver::find(decrypt($driverId));
    return view('drivers.vehicles.new',compact('driver'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'make' => 'required',
        'model' => 'required',
        'regNumber' => 'required',
        'color' => 'required'
    ]);
    $input = [
      'driverId'  =>decrypt($request->driverId),
      'make'  =>$request->make,
      'model'  =>$request->model,
      'regNumber'  =>$request->regNumber,
      'color'  =>$request->color
    ];
    $training = Vehicle::create($input);
    return redirect(route('driver.vehicles.home',$request->driverId))
      ->with('message','Succesfully created new Vehicle !!');
  }

  public function edit($vehicleId){
    $vehicle = Vehicle::with('driver')->find(decrypt($vehicleId));
    return view('drivers.vehicles.edit',compact('vehicle'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'make' => 'required',
        'model' => 'required',
        'regNumber' => 'required',
        'color' => 'required'
    ]);
    $input = [
      'make'  =>$request->make,
      'model'  =>$request->model,
      'regNumber'  =>$request->regNumber,
      'color'  =>$request->color
    ];

    $vehicle = Vehicle::find(decrypt($request->vehicleId));
    $vehicle->update($input);
    return redirect(route('driver.vehicles.home',encrypt($vehicle->vehicleId)))
      ->with('message','Succesfully updated Vehicle !!');
  }

  public function delete($id){
    $vehicle = Vehicle::find(decrypt($id));
    $vehicle->delete();
    session()->flash('message', 'Succesfully deleted Vehicle !!');
    return ['url'=>route('driver.vehicles.home',encrypt($vehicle->driverId))];
  }
}
