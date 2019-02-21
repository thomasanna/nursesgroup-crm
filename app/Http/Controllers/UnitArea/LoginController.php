<?php

namespace App\Http\Controllers\UnitArea;

use Illuminate\Http\Request;
use Auth;
use App\Models\ClientUnit;
use App\Models\ClientUnitLogin;
use Hash;
class LoginController
{
    public function login(){
        if(Auth::guard('unit')->check()){
            return redirect()->route('unit.area.dashboard');
        }
          return view('unitArea.login.unitLogin');
    }

    public function checkLogin(Request $req){
    	if (Auth::guard('unit')->attempt($req->only(['username','password']))) {
            return redirect()->route('unit.area.dashboard');
        }else{
            return redirect()->route('unit.area.login.unitLogin')->with('message','Invalid Login, Please try to login again');
        }
    }

    public function logout(){
    	Auth::guard('unit')->logout();
        return redirect()->route('unit.area.login.unitLogin')->with('message','Successfully Logged Out!!');
    }
    public function resetPassword(){
        ClientUnit::find(1)->update(['password'=>Hash::make('signature123')]);
      return "password updated";
    }

    public function register(){
    	return view('unitArea.login.register');
    }

    public function saveRegister(Request $req){
        // return $req;
        if (Auth::guard('unit')->attempt($req->only(['username','password']))) {
            return redirect()->route('unit.area.dashboard');
        }else{

            $password = $req->password;
            $booking = ClientUnitLogin::create([
              'clientUnitId'=>1,
              'username'=>$req->username,
              'password'=>Hash::make($password),
              'status'=>1
            ]);
            return redirect()->route('unit.area.dashboard');
        }
    }




}
