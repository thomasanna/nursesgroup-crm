<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;
use Hash;

class LoginController
{
    public function login(){
      if(Auth::guard('admin')->check()){
        return redirect()->route('home.dashboard');
      }
      return view('startup.login');
    }
    public function checkLogin(Request $req){
      if (Auth::guard('admin')->attempt($req->only(['username','password']))) {
  			  return redirect()->route('home.dashboard');
  		}else{
  			return redirect()->route('login')->with('message','Invalid Login, Please try to login again');
  		}
    }

    public function logout(){
      Auth::guard('admin')->logout();
      return redirect()->route('login')->with('message','Successfully Logged Out!!');
    }

    public function newLogin(){
      // $input = [
      //   'name'  =>'Booking Staff Sepetember',
      //   'type'  =>6,
      //   'email' =>'bookStaffSepetember@nursesgroup.co.uk',
      //   'username' =>'bookstaffsepetember@nursesgroup.co.uk',
      //   'password' =>Hash::make('sepetemberBookStaff'),
      //   'secret_pin' =>Hash::make('sepetemberBookStaff'),
      // ];
      Admin::find(1)->update(['password'=>Hash::make('jobiMnursesGroup')]);
      // Admin::create($input);
      //mcjobi@nursesgroup.co.uk
      //jobiMnursesGroup
      return "Created !!!";
    }
}
