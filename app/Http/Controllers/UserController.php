<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Admin;
use App\Models\AdminLog;
use App\Models\AdminRole;
use Spatie\Permission\Models\Role;
use Auth;
use Hash;

class UserController
{
    public function index(){
      return view('users.index');
    }
    public function data(Request $req){
        return Datatables::of(Admin::orderBy('adminId','ASC'))
        ->addIndexColumn()
        ->editColumn('status',function($user){
          if($user->status ==1){ return "<span class='label label-success'>Active</span>";}
          if($user->status ==0){ return "<span class='label label-warning'>Inactive</span>";}
        })
       ->editColumn('user_log',function($user){
             return "<button class='btn btn-primary btn-xs mrs openLogModal 'adminid='".encrypt($user->adminId)."' name='".$user->name."'>User Log</button>";
       })
        ->editColumn('type',function($user){
          if($user->type ==1){ return "<span class='label label-success'>Admin</span>";}
          if($user->type ==2){ return "<span class='label label-success'>Accountant</span>";}
          if($user->type ==3){ return "<span class='label label-success'>HR</span>";}
          if($user->type ==4){ return "<span class='label label-success'>Payroll</span>";}
          if($user->type ==4){ return "<span class='label label-success'>client</span>";}
        })

        ->editColumn('actions',function($user){

          $html = "<a href=".route('users.edit',encrypt($user->adminId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
          if(Auth::guard('admin')->user()->type!=12){
            $html .= "<a action=".route('users.delete',encrypt($user->adminId))." class='btn btn-danger btn-xs mrs'";
            $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
    }
          return $html;
        })
        ->make(true);
        
    }

     public function new(){
       
             $roles = Role::all();
             return view('users.new',compact('user','roles'));
    }

    public function save(Request $request){
       $validator = $request->validate([
        'name' => 'required',
        'username' => 'required',
        'password' => 'required',
    ]);
      $input = $request->except('_token','password');
      $input = $request->except('_token','password');
      $input['password'] = Hash::make($request->password);
      $user = Admin::create($input);
 
      if($request->roles){
        foreach ($request->roles as $role) {
          AdminRole::create(['adminId'=>$user->adminId,'roleId'=>$role]);
        }
      }
       return redirect(route('users.home'))->with('message','Succesfully created new User!!'); 
    }

    public function edit($id){
            $user = Admin::find(decrypt($id));
            $roles = Role::all();
            return view('users.edit',compact('user','roles'));
    }


    public function update(Request $request){
      $validator = $request->validate([
          'name' => 'required',
      ]);
      $admin = Admin::find(decrypt($request->pkId));
      $admin->update($request->except('_token','pkId','roles'));
      $admin->syncRoles(request('roles'));
      
      return redirect(route('users.home'))->with('message','Succesfully updated user !!');
    }


    public function delete($id){
      Admin::find(decrypt($id))->delete();
      session()->flash('message', 'Succesfully deleted User !!');
      return ['url'=>route('users.home')];
    }
     public function getUserLog(Request $req){
        $logs = AdminLog::with('admin')->where('adminId',decrypt(request('adminId')))->latest()->get();
        $html = view('users.logTemplate',compact('logs'));
        return $html;
    }

      public function userLogEntry(Request $req){
     AdminLog::create([
        'adminId' =>decrypt($req->adminId),
        'author' =>Auth::guard('admin')->user()->adminId,
        'content' =>$req->content,
        'type'=>2,
      ]);
      $logs = AdminLog::with('admin')->where('adminId',decrypt(request('adminId')))->latest()->get();
      $html = view('users.logTemplate',compact('logs'));
      return $html;
    }
  }

  ?>
