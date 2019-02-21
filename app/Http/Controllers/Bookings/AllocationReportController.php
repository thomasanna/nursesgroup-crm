<?php

namespace App\Http\Controllers\Bookings;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Booking;
use App\Models\ClientUnitSchedule;
use App\Models\StaffAvailability;
use Carbon\Carbon;

class AllocationReportController
{

     public function allocationReport(Request $request) {
          $staffs = Staff::where('status', 1)->orderBy('forname','ASC')->get();
          return view('bookings.allocationReport', compact('staffs'));
     }

     public function allocationReportView(Request $request) {
        $startDate = Carbon::createFromFormat('Y-m-d',$request->startDate);
        $staffId = $request->staffId;


        $prevWeek = $startDate->copy()->startOfWeek()->subDay(14);
        $currWeek = $startDate->copy()->startOfWeek();
        $nextWeek = $startDate->copy()->startOfWeek()->addDay(14);

        $dbFrom = $prevWeek->copy();
        $dbTo = $nextWeek->copy()->endOfWeek();

        $dbExist = Booking::whereBetween('date',[$dbFrom->format('Y-m-d'),$dbTo->format('Y-m-d')])
                                    ->where('staffId',$staffId)
                                    ->select('date','shiftId','unitId','categoryId')
                                    ->groupBy('date')
                                    ->get()->toArray();

        foreach ($dbExist as $key => $row) {

            $dbExist[$key]['hour'] = 0;
            $unitSchedule = ClientUnitSchedule::where('clientUnitId',$row['unitId'])
                                                ->where('staffCategoryId',$row['categoryId'])
                                                ->where('shiftId',$row['shiftId'])
                                                ->first();
            if(!empty($unitSchedule)){
                $dbExist[$key]['hour'] = $unitSchedule->totalHoursUnit;
            }
        }


        $dbExist = array_column($dbExist,null,'date');
        $calenderData = [];
        $currDay = clone $prevWeek;
        for($i=1;$i<=5;$i++){
            $weekData = [];
            $weekData['label']='WEEK - '.$i;
            $weekData['hour']['vale']=0;
            $weekData['hour']['color']="";
            for($j=1;$j<=7;$j++){
                $state = "";
                $currDayData = (array_key_exists($currDay->format('Y-m-d'),$dbExist))?$dbExist[$currDay->format('Y-m-d')]:null;
                if(!empty($currDayData)){
                    if($currDayData['shiftId']=='1'){
                        $state = 'E';
                    }else if($currDayData['shiftId']=='2'){
                        $state = 'L';
                    }else if($currDayData['shiftId']=='3'){
                        $state = 'T';
                    }else if($currDayData['shiftId']=='4'){
                        $state .= 'N';
                    }

                    $weekData['hour']['vale'] += $currDayData['hour'];
                }

                if($j==1){
                    $weekData['label'] .= ' ('.$currDay->format('d-M-Y');
                }else if($j==7){
                    $weekData['label'] .= ' - '.$currDay->format('d-M-Y').')';
                }

                $weekData['day'.$j]['status'] = ($state=="")?"-":$state;
                $weekData['day'.$j]['date'] = $currDay->format('d-m-Y');
                $currDay->addDay();
            }

            if( $weekData['hour']['vale'] <=10){
               $weekData['hour']['color'] = 'bg-red';
            }else if( $weekData['hour']['vale'] <=20){
               $weekData['hour']['color'] = 'bg-orange';
            }else if( $weekData['hour']['vale'] <=40){
                $weekData['hour']['color'] = 'bg-lime';
            }else if( $weekData['hour']['vale'] <=90){
                $weekData['hour']['color'] = 'bg-green';
            }

            $calenderData[$i] = $weekData;
        }

        $returnData['content'] = (string) view('bookings.reportTable', compact('calenderData'));
        return $returnData;
     }


}
