<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\ClientUnitContact;
use App\Models\ClientUnit;

class ClientUnitContactController
{
  public function index($clientUnitId){
    $unit = ClientUnit::find(decrypt($clientUnitId));
    return view('clients.units.contacts.index',compact('unit'));
  }
  public function data($unitId,Request $req){
    return Datatables::of(ClientUnitContact::where('clientUnitId',decrypt($unitId))->orderBy('clientUnitPhoneId'))
      ->addIndexColumn()
      ->editColumn('status',function($unitPhone){
        if($unitPhone->status ==1){ return "<span class='label label-success'>Active</span>";}
        if($unitPhone->status ==0){ return "<span class='label label-warning'>Inactive</span>";}
      })
      ->editColumn('actions',function($unitPhone){
        $html = "<a href=".route('client_unit_contact.edit',encrypt($unitPhone->clientUnitPhoneId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
        $html .= "<a action=".route('client_unit_contact.delete',encrypt($unitPhone->clientUnitPhoneId))." class='btn btn-danger btn-xs mrs'";
        $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        return $html;
      })->make(true);
  }

  public function getContacts(Request $req){
    $contacts = ClientUnitContact::where('clientUnitId',$req->unit)->orderBy('fullName','ASC')->get();
    $html ="<option value=''></option>";
    foreach ($contacts as $contact) {
      $html .="<option value='".$contact->clientUnitPhoneId."'>".$contact->fullName."</option>";
    }

    return $html;
  }

  public function new($clientUnitId){
    $unit = ClientUnit::find(decrypt($clientUnitId));
    return view('clients.units.contacts.new',compact('unit'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'fullName' => 'required',
    ]);
    $input = $request->except('_token','clientUnitId');
    $input['clientUnitId'] =decrypt($request->clientUnitId);
    $clientUnit = ClientUnitContact::create($input);
    return redirect(route('client_unit_contact.home',$request->clientUnitId))
      ->with('message','Succesfully added new Unit Contact!!');
  }

  public function edit($id){
    $unitContact = ClientUnitContact::with('unit')->find(decrypt($id));
    return view('clients.units.contacts.edit',compact('unitContact'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'fullName' => 'required',
    ]);
    ClientUnitContact::find(decrypt($request->clientUnitContactId))
        ->update($request->except('_token','clientUnitContactId','clientUnitId'));
    return redirect(route('client_unit_contact.home',$request->clientUnitId))
        ->with('message','Succesfully updated Unit Contact !!');
  }

  public function delete($id){
    $clientUnitContact = ClientUnitContact::find(decrypt($id));
    $clientUnit = $clientUnitContact->clientUnitId;
    $clientUnitContact->delete();
    session()->flash('message', 'Succesfully deleted Unit Contact!!');
    return ['url'=>route('client_unit_contact.home',encrypt($clientUnit))];
  }
}
