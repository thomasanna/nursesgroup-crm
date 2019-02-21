<?php
   namespace App\Http\Controllers;

   use Illuminate\Http\Request;
   use Yajra\Datatables\Facades\Datatables;

   use App\Models\Permission;

   use Spatie\Permission\Models\Role;
   use Spatie\Permission\Traits\HasRoles;
   use assignRole;
   use Auth;

   class RoleController
   {
      public function index(){
            return view('roles.index');
          }

     public function data(Request $req){
           return Datatables::of(Role::orderBy('id','ASC'))

            ->addIndexColumn()
            ->editColumn('actions',function($user){
              $html = "<a href=".route('roles.edit',encrypt($user->id))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
              $html .= "<a action=".route('roles.delete',encrypt($user->id))." class='btn btn-danger btn-xs mrs'";
              $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
             return $html;
           })
           ->make(true);
          }
      public function new(){
           $groups = Permission::all();
           $unique = $groups->unique('module_name')->values()->all();
           $permissions=[];
        for ($i = 0; $i < count($unique); $i++) {
           $permissionsRow = $groups->where('module_name',$unique[$i]->module_name)->all();
           $itemArray=['name'=>$unique[$i]->module_name,'items'=>$permissionsRow];
           $permissions[] = $itemArray;
         }
        return view('roles.new',compact('permissions'));
       }

     public function save(Request $request){
         $validator = $request->validate([
             'name' => 'required',
         ]);
          $role =Role::create(['name'=>$request->get('name')]);

          $permissions= $request->input('permissions_name');
          for($i = 0;$i<count($permissions);$i++)
          {
            $role->givePermissionTo($permissions[$i]);
          }
       return redirect(route('roles.home'))->with('message','Succesfully created new Role!!');
       }
      public function edit($id){
        $role = Role::find(decrypt($id));
        $groups = Permission::all();
           $unique = $groups->unique('module_name')->values()->all();
           $permissions=[];
        for ($i = 0; $i < count($unique); $i++)
         {
           $permissionsRow = $groups->where('module_name',$unique[$i]->module_name)->all();
           $itemArray=['name'=>$unique[$i]->module_name,'items'=>$permissionsRow];
           $permissions[] = $itemArray;
         }
        return view('roles.edit',compact('role','permissions'));


       }
    public function update(Request $request){
             $validator = $request->validate([
                 'name' => 'required',
         ]);

          $role = Role::find(decrypt($request->pkId));
          $role->update(['name'=>request('name')]);
          $permissions= $request->input('permissions_name');
          $role->syncPermissions($permissions);

         return redirect(route('roles.home'))->with('message','Succesfully updated Role !!');
       }
      public function delete($id){
         Role::find(decrypt($id))->delete();
         session()->flash('message', 'Succesfully deleted Role !!');
         return ['url'=>route('roles.home')];
       }

   }

   ?>
