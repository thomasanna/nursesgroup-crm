<?php

namespace App\Http\Controllers\UnitArea;

use Illuminate\Http\Request;
use App\Models\ClientUnitBudget;
use Auth;
use App\Models\Booking;
use App\Models\ClientUnitPayment;
use Carbon\Carbon;
use Yajra\Datatables\Facades\Datatables;

class BudgetController
{
    public function overview(){
    	$unitId = Auth::guard('unit')->user()->clientUnitId;
        $monthArray = $this->getComingMonths();
        $prevComing3Months = $this->getPrevComingMonths();
        $budget = [];
        for($i=0;$i<4;$i++){
        	$monthYear = date('m,Y ', strtotime($monthArray[$i]));
          $monthYear =  explode(",",$monthYear);
          $monthNo =  $monthYear[0];
          $year    =  $monthYear[1];
          $clientUnitBudgetData = ClientUnitBudget::where('clientUnitId',$unitId)->where('month',$monthNo)->where('year',$year)->first();
          if(!empty($clientUnitBudgetData)){
          	$budget[] = number_format($clientUnitBudgetData->budget,2);
          }
          else{
          	$budget[] ='';
          }
        }

        $countHcaRgnConfirmedCurrentMonth = $this->getCountHcaRgnConfirmedCurrentMonth();

        $countHcaRgnConfirmedNextMonth    = $this->getCountHcaRgnConfirmedNextMonth();
        // print_r($countHcaRgnConfirmedNextMonth); exit;

    	return view('unitArea.budget',compact(['monthArray','budget','countHcaRgnConfirmedCurrentMonth','countHcaRgnConfirmedNextMonth','prevComing3Months']));
    }



    public function	getComingMonths(){
    	$m = date("m");
        $monthArray = [];
        $monthArray[] =date('M, Y');
        for($i=1; $i < 11; $i++){
          $monthArray[] = date('M, Y', strtotime('+'.$i.' month'));
        }
        return $monthArray;
    }

    public function setAction(Request $req){
    	$unitId = Auth::guard('unit')->user()->clientUnitId;
        $monthArray = [];
    	$monthArray[] = array('month'=>$req->month0,'budget'=>$req->budget0);
    	$monthArray[] = array('month'=>$req->month1,'budget'=>$req->budget1);
    	$monthArray[] = array('month'=>$req->month2,'budget'=>$req->budget2);
    	$monthArray[] = array('month'=>$req->month3,'budget'=>$req->budget3);
    	foreach($monthArray as $data){
            $monthYear = date('m,Y ', strtotime($data['month']));
            $monthYear =  explode(",",$monthYear);
            $monthNo =  $monthYear[0];
            $year    =  $monthYear[1];
            $clientUnitBudgetData = ClientUnitBudget::where('clientUnitId',$unitId)
                                         ->where('month',$monthNo)
                                         ->where('year',$year)
                                         ->first();
            if(!empty($clientUnitBudgetData)){
	          	$clientUnitBudgetData->budget = $data['budget'];
	          	$clientUnitBudgetData->save();
            }
            else{
	          	ClientUnitBudget::create([
			          'clientUnitId'=>$unitId,
			          'year'=>$year,
			          'month'=>$monthNo,
			          'budget'=>$data['budget']
	            ]);
            }
    	}
    	return ['status'=>true];
    }

    public function bookingData(Request $req){
      $unitId = Auth::guard('unit')->user()->clientUnitId;
      $currentMonthFirstDate = date("Y-m-d", strtotime("first day of this month"));
    	$currentMonthLastDate = date("Y-m-d", strtotime("last day of this month"));
      $query = Booking::with('category','staff','unit','shift')
                    ->where('unitStatus',4)
                    ->where('unitId',$unitId);
        if(empty($req->columns[1]['search']['value'])){
          $query->whereBetween('date', [$currentMonthFirstDate,$currentMonthLastDate]);
        }

        $data = Datatables::of($query);
        $data->addIndexColumn();
        $data->filterColumn('booking.date', function($payment, $keyword) {
          $m = date('m',strtotime($keyword));
	    	  $de = date('d',strtotime($keyword));
          $y = date('Y',strtotime($keyword));
	        $no_of_days_month = cal_days_in_month(CAL_GREGORIAN, $m, $y);
	        $start = date('Y-m-d', mktime(0,0,0,$m,(1),$y));
	        $end = date('Y-m-d', mktime(0,0,0,$m,(1+$no_of_days_month),$y));
	        $payment->whereBetween('bookings.date',[date('Y-m-d',strtotime($start)),date('Y-m-d',strtotime($end))]);
        });
        $data->editColumn('booking.date',function($payment){
          if($payment->date) { return date('d-M-Y, D',strtotime($payment->date)); }
        });
     //    $data->editColumn('actions',function($payment){
	    //   $html = "<a href='' class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
	    //   $html .= "<a action='' class='btn btn-danger btn-xs mrs'";
	    //   $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
	    //   return $html;
	    // });
	     $tableData =$data->make(true);
	    return $tableData;
    }

