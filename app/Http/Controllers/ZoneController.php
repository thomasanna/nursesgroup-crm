<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Branch;
use App\Models\BranchZone;
use Auth;

class ZoneController
{
    public function index($id){
      $branch = Branch::find(decrypt($id));
    	return view('branches.zones.index',compact('branch'));
    }
    public function data(Request $req,$id){
      return Datatables::of(BranchZone::where('branchId',$id)->orderBy('id','DESC'))
        ->addIndexColumn()
        ->editColumn('status',function($branch){
          if($branch->status ==1){ return "<span class='label label-success'>Active</span>";}
          if($branch->status ==0){ return "<span class='label label-warning'>Inactive</span>";}
        })
        ->editColumn('actions',function($branch){
          $html = "<a href=".route('zones.edit',encrypt($branch->id))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
          if(Auth::guard('admin')->user()->type!=12){
            $html .= "<a action=".route('zones.delete',encrypt($branch->id))." class='btn btn-danger btn-xs mrs'";
            $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
          }
          return $html;
        })->make(true);
    }

    public function get(Request $req){
      $zones = BranchZone::where('branchId',$req->branchId)->where('status',1)->orderBy('id','DESC')->get();
      $html = "<option value=''></option>";
      for ($i=0; $i < count($zones); $i++) {
        $html.="<option value=".$zones[$i]->id.">".$zones[$i]->name."</option>";
      }
      return $html;
    }

    public function new($id){
      $branch = Branch::find(decrypt($id));
      return view('branches.zones.new',compact('branch'));
    }

    public function save(Request $request){
      $validator = $request->validate([
          'name' => 'required',
      ]);
      $input = ['branchId'=>decrypt($request->branchId),'name'=>$request->name];
      BranchZone::create($input);
      return redirect(route('zones.home',$request->branchId))->with('message','Succesfully created new zone !!');
    }

    public function edit($id){
      $zone = BranchZone::find(decrypt($id));
      return view('branches.zones.edit',compact('zone'));
    }

    public function update(Request $request){
      $validator = $request->validate([
          'name' => 'required',
      ]);
      $branch = BranchZone::find(decrypt($request->pkId))->branchId;
      BranchZone::find(decrypt($request->pkId))->update($request->except('_token','pkId'));
      return redirect(route('zones.home',encrypt($branch)))->with('message','Succesfully updated zone !!');
    }

    public function delete($id){
      $zone = BranchZone::find(decrypt($id));
      $zoneBranch =$zone->branchId;
      $zone->delete();
      session()->flash('message', 'Succesfully deleted zone !!');
      return ['url'=>route('zones.home',encrypt($zoneBranch))];
    }
}
