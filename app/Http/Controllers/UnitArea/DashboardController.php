<?php

namespace App\Http\Controllers\UnitArea;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Staff;
use App\Models\Client;
use App\Models\ClientUnit;
use App\Models\ClientUnitLogin;
use App\Models\Booking;
use Hash;
use Auth;

class DashboardController
{
	
    public function index(){

      $client = Client::count();
      $units = ClientUnit::count();
      $activeApplicants = Applicant::where('status',1)->count();
      $terminatedApplicants = Applicant::where('status',2)->count();
      $activeStaff = Staff::where('status',1)->count();
      $inActiveStaff = Staff::where('status',0)->count();
      $terminatedStaff = Staff::onlyTrashed()->count();
    	return view('unitArea.dashboard',compact([
        'client',
        'units',
        'activeApplicants',
        'terminatedApplicants',
        'activeStaff',
        'inActiveStaff',
        'terminatedStaff',
      ]));
    }


 public function getWeeklyBookingGraphData(){
      $categories = $this->getLastNDays(7);
      $rgnData = $hcaData = $shcaData = [];

      for ($i=0; $i < count($categories); $i++) {
        $rgnCount = Booking::whereDate('date',date('Y-m-d',strtotime($categories[$i])))->where('categoryId',1)->where('unitId','==',21)->where('unitStatus',4)->count();
        $rgnData[] = $rgnCount;
        $hcaCount = Booking::whereDate('date',date('Y-m-d',strtotime($categories[$i])))->where('categoryId',2)->where('unitId','<>',21)->where('unitStatus',4)->count();
        $hcaData[] = $hcaCount;
      }

      $rgn = ['name'=>'RGN','data'=>$rgnData];
      $hca = ['name'=>'HCA','data'=>$hcaData];

      $shifts[0] = $hca;
      $shifts[1] = $rgn;

      $monthlyData = $this->getMonthlyData();
      $pieData = $this->getPieChartData();
      return response()->json(['lastWeek'=>['categories'=>$categories,'data'=>$shifts],'pieData'=>$pieData,'lastMonthCnfr'=>$monthlyData['confirmed'],'lastMonthCncl'=>$monthlyData['cancled'],'lastMonthUnbl'=>$monthlyData['unable']]);
    }


    public function getMonthlyData(){
      $clientUnitId = Auth::guard('unit')->user()->clientUnitLoginId;
      $days = array_collapse([$this->getLastNDays(20),$this->getComingDays(10)]);
      for ($i=0; $i < count($days); $i++) {
        $bookCountConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)->count();
        $bookDataConfirmed[] = $bookCountConfirmed;

        $bookCountCancled = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId','<>',21)->where('unitStatus',2)->count();
        $bookDataCancled[] = $bookCountCancled;

        $bookCountUnable = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId','<>',21)->where('unitStatus',3)->count();
        $bookDataUnable[] = $bookCountUnable;
      }
      return $bookData = array('confirmed'=>array('categories'=>$days,'data'=>$bookDataConfirmed),'cancled'=>array('categories'=>$days,'data'=>$bookDataCancled),'unable'=>array('categories'=>$days,'data'=>$bookDataUnable));
    }

    public function getPieChartData(){
      $days = $this->getLastNDays(30);
      $timestamp    = strtotime(date('M Y'));
      $firstDay = date('Y-m-01', $timestamp);
      $lastDay  = date('Y-m-t', $timestamp); // A leap year!
      $units = ClientUnit::all();
      for ($i=0; $i < count($units); $i++) {
        $bookCount = Booking::whereBetween('date',[$firstDay,$lastDay])->where('unitId',$units[$i]->clientUnitId)->where('unitId','<>',21)->where('unitStatus',4)->count();
        $bookData[] = ['name'=>$units[$i]->alias,'y'=>$bookCount];
      }
      return ['categories'=>$days,'data'=>$bookData,'from'=>date('d-M',strtotime($firstDay)),'to'=>date('d-M',strtotime($lastDay))];
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

}