    public function getCurrentMonthGraphData(){
      $clientUnitId = Auth::guard('unit')->user()->clientUnitId;
      $days = $this->getCurrentMonthDays();
      $bookRGNConfirmed  = [];
      $bookHCAConfirmed  = [];
      for ($i=0; $i < count($days); $i++) {
         $bookCountRGNConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
                  ->where('categoryId',1)
                  ->count();
          $bookRGNConfirmed[] = $bookCountRGNConfirmed;
          $bookCountHCAConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
                  ->where('categoryId',2)
                  ->count();
          $bookHCAConfirmed[] = $bookCountHCAConfirmed;

      }
      $currentMonth = date('M, Y');
      $amountRgnHca = $this->getHcaRgnAmount($currentMonth);
      //print_r($amountRgnHca); exit;

      return $bookData = array('confirmed'=>array('categories'=>$days,'rgn'=>$bookRGNConfirmed,'hca'=>$bookHCAConfirmed,
                   'rgnAmount' => $amountRgnHca['rgnAmount'], 'hcaAmount'=>$amountRgnHca['hcaAmount'],'totalAmount'=>$amountRgnHca['totalAmount']));

    }

    
    public function getHcaRgnAmount($month){
    	$clientUnitId = Auth::guard('unit')->user()->clientUnitId;
    	$hcaShiftPaymentPerDaySum = 0;
    	$totalHcaShiftPayment = 0;
    	$totalRgnShiftPayment = 0;
    	$rgnShiftPaymentPerDaySum = 0;
    	$totalShcaShiftPayment = 0;
    	$shcaShiftPaymentPerDaySum = 0;
        $days = $this->getMonthDays($month);
        for($i = 0 ; $i < count($days); $i++){

        	$bookingAll =   Booking::with('category','staff','unit','shift')->where('unitStatus',4)
                    ->where('date',date('Y-m-d',strtotime($days[$i])))
                    ->where('unitId',$clientUnitId)
                    ->get();
            $hca =  $bookingAll->reject(function ($hca){
                 	return $hca->categoryId != 2;
                 })
                 ->map(function ($hca){
                    return array('categoryId'=>$hca->categoryId,'date'=>$hca->date,'shiftId'=>$hca->shiftId,'shift'=>$hca->shift->name);
                   // return $hca;
                 });
           // print_r($hca);    
           if(!empty($hca)){
           	 $hcaShiftPaymentPerDaySum = $this->getShiftPayment($hca);  
           }
           $totalHcaShiftPayment = $totalHcaShiftPayment+ $hcaShiftPaymentPerDaySum;

           $rgn =  $bookingAll->reject(function ($rgn){
                 	return $rgn->categoryId != 1;
                 })
                 ->map(function ($rgn){
                    return array('categoryId'=>$rgn->categoryId,'date'=>$rgn->date,'shiftId'=>$rgn->shiftId,'shift'=>$rgn->shift->name);
                   // return $hca;
                 });
                // print_r($rgn);
            if(!empty($rgn)){
           	 $rgnShiftPaymentPerDaySum = $this->getShiftPayment($rgn);  
           }
           $totalRgnShiftPayment = $totalRgnShiftPayment+ $rgnShiftPaymentPerDaySum;  

            $shca =  $bookingAll->reject(function ($shca){
                 	return $shca->categoryId != 3;
                 })
                 ->map(function ($shca){
                    return array('categoryId'=>$shca->categoryId,'date'=>$shca->date,'shiftId'=>$shca->shiftId,'shift'=>$shca->shift->name);
                   // return $hca;
                 });
                // print_r($rgn);
            if(!empty($shca)){
           	 $shcaShiftPaymentPerDaySum = $this->getShiftPayment($shca);  
           }
           $totalShcaShiftPayment = $totalShcaShiftPayment+ $shcaShiftPaymentPerDaySum;      
                 

        }
        $total = $totalRgnShiftPayment + $totalHcaShiftPayment + $totalShcaShiftPayment;

        return array('rgnAmount' =>number_format($totalRgnShiftPayment,2) ,'hcaAmount'=> number_format($totalHcaShiftPayment,2),'totalAmount' =>number_format($total,2));
           
     // echo $totalHcaShiftPayment; exit;
    }

