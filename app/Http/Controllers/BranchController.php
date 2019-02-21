<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Branch;
use App\Models\BranchZone;
use Auth;

class BranchController
{
    public function index(){
    	return view('branches.index');
    }
    public function data(Request $req){
      return Datatables::of(Branch::orderBy('branchId','DESC'))
        ->addIndexColumn()
        ->editColumn('status',function($branch){
          if($branch->status ==1){ return "<span class='label label-success'>Active</span>";}
          if($branch->status ==0){ return "<span class='label label-warning'>Inactive</span>";}
        })
        ->editColumn('actions',function($branch){
          $html = "<a href=".route('zones.home',encrypt($branch->branchId))." class='btn btn-success btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Zones</a>";
          $html .= "<a href=".route('branches.edit',encrypt($branch->branchId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
          if(Auth::guard('admin')->user()->type!=12){
            $html .= "<a action=".route('branches.delete',encrypt($branch->branchId))." class='btn btn-danger btn-xs mrs'";
            $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
          }
          return $html;
        })->make(true);
    }

    public function new(){
      return view('branches.new');
    }

    public function save(Request $request){
      $validator = $request->validate([
          'name' => 'required',
      ]);

      $branch = Branch::create($request->except('_token'));
      $branchId = $branch->branchId;
      $zones = ['North','East','West','South','Center'];
      for ($i=0; $i < 5; $i++) {
        $zoneName = substr($branch->name, 0, 3)." - ".$zones[$i];
        BranchZone::create(['branchId'=>$branchId,'name'=>$zoneName]);
      }
      return redirect(route('branches.home'))->with('message','Succesfully created new branch !!');
    }

    public function edit($id){
      $branch = Branch::find(decrypt($id));
      return view('branches.edit',compact('branch'));
    }

    public function update(Request $request){
      $validator = $request->validate([
          'name' => 'required',
      ]);
      Branch::find(decrypt($request->pkId))->update($request->except('_token','pkId'));
      return redirect(route('branches.home'))->with('message','Succesfully updated branch !!');
    }

    public function delete($id){
      BranchZone::where('branchId',decrypt($id))->delete();
      Branch::find(decrypt($id))->delete();
      session()->flash('message', 'Succesfully deleted branch !!');
      return ['url'=>route('branches.home')];
    }
}
