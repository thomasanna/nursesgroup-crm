<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\ClientUnitPayment;
use App\Models\ClientUnit;
use App\Models\StaffCategory;

class ClientUnitPaymentController
{
  public function new($clientUnitId){
    $categories =StaffCategory::where('status',1)->get()->toArray();
    $categoryTypes = [
      ['id'=>1,'name'=>'Rate','colorCode'=>'#048e86'],
      ['id'=>2,'name'=>'ENIC','colorCode'=>'#0f12f0']
    ];
    for ($i=0; $i < count($categories); $i++) {
      for ($j=0; $j < count($categoryTypes); $j++) {
        $typeData = ClientUnitPayment::where('staffCategoryId',$categories[$i]['categoryId'])
                                          ->where('clientUnitId',decrypt($clientUnitId))
                                          ->where('rateType',$categoryTypes[$j]['id'])->first();

        $categories[$i]['type'][$categoryTypes[$j]['id']]['id'] =$categoryTypes[$j]['id'];
        $categories[$i]['type'][$categoryTypes[$j]['id']]['name'] =$categoryTypes[$j]['name'];
        $categories[$i]['type'][$categoryTypes[$j]['id']]['colorCode'] =$categoryTypes[$j]['colorCode'];
        $categories[$i]['type'][$categoryTypes[$j]['id']]['rates'] =$typeData;
      }
    }
    return view('clients.units.payments.new',compact('categories','categoryTypes','clientUnitId'));
  }

  public function save(Request $request){
    $exist = ClientUnitPayment::where('staffCategoryId',decrypt($request->categoryId))
              ->where('clientUnitId',decrypt($request->clientUnitId))
              ->where('rateType',decrypt($request->rateType))->count();
    $data = $request->except(['_token','clientUnitId','categoryId','rateType']);
    if($exist==0){
      $data['clientUnitId'] = decrypt($request->clientUnitId);
      $data['staffCategoryId'] = decrypt($request->categoryId);
      $data['rateType'] = decrypt($request->rateType);
      $clientUnitPayment = ClientUnitPayment::create($data);
    }else{
      $clientUnitPayment = ClientUnitPayment::where('staffCategoryId',decrypt($request->categoryId))
        ->where('clientUnitId',decrypt($request->clientUnitId))
        ->where('rateType',decrypt($request->rateType))->update($data);
    }

    return redirect(route('client_unit_payments.new',$request->clientUnitId))
      ->with('message','Succesfully configured Payments!!');
  }
}
