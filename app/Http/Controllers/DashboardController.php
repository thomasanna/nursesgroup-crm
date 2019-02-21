<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Staff;
use App\Models\Client;
use App\Models\ClientUnit;
use App\Models\Booking;
use Hash;

class DashboardController
{
    public function index(){
      $units = ClientUnit::count();
      $activeApplicants = Applicant::where('status',1)->count();
      $terminatedApplicants = Applicant::where('status',2)->count();
      $activeStaff = Staff::where('status',1)->count();
      $inActiveStaff = Staff::where('status',0)->count();
      $terminatedStaff = Staff::onlyTrashed()->count();
      $unfilled = Booking::whereDate('date',date('Y-m-d'))->where('unitId','<>',21)->where('unitStatus',4)->whereNull('staffId')->count();
      return view('dashboard.dashboard',compact([
        'client',
        'units',
        'activeApplicants',
        'terminatedApplicants',
        'activeStaff',
        'inActiveStaff',
        'terminatedStaff',
        'unfilled'
      ]));
    }

    public function  getMonthlyData(){
      $monthlyData = $this->getMonthlyBookingGraphData();
      return response()->json([
        'lastMonthCnfr'=>$monthlyData['confirmed'],
        'lastMonthCncl'=>$monthlyData['cancled'],
        'lastMonthUnbl'=>$monthlyData['unable']
        ]);
    }


    public function getMonthlyBookingGraphData(){
      $days = array_collapse([$this->getLastNDays(20),$this->getComingDays(10)]);
      for ($i=0; $i < count($days); $i++) {
        $bookCountConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId','<>',21)->where('unitStatus',4)->count();
        $bookDataConfirmed[] = $bookCountConfirmed;

        $bookCountCancled = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId','<>',21)->where('unitStatus',2)->count();
        $bookDataCancled[] = $bookCountCancled;

        $bookCountUnable = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId','<>',21)->where('unitStatus',3)->count();
        $bookDataUnable[] = $bookCountUnable;
      }
      return $bookData = array('confirmed'=>array('categories'=>$days,'data'=>$bookDataConfirmed),'cancled'=>array('categories'=>$days,'data'=>$bookDataCancled),'unable'=>array('categories'=>$days,'data'=>$bookDataUnable));
    }

    public static function getLastNDays($days, $format = 'd-M'){
      $m = date("m"); $de= date("d"); $y= date("Y");
      $dateArray = [];
      for($i=0; $i<=$days-1; $i++){
          $dateArray[] = '' . date($format, mktime(0,0,0,$m,($de-$i),$y)) . '';
      }
      return array_reverse($dateArray);
    }
    public function getComingDays($days,$format='d-M'){
      $m = date("m"); $de= date("d"); $y= date("Y");
      $dateArray = [];
      for($i=1; $i < 11; $i++){
          $dateArray[] = date('d-M', strtotime('+'.$i.' day'));
      }
      return $dateArray;
    }


    public function changePassword(Request $request){

      if (!(Hash::check($request->get('current-password'), auth()->user()->password))) {
          return redirect(route('home.dashboard'))->with("message", "Your  current password  does  not  matches  with  the  password  you  provided.  Please  try  again.");

      }

      if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
          return redirect(route('home.dashboard'))->with("message","New  Password  cannot  be  same  as  your  current  password.  Please  choose  a  different  password.");
      }

      $user = auth()->user();
      $user->password = Hash::make($request->get('new-password'));
      $user->save();
      return redirect(route('home.dashboard'))->with("message","Password changed successfully !");

    }
     public function indexone(){
      return view("unitArea.dashbord");
    }
}
