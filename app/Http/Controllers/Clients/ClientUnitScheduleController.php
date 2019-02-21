<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use App\Models\ClientUnitSchedule;
use App\Models\StaffCategory;
use App\Models\Shift;

class ClientUnitScheduleController
{
  public function new($clientUnitId){
    $categories =StaffCategory::where('status',1)->get()->toArray();
    $shifts = Shift::all();
    for ($i=0; $i < count($categories); $i++) {
      for ($j=0; $j < count($shifts); $j++) {
        $shiftData = ClientUnitSchedule::where('staffCategoryId',$categories[$i]['categoryId'])
                                          ->where('clientUnitId',decrypt($clientUnitId))
                                          ->where('shiftId',$shifts[$j]->shiftId)->first();

        $categories[$i]['shifts'][$shifts[$j]->shiftId]['id'] =$shifts[$j]->shiftId;
        $categories[$i]['shifts'][$shifts[$j]->shiftId]['name'] =$shifts[$j]->name;
        $categories[$i]['shifts'][$shifts[$j]->shiftId]['color'] =$shifts[$j]->colorCode;
        $categories[$i]['shifts'][$shifts[$j]->shiftId]['schedules'] =$shiftData;
      }
    }
    return view('clients.units.schedules.new',compact('categories','shifts','clientUnitId'));
  }

  public function save(Request $request){
    $exist = ClientUnitSchedule::where('staffCategoryId',decrypt($request->categoryId))
              ->where('clientUnitId',decrypt($request->clientUnitId))
              ->where('shiftId',decrypt($request->shiftId))->count();
    $data = [
      'startTime'=>date('H:i:s',strtotime($request->startTime)),
      'endTime'=>date('H:i:s',strtotime($request->endTime)),
      'unPaidBreak'=>date('H:i:s',strtotime($request->unPaidBreak)),
      'paidBreak'=>date('H:i:s',strtotime($request->paidBreak)),
      'totalHoursUnit'=>$request->totalHoursUnit,
      'totalHoursStaff'=>$request->totalHoursStaff,
    ];
    if($exist==0){
      $data['clientUnitId'] = decrypt($request->clientUnitId);
      $data['staffCategoryId'] = decrypt($request->categoryId);
      $data['shiftId'] = decrypt($request->shiftId);
      $clientUnitPayment = ClientUnitSchedule::create($data);
    }else{
      $clientUnitPayment = ClientUnitSchedule::where('staffCategoryId',decrypt($request->categoryId))
        ->where('clientUnitId',decrypt($request->clientUnitId))
        ->where('shiftId',decrypt($request->shiftId))->update($data);
    }

    return redirect(route('client_unit_schedules.new',$request->clientUnitId))
      ->with('message','Succesfully configured Schedules!!');
  }
}
