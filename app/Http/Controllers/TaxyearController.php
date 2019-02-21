<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\TaxYear;
use Auth;
use Hash;

class TaxyearController
{
    public function index(){
      return view('taxyears.index');
    }

		public function data(Request $req){
        return Datatables::of(TaxYear::orderBy('taxYearId','ASC'))
         ->addIndexColumn()
         ->editColumn('actions',function($user){

          $html = "<a href=".route('taxyears.edit',encrypt($user->taxYearId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
            $html .= "<a action=".route('taxyears.delete',encrypt($user->taxYearId))." class='btn btn-danger btn-xs mrs'";
            $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
          return $html;
        })
        ->make(true);
	}
	public function new(){

      return view('taxyears.new');
    }

    public function save(Request $request){
    	 $validator = $request->validate([
          'taxYearTo' => 'required',
          'taxYearFrom' => 'required',
      ]);

    	$input = $request->except('_token');

    	  $tax_years = TaxYear::create($input);
            return redirect(route('taxyears.home'))->with('message','Succesfully created new Tax Year!!'); 
    }


    public function edit($taxYearId){
            $user = TaxYear::find(decrypt($taxYearId));
            return view('taxyears.edit',compact('user'));
    }
    public function update(Request $request){
      $validator = $request->validate([
          'taxYearTo' => 'required',
          'taxYearFrom' => 'required',
      ]);
      TaxYear::find(decrypt($request->pkId))->update($request->except('_token','pkId'));
      return redirect(route('taxyears.home'))->with('message','Succesfully updated  Tax Year !!');
    }



    public function delete($taxYearId){
      TaxYear::find(decrypt($taxYearId))->delete();
      session()->flash('message', 'Succesfully deleted  Tax Year !!');
      return ['url'=>route('taxyears.home')];
    }

}
?>