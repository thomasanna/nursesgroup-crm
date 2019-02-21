<?php

namespace App\Http\Controllers\Staffs;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\StaffAvailability;
use Carbon\Carbon;
use App\Models\Branch;

class StaffAvailabilityController
{
     public function availabilty(Request $request) {
        $staffs = Staff::where('status',1)->orderBy('forname','asc')->get();
        $categories = StaffCategory::all();
        $branches = Branch::where('status',1)->get();
        return view('staffs.availabilty.allAvailability',compact('staffs','categories','branches'));
     }
    
    public function getStaffById(Request $request) {
        $staff = Staff::where('staffId', $request->id)->first();;
        return view('staffs.availabilty.availabilty', compact('staff'));
    }

    public function availabilityView(Request $request) {


        $startDate = Carbon::createFromFormat('Y-m-d',$request->startDate)->startOfWeek();
        $staffId = $request->staffId;

        $dbFrom = clone $startDate;
        $dbFrom->subDay();
        $dbTo = clone $startDate;
        $dbTo->addDay(28);

        $dbExist = StaffAvailability::whereBetween('date',[$dbFrom->format('Y-m-d'),$dbTo->format('Y-m-d')])
                                    ->where('staffId',$staffId)
                                    ->get()->toArray();
        $dbExist = array_column($dbExist,null,'date');

        $calenderData =[];
        $currDay = clone $startDate;
        for($i=1;$i<=28;$i++){


            $prevDay = clone $currDay;
            $prevDay->subDay();
            $nextDay = clone $currDay;
            $nextDay->addDay();

            $early = ['c'=>0, 'd'=>0];
            $late = ['c'=>0, 'd'=>0];
            $night = ['c'=>0, 'd'=>0];
            $absent = ['c'=>0, 'd'=>0];

            $currDayKey = array_search($currDay->format('Y-m-d'), array_column($dbExist, 'date', 'date'));
            if($currDayKey){
                $currDayData = $dbExist[$currDayKey];

                $early['c'] = $currDayData['early'];
                $late['c'] = $currDayData['late'];
                $night['c'] = $currDayData['night'];
                $absent['c'] = $currDayData['absent'];

                if($absent['c']){
                    $early['d'] = 1;
                    $late['d'] = 1;
                    $night['d'] = 1;
                }else if($early['c']||$late['c']||$night['c']){
                    $absent['d']=1;
                }

                if(($early['c']&&$night['c'])||$night['c']){
                    $late['d'] = 1;
                }
                if($late['c']){
                    $night['d'] = 1;
                }
            }
            $prevDayKey = array_search($prevDay->format('Y-m-d'), array_column($dbExist, 'date', 'date'));
            $isPrevNight = false;
            if($prevDayKey){
                $prevDayData = $dbExist[$prevDayKey];
                if($prevDayData['night']){
                    $early['d'] = 1;
                    $isPrevNight = true;
                }
            }
            $nextDayKey = array_search($nextDay->format('Y-m-d'), array_column($dbExist, 'date', 'date'));
            $isNextEarly = false;
            if($nextDayKey){
                $nextDayData = $dbExist[$nextDayKey];
                if($nextDayData['early']){
                    $night['d'] = 1;
                    $isNextEarly = true;
                }
            }

            $calenderData[$currDay->format('Y-m-d')] = [
                'date'=>clone $currDay,
                'isNextEarly'=> (($i==28)?$isNextEarly:false),
                'isPrevNight'=> (($i==1)?$isPrevNight:false),
                'e'=>$early,
                'l'=>$late,
                'n'=>$night,
                'a'=>$absent,

            ];

            $currDay->addDay();
        }

        $prevViewDay = clone $startDate;
        $prevViewDay->subDay(28);
        $nextViewDay = clone $startDate;
        $nextViewDay->addDay(28);

        $returnData['content'] = (string) view('staffs.availabilty.availabilityEntry', compact('calenderData'));
        $returnData['prev'] = $prevViewDay->format('Y-m-d');
        $returnData['next'] = $nextViewDay->format('Y-m-d');
        return $returnData;
     }

     public function availabiltyPost(Request $request) {

        $staffAvailability = StaffAvailability::firstOrCreate(
            ['date'=>$request->date,'staffId'=>$request->staffId],
            ['date'=>$request->date,'staffId'=>$request->staffId]
        );

        switch ($request->type) {
            case 'early':
                $staffAvailability->early = $request->data;
                break;
            case 'late':
                $staffAvailability->late = $request->data;
                break;
            case 'night':
                $staffAvailability->night = $request->data;
                break;
            case 'absent':
                $staffAvailability->absent = $request->data;
                if($request->data==1){
                    $staffAvailability->early = 0;
                    $staffAvailability->late = 0;
                    $staffAvailability->night = 0;
                }
                break;
            case 'both':
                $staffAvailability->early = $request->data;
                $staffAvailability->late = $request->data;
                $staffAvailability->night = $request->data;
                break;
        }

        $status = $staffAvailability->save();

        return ['status'=>$status];
     }

     public function availabilityReport($categoryId=null)
     {
        if(isset($categoryId)){
            $categoryId = $categoryId;
        }

        $categories = StaffCategory::all();
        $startDate = Carbon::now();//createFromFormat('Y-m-d',$request->startDate)->startOfWeek();
        $dayCount = 18;

        $dbFrom = clone $startDate;
        $dbTo = $startDate->copy()->addDay($dayCount-1);
        $dbExist = StaffAvailability::with('staff')
        ->whereBetween('date',[$dbFrom->format('Y-m-d'),$dbTo->format('Y-m-d')])
                                    ->get();

        $query = Staff::where('status', 1);
        if(isset($categoryId)){
          $query->where('categoryId',$categoryId);
        }
        $staffList = $query->orderBy('forname')->get();

        $currDay = clone $startDate;
        $dateArray = [];
        for($i=1;$i<=$dayCount;$i++){
            $dateArray[$currDay->format('Y-m-d')]['value'] = '-';
            $dateArray[$currDay->format('Y-m-d')]['date'] = $currDay->copy();
            $currDay->addDay();
        }

        $reportData = [];
        foreach ($staffList as $staff) {
            if(!isset($reportData[$staff->staffId])){
                $reportData[$staff->staffId]['data'] = $dateArray;
                $reportData[$staff->staffId]['staff'] = $staff->forname." ".$staff->surname."(".$staff->category->name.")";
            }
        }

        foreach ($dbExist as $row) {
            if(isset($reportData[$row->staffId])){
                $state = "";
                if($row->absent==1){
                    $state = '<span class="red"><strong>A</strong></span>';
                }else if(($row->early==1)&&($row->late==1)){
                    $state = 'EL';
                }else if(($row->early==1)&&($row->night==1)){
                    $state = 'EN';
                }else if($row->early==1){
                    $state = 'E';
                }else if($row->late==1){
                    $state = 'L';
                }else  if($row->night==1){
                    $state .= 'N';
                }
                $reportData[$row->staffId]['data'][$row->date]['value'] = (empty($state))?"-":$state;
            }
        }

        return view('staffs.availabilty.report', compact('reportData','dateArray','categories','categoryId'));

     }
}