    public function getShiftPayment($data){
    	$clientUnitId = Auth::guard('unit')->user()->clientUnitId;
    	$shiftPaymentAmountPerDay =0;
    	$payment =0;
    	foreach($data as $dt){
    		 $day = date('l',strtotime($dt['date']));
    		 $shift = $dt['shift'];
    		// echo $dt['categoryId']; exit;
    		
    		// echo $day; exit;

    		 $paymentData = ClientUnitPayment::where('clientUnitId',$clientUnitId)
    		                    ->where('staffCategoryId',$dt['categoryId'])->first();
    		                   // print_r($paymentData); exit;
    		if($shift == 'Early' || $shift == 'Longday'){
    		 	//$day = 'day'.$day;
    		 	if($day == 'Monday'){ $payment = $paymentData->dayMonday;}
    		 	if($day == 'Tuesday'){ $payment = $paymentData->dayTuesday;}
    		 	if($day == 'Wednesday'){  $payment = $paymentData->dayWednesday;}
    		 	if($day == 'Thursday'){ $payment = $paymentData->dayThursday;}
    		 	if($day == 'Friday'){ $payment = $paymentData->dayFriday;}
    		 	if($day == 'Saturday'){ $payment = $paymentData->daySaturday;}
    		 	if($day == 'Sunday'){ $payment = $paymentData->daySunday;}
    		 }
    		 else{
    		 	if($day == 'Monday'){ $payment = $paymentData->nightMonday;}
    		 	if($day == 'Tuesday'){ $payment = $paymentData->nightTuesday;}
    		 	if($day == 'Wednesday'){$payment = $paymentData->nightWednesday;}
    		 	if($day == 'Thursday'){ $payment = $paymentData->nightThursday;}
    		 	if($day == 'Friday'){ $payment = $paymentData->nightFriday;}
    		 	if($day == 'Saturday'){ $payment = $paymentData->nightSaturday;}
    		 	if($day == 'Sunday'){ $payment = $paymentData->nightSunday;}
    		 }  
    		  $shiftPaymentAmountPerDay = $shiftPaymentAmountPerDay + $payment;                

    	}
    	return $shiftPaymentAmountPerDay;
    }

    public function getCurrentMonthDays(){
       $no_of_days_month = date('t');
       $m = date("m", strtotime("first day of this month"));
       $de= date("d", strtotime("first day of this month"));
       $y = date("Y", strtotime("first day of this month"));
       $dateArray = [];
       for($i=0;$i<$no_of_days_month;$i++){
       	$dateArray[] = date('d-m-Y', mktime(0,0,0,$m,($de+$i),$y));
       }
       return $dateArray;
    }

