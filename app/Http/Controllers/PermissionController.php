<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Spatie\Permission\Models\Permission;
use Auth;
use Illuminate\Support\Facades\Input;
class PermissionController
{

	 public function index(){
	 return view('permissions.index');
		    }

   public function data(Request $req){
        return Datatables::of(Permission::orderBy('id','ASC'))
         ->addIndexColumn()
				 ->editColumn('name',function($permission){
					 $name=str_replace('_', ' ',$permission->name);
					 return ucwords($name);
				 })
         ->editColumn('actions',function($user){

          $html = "<a href=".route('permissions.edit',encrypt($user->id))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
            $html .= "<a action=".route('permissions.delete',encrypt($user->id))." class='btn btn-danger btn-xs mrs'";
            $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
          return $html;
        })
        ->make(true);
    }
  public function new(){
      return view('permissions.new');
    }

    public function save(Request $request){
        $validator = $request->validate([
          'name' => 'required',
      ]);

        $value= $request->input('name');

        $value =strtolower($value);
        $name=str_replace(' ', '_',$value);
        $role =Permission::create(['name'=>$name]);
         return redirect(route('roles.home'))->with('message','Succesfully created new permissions!!');
    }
    public function edit($id ){


        $user = Permission::find(decrypt($id));

   return view('permissions.edit',compact('user'));
    }
    public function update(Request $request){
      $validator = $request->validate([
          'name' => 'required',
      ]);
       Permission::find(decrypt($request->pkId))->update($request->except('_token','pkId'));
      return redirect(route('roles.home'))->with('message','Succesfully updated Permision !!');
    }
    public function delete($id){
      Permission::find(decrypt($id))->delete();
      session()->flash('message', 'Succesfully deleted Permision !!');
      return ['url'=>route('roles.home')];
    }
}
