<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Client;
use App\Models\Branch;
use App\Models\BranchZone;
use App\Models\ClientZone;
use Auth;

class ClientController
{
  public function index(){
    return view('clients.index');
  }
  public function data(Request $req){
    return Datatables::of(Client::orderBy('clientId','DESC'))
      ->addIndexColumn()
      ->editColumn('status',function($client){
        if($client->status ==1){ return "<span class='label label-success'>Active</span>";}
        if($client->status ==0){ return "<span class='label label-warning'>Inactive</span>";}
      })
      ->editColumn('actions',function($client){
        $html = "<a href=".route('clients.edit',encrypt($client->clientId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
        if(Auth::guard('admin')->user()->type!=12){
          $html .= "<a action=".route('clients.delete',encrypt($client->clientId))." class='btn btn-danger btn-xs mrs'";
          $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
        }
        return $html;
      })->make(true);
  }

  public function new(){
    $branches= Branch::where('status',1)->get();
    $zones= BranchZone::where('status',1)->get();
    return view('clients.new',compact('branches','zones'));
  }

  public function save(Request $request){
    $validator = $request->validate([
        'name' => 'required',
    ]);

    $client = Client::create($request->except('_token'));
    return redirect(route('clients.home'))->with('message','Succesfully created new client !!');
  }

  public function edit($id){
    $client = Client::find(decrypt($id));
    return view('clients.edit',compact('client'));
  }

  public function update(Request $request){
    $validator = $request->validate([
        'name' => 'required',
    ]);
    Client::find(decrypt($request->pkId))->update($request->except('_token','pkId'));
    return redirect(route('clients.home'))->with('message','Succesfully updated client !!');
  }

  public function delete($id){
    Client::find(decrypt($id))->delete();
    session()->flash('message', 'Succesfully deleted client !!');
    return ['url'=>route('clients.home')];
  }
}