    public function getNextMonthGraphData(){
    	  $clientUnitId = Auth::guard('unit')->user()->clientUnitId;
	      $days = $this->getNextMonthDays();
	      $bookRGNConfirmed  = [];
	      $bookHCAConfirmed  = [];
	      for ($i=0; $i < count($days); $i++) {
	         $bookCountRGNConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
	                  ->where('categoryId',1)
	                  ->count();
	          $bookRGNConfirmed[] = $bookCountRGNConfirmed;
	          $bookCountHCAConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($days[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
	                  ->where('categoryId',2)
	                  ->count();
	          $bookHCAConfirmed[] = $bookCountHCAConfirmed;

      }
      $nextMonth = date('M, Y', strtotime('+1 month'));
      $amountRgnHca = $this->getHcaRgnAmount($nextMonth);
      //($amountRgnHca); exit;
      return $bookData = array('confirmed'=>array('categories'=>$days,'rgn'=>$bookRGNConfirmed,'hca'=>$bookHCAConfirmed,
      'rgnAmount' => $amountRgnHca['rgnAmount'], 'hcaAmount'=>$amountRgnHca['hcaAmount'],'totalAmount'=>$amountRgnHca['totalAmount']));

    }

    public function getNextMonthDays(){
    	$m = date('m',strtotime('first day of +1 month'));
    	$de = date('d',strtotime('first day of +1 month'));
    	$y = date('Y',strtotime('first day of +1 month'));
    	$no_of_days_month = cal_days_in_month(CAL_GREGORIAN, $m, $y);
    	$dateArray = [];
        for($i=0;$i<$no_of_days_month;$i++){
       	 $dateArray[] = date('d-m-Y', mktime(0,0,0,$m,($de+$i),$y));
        }
        return $dateArray;
    }

    public function getCountHcaRgnConfirmedCurrentMonth(){
    	$clientUnitId = Auth::guard('unit')->user()->clientUnitId;
        $currentMonthFirstDate = date("Y-m-d", strtotime("first day of this month"));
    	$currentMonthLastDate  = date("Y-m-d", strtotime("last day of this month"));
        $rgn = Booking::where('type',2)
                    ->where('unitStatus',4)
                    ->where('categoryId',1)
                    ->whereBetween('date', [$currentMonthFirstDate,$currentMonthLastDate])
                    ->where('unitId',$clientUnitId)
                    ->count();
        $hca = Booking::where('type',2)
                    ->where('unitStatus',4)
                    ->where('categoryId',2)
                    ->whereBetween('date', [$currentMonthFirstDate,$currentMonthLastDate])
                    ->where('unitId',$clientUnitId)
                    ->count();
        return array('rgnCount'=>$rgn , 'hcaCount'=>$hca);
    }

    public function getCountHcaRgnConfirmedNextMonth(){
    	$clientUnitId = Auth::guard('unit')->user()->clientUnitId;
        $nextMonthFirstDate = date("Y-m-d", strtotime("first day of +1 month"));
    	$nextMonthLastDate  = date("Y-m-d", strtotime("first day of +1 month"));
    	$rgn = Booking::where('type',2)
                    ->where('unitStatus',4)
                    ->where('categoryId',1)
                    ->whereBetween('date', [$nextMonthFirstDate,$nextMonthLastDate])
                    ->where('unitId',$clientUnitId)
                    ->count();
        $hca = Booking::where('type',2)
                    ->where('unitStatus',4)
                    ->where('categoryId',2)
                    ->whereBetween('date', [$nextMonthFirstDate,$nextMonthLastDate])
                    ->where('unitId',$clientUnitId)
                    ->count();
        return array('rgnCount'=>$rgn , 'hcaCount'=>$hca);
    }

    public function getPrevComingMonths(){
        $monthArray = [];
        for($i=3; $i >= 1; $i--){
          $monthArray[] = date('M, Y', strtotime('-'.$i.' month'));
        }
        $monthArray[] =date('M, Y');
        for($i=1; $i < 4; $i++){
          $monthArray[] = date('M, Y', strtotime('+'.$i.' month'));
        }
        return $monthArray;
    }

    public function bookingFilterByMonth(Request $req){
    	$clientUnitId = Auth::guard('unit')->user()->clientUnitId;

        $month = $req->month;

        $firstMonthDays = $this->getMonthDays($month);
       

        $firstMonthBookRGNConfirmed  = [];

      $month = $req->month;
      $firstMonthDays = $this->getMonthDays($month);
      $firstMonthBookRGNConfirmed  = [];
	  $firstMonthBookHCAConfirmed  = [];
	    for ($i=0; $i < count($firstMonthDays); $i++) {
	        $firstBookCountRGNConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($firstMonthDays[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
	                  ->where('categoryId',1)
	                  ->count();
	        $firstMonthBookRGNConfirmed[] = $firstBookCountRGNConfirmed;
	        $firstBookCountHCAConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($firstMonthDays[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
	                  ->where('categoryId',2)
	                  ->count();
	        $firstMonthBookHCAConfirmed[] = $firstBookCountHCAConfirmed;

      }

      $firstMonthBudgetAmount = $this->checkSetBudget($month);
       $firstAmountRgnHca = $this->getHcaRgnAmount($month);

      $nextMonth = date('M, Y', strtotime('+1 month', strtotime($month)));
      $nextMonthDays = $this->getMonthDays($nextMonth);
      $nextAmountRgnHca = $this->getHcaRgnAmount($nextMonth);
      $nextMonthBookRGNConfirmed  = [];
	    $nextMonthBookHCAConfirmed  = [];
	    for ($i=0; $i < count($nextMonthDays); $i++) {
	        $nextBookCountRGNConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($nextMonthDays[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
	                  ->where('categoryId',1)
	                  ->count();
	        $nextMonthBookRGNConfirmed[] = $nextBookCountRGNConfirmed;
	        $nextBookCountHCAConfirmed = Booking::whereDate('date',date('Y-m-d',strtotime($nextMonthDays[$i])))->where('unitId',$clientUnitId)->where('unitStatus',4)
	                  ->where('categoryId',2)
	                  ->count();
	        $nextMonthBookHCAConfirmed[] = $nextBookCountHCAConfirmed;

      }
      $nextMonthBudgetAmount = $this->checkSetBudget($nextMonth);
      $nextAmountRgnHca = $this->getHcaRgnAmount($nextMonth);


      $view1 = view("unitArea.budgetGraph1",compact('month','firstMonthBudgetAmount','firstMonthBookRGNConfirmed','firstMonthBookHCAConfirmed'))->render();
      $view2 = view("unitArea.budgetGraph2",compact('nextMonth','nextMonthBudgetAmount','nextMonthBookRGNConfirmed','nextMonthBookHCAConfirmed'))->render();
      return $bookData = array('graph'=>array(
        'first'=>array(
          'html'=>$view1,
          'categories'=>$firstMonthDays,
          'rgn'=>$firstMonthBookRGNConfirmed ,
          'hca'=>$firstMonthBookHCAConfirmed,
          'hcaCount'=>array_sum($firstMonthBookHCAConfirmed),
          'rgnAmount' => $firstAmountRgnHca['rgnAmount'],
          'hcaAmount'=>$firstAmountRgnHca['hcaAmount'],
          'totalAmount'=>$firstAmountRgnHca['totalAmount']
        ),
        'second'=>array(
          'html'=>$view2,
          'categories'=>$nextMonthDays,
          'rgn'=>$nextMonthBookRGNConfirmed ,
          'hca'=>$nextMonthBookHCAConfirmed,
          'hcaCount'=>array_sum($nextMonthBookHCAConfirmed),
          'rgnAmount' => $nextAmountRgnHca['rgnAmount'],
          'hcaAmount'=>$nextAmountRgnHca['hcaAmount'],
          'totalAmount'=>$nextAmountRgnHca['totalAmount']

        )));
    }

    public function getMonthDays($month){
    	$monthYear = date('m,Y ', strtotime($month));
        $monthYear =  explode(",",$monthYear);
        $monthNo =  $monthYear[0];
        $year    =  $monthYear[1];

        $m = date('m',strtotime($month));
    	$de = date('d',strtotime($month));
    	$y = date('Y',strtotime($month));
        $no_of_days_month = cal_days_in_month(CAL_GREGORIAN, $m, $y);

    	$dateArray = [];
        for($i=0;$i<$no_of_days_month;$i++){
       	 $dateArray[] = date('d-m-Y', mktime(0,0,0,$m,(1+$i),$y));
        }
        return $dateArray;
    }

    public function checkSetBudget($month){
    	$clientUnitId = Auth::guard('unit')->user()->clientUnitId;
    	$monthYear = date('m,Y ', strtotime($month));
        $monthYear =  explode(",",$monthYear);
        $monthNo =  $monthYear[0];
        $year    =  $monthYear[1];
    	$clientUnitBudgetData = ClientUnitBudget::where('clientUnitId',$clientUnitId)
                                         ->where('month',$monthNo)
                                         ->where('year',$year)
                                         ->first();
        if(!empty($clientUnitBudgetData)){
        	$budget = number_format($clientUnitBudgetData->budget,2);
        }
        else{
        	$budget = "";
        }
        return    $budget;
    }

    public function getCountHcaRgnConfirmed($month){
    	$clientUnitId = Auth::guard('unit')->user()->clientUnitId;
        $currentMonthFirstDate = date('Y-m-01', strtotime($query_date));
    	$currentMonthLastDate  = date("Y-m-d", strtotime("last day of this month"));
        $rgn = Booking::where('type',2)
                    ->where('unitStatus',4)
                    ->where('categoryId',1)
                    ->whereBetween('date', [$currentMonthFirstDate,$currentMonthLastDate])
                    ->where('unitId',$clientUnitId)
                    ->count();
        $hca = Booking::where('type',2)
                    ->where('unitStatus',4)
                    ->where('categoryId',2)
                    ->whereBetween('date', [$currentMonthFirstDate,$currentMonthLastDate])
                    ->where('unitId',$clientUnitId)
                    ->count();
        return array('rgnCount'=>$rgn , 'hcaCount'=>$hca);
    }
}
