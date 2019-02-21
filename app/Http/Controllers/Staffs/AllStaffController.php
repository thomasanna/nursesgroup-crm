<?php

namespace App\Http\Controllers\Staffs;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Notification;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\Booking;
use App\Models\ClientUnitSchedule;
use App\Models\Branch;
use App\Models\StaffAvailability;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPlainSMS as SendPlainSMSEvent;
use Carbon\Carbon;
use URL;



class AllStaffController
{
      public function indexAll(){
        $staffs = Staff::where('status',1)->orderBy('forname','asc')->get();
        $categories = StaffCategory::all();
        $branches = Branch::where('status',1)->get();
        return view('staffs.index_all',compact('staffs','categories','branches'));
      }

    public function dataStaffAll(Request $req){
      $query = Staff::with('category','band','branch','zone')
              ->where('status',1)
              ->orderBy('performancePoint','DESC')->orderBy('forname','asc');
      return Datatables::of($query)
        ->addIndexColumn()
        ->editColumn('email',function($staff){
          $html ="<a href='mailto:$staff->email'>".$staff->email."<a>";
          return $html;
        })
        ->editColumn('forname',function($staff){
          $html ="<a href='".route('staffs.note.profile',encrypt($staff->staffId))."' class='btn btn-primary'>".$staff->forname." ".$staff->surname."<a>";
          return $html;
        })
        ->filterColumn('forname',function($staff,$keyword){
          $staff->where('staffId', $keyword);
        })
        ->filterColumn('category.name',function($staff,$keyword){
          $staff->where('categoryId', $keyword);
        })
        ->filterColumn('branch.name',function($staff,$keyword){
          $staff->where('branchId', $keyword);
        })
        ->editColumn('mobile',function($staff){
          $html =$staff->mobile;
          return $html;
        })
        ->editColumn('log',function($staff){
          $html ="<button staffname='".$staff->forname." ".$staff->surname."' category='".$staff->category->name."' staffid='".$staff->staffId."' data-toggle='modal' class='btn btn-danger btn-xs mrs m-r-10 openLogModal'>Log Book</button>";
          return $html;
        })
        ->editColumn('logStatus',function($staff){
          $diffInDays = Carbon::parse($staff->log->created_at)->diffInDays(Carbon::now());
          $html ="<button class='btn btn-primary btn-xs mrs m-r-10'>No Logs</button>";
          if($diffInDays <= 15){
            switch ($staff->log->priority) {
              case '1':
                $html ="<button class='btn btn-success btn-xs mrs m-r-10'>FollowUp</button>";
                break;
              case '2':
                $html ="<button class='btn btn-danger btn-xs mrs m-r-10'>Urgent</button>";
                break;
              case '3':
                $html ="<button class='btn btn-warning btn-xs mrs m-r-10'>Info</button>";
                break;
            }
          }
          return $html;
        })
        ->editColumn('avaiblity',function($staff){
          $today = date('Y-m-d');
          $tomorrow = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
          $dayAftrTmrow = date('Y-m-d', strtotime('+2 days', strtotime(date('Y-m-d'))));
          $dayAftrTmrowPone = date('Y-m-d', strtotime('+3 days', strtotime(date('Y-m-d'))));
          $dayAftrTmrowPtwo = date('Y-m-d', strtotime('+4 days', strtotime(date('Y-m-d'))));

          $availability = StaffAvailability::where('staffId',$staff->staffId)
              ->whereIn('date',[$today,$tomorrow,$dayAftrTmrow,$dayAftrTmrowPone,$dayAftrTmrowPtwo])->get();
          if($availability->contains('date',$today) ==1){
            $html ="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$today)."</button>";
          }else{
            $html ="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$tomorrow) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$tomorrow)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$dayAftrTmrow) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$dayAftrTmrow)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$dayAftrTmrowPone) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$dayAftrTmrowPone)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$dayAftrTmrowPtwo) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$dayAftrTmrowPtwo)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          return $html;
        })
        ->editColumn('curentSts',function($staff){
          $dayBeforeYestrdy = date('Y-m-d', strtotime('-2 days', strtotime(date('Y-m-d'))));
          $yesterdy = date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))));
          $today = date('Y-m-d');
          $tomorrow = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
          $dayAftrTmrow = date('Y-m-d', strtotime('+2 days', strtotime(date('Y-m-d'))));

          $pastShifts = Booking::with(['shift'])->where('staffId',$staff->staffId)->whereIn('date',[$dayBeforeYestrdy,$yesterdy,$today,$tomorrow,$dayAftrTmrow])->where('unitStatus',4)->get();
          if($pastShifts->contains('date', $dayBeforeYestrdy)==1){
            $html ="<button class='btn btn-success boxBtn btn-xs mrs m-r-10' date='".$dayBeforeYestrdy."'>".$this->getStaffShiftsWithDate($pastShifts,$dayBeforeYestrdy)."</button>";
          }else{
            $html ="<button class='btn btn-warning boxBtn btn-xs mrs m-r-10' date='".$dayBeforeYestrdy."'>&nbspA&nbsp</button>";
          }
          if($pastShifts->contains('date', $yesterdy)==1){
            $html .="<button class='btn btn-success boxBtn btn-xs mrs m-r-10' date='".$yesterdy."'>".$this->getStaffShiftsWithDate($pastShifts,$yesterdy)."</button>";
          }else{
            $html .="<button class='btn btn-warning boxBtn btn-xs mrs m-r-10' date='".$yesterdy."'>&nbspA&nbsp</button>";
          }
          if($pastShifts->contains('date', $today)==1){
            $html .="<button class='btn btn-success boxBtn btn-xs mrs m-r-10' date='".$today."'>".$this->getStaffShiftsWithDate($pastShifts,$today)."</button>";
          }else{
            $html .="<button class='btn btn-primary boxBtn btn-xs mrs m-r-10' date='".$today."'>&nbspA&nbsp</button>";
          }
          if($pastShifts->contains('date', $tomorrow) ==1){
            $html .="<button class='btn btn-success boxBtn btn-xs mrs m-r-10' date='".$tomorrow."'>".$this->getStaffShiftsWithDate($pastShifts,$tomorrow)."</button>";
          }else{
            $html .="<button class='btn btn-warning boxBtn btn-xs mrs m-r-10' date='".$tomorrow."'>&nbspA&nbsp</button>";
          }
          if($pastShifts->contains('date', $dayAftrTmrow) ==1){
            $html .="<button class='btn btn-success boxBtn btn-xs mrs m-r-10' date='".$dayAftrTmrow."'>".$this->getStaffShiftsWithDate($pastShifts,$dayAftrTmrow)."</button>";
          }else{
            $html .="<button class='btn btn-warning boxBtn btn-xs mrs m-r-10' date='".$dayAftrTmrow."'>&nbspA&nbsp</button>";
          }
          return $html;
        })
        ->editColumn('actions',function($staff){

          $html = "";
          $html .= "<a href='#' data-staff='".encrypt($staff->staffId)."'";
          $html .= "name='$staff->forname $staff->surname'";
          $html .= "mobile='$staff->mobile'";
          $html .="class='btn smsModalbtn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i>SMS</a>";
          return $html;
        })
        ->editColumn('hw',function($staff){
          $startDate = Carbon::createFromFormat('Y-m-d',date('Y-m-d'));
          $currWeek = $startDate->copy()->startOfWeek();
          $dbExist = Booking::whereBetween('date',[$currWeek ,date('Y-m-d')])->where('staffId',$staff->staffId)->select('date','shiftId','unitId','categoryId')->groupBy('date')->get()->toArray();
            $hourCount = 0;
            foreach ($dbExist as $key => $row) {
              $unitSchedule = ClientUnitSchedule::where('clientUnitId',$row['unitId'])->where('staffCategoryId',$row['categoryId'])->where('shiftId',$row['shiftId'])->first();
              if(!empty($unitSchedule)){
                $hourCount +=  $unitSchedule->totalHoursUnit; 
              }
          }    
          return $hourCount;
        })->make(true);
    }

  

    public function availableDataStaffAll(Request $req){
      $query = Staff::with('category','band','branch','zone')
              ->where('status',1)
              ->orderBy('performancePoint','DESC')->orderBy('forname','asc');
      return Datatables::of($query)
        ->addIndexColumn()
        ->editColumn('forname',function($staff){
          $html ="<a href='".route('staffs.note.profile',encrypt($staff->staffId))."' class='btn btn-primary'>".$staff->forname." ".$staff->surname."<a>";
          return $html;
        })
        ->filterColumn('forname',function($staff,$keyword){
          $staff->where('staffId', $keyword);
        })
        ->filterColumn('category.name',function($staff,$keyword){
          $staff->where('categoryId', $keyword);
        })
        ->filterColumn('branch.name',function($staff,$keyword){
          $staff->where('branchId', $keyword);
        })
        ->editColumn('mobile',function($staff){
          $html =$staff->mobile;
          return $html;
        })
        ->editColumn('avaiblity',function($staff){
          $today = date('Y-m-d');
          $tomorrow = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
          $dayAftrTmrow = date('Y-m-d', strtotime('+2 days', strtotime(date('Y-m-d'))));
          $dayAftrTmrowPone = date('Y-m-d', strtotime('+3 days', strtotime(date('Y-m-d'))));
          $dayAftrTmrowPtwo = date('Y-m-d', strtotime('+4 days', strtotime(date('Y-m-d'))));

          $availability = StaffAvailability::where('staffId',$staff->staffId)
              ->whereIn('date',[$today,$tomorrow,$dayAftrTmrow,$dayAftrTmrowPone,$dayAftrTmrowPtwo])->get();
          if($availability->contains('date',$today) ==1){
            $html ="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$today)."</button>";
          }else{
            $html ="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$tomorrow) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$tomorrow)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$dayAftrTmrow) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$dayAftrTmrow)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$dayAftrTmrowPone) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$dayAftrTmrowPone)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          if($availability->contains('date',$dayAftrTmrowPtwo) ==1){
            $html .="<button class='btn btn-success btn-xs mrs m-r-10'>".$this->getStaffAvailabilty($availability,$dayAftrTmrowPtwo)."</button>";
          }else{
            $html .="<button class='btn btn-warning btn-xs mrs m-r-10'>A</button>";
          }
          return $html;
        })
        ->editColumn('sms',function($staff){

          $html = "";
          $html .= "<a href='#' data-staff='".encrypt($staff->staffId)."'";
          $html .= "name='$staff->forname $staff->surname'";
          $html .= "mobile='$staff->mobile'";
          $html .="class='btn smsModalbtn btn-success btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i>SMS</a>";
          return $html;
        })
        ->editColumn('actions',function($staff){

          $html = "";
          $html .= "<a href='".'staff-availabilty/'.$staff->staffId."'";
          $html .="class='btn btn-success btn-xs' style='margin: 0 5px;''>Record</a>";
          return $html;
        })
        ->make(true);
    }

    public function getStaffShiftsWithDate($shifts,$day){
      $shiftId = 0;
      for ($i=0; $i < count($shifts); $i++) {
        if ($shifts[$i]->date==$day) {
          $shiftId = $shifts[$i]->shiftId;
        }
      }
      switch ($shiftId) {
        case 1:
            $shift = "&nbspE&nbsp";
          break;
        case 2:
            $shift = '&nbspL&nbsp';
          break;
        case 3:
            $shift = "EL";
          break;
        case 4:
          $shift = "&nbspN&nbsp&nbsp";
          break;
      }
      return $shift;
    }


  public function getStaffAvailabilty($availabilty,$day){
      $ifSingle = 0;
      $html ="";
      for ($i=0; $i < count($availabilty); $i++) {
        if ($availabilty[$i]->date==$day) {
          if($availabilty[$i]->early==1){
            $html .="E";
            $ifSingle = 1;
          }
          if($availabilty[$i]->late==1){
            if($ifSingle==1){ $html .=",";}
            $html .="L";
          }
          if($availabilty[$i]->night==1){
            if($ifSingle==1){ $html .=",";}
            $html .="N";
          }
        }
      }
      return $html;
    }

    public function sendSms(Request $req){
      $staff = Staff::find(decrypt($req->staffId));
      $staff->message = $req->message;
      $emailAddress = "0044".substr($staff->mobile, 1).'@mail.mightytext.net';
      $emailAddress = str_replace(" ","",$emailAddress);
      $staff->email = $emailAddress;
      Mail::to($emailAddress)->queue(new SendPlainSMSEvent($req->message));
      session()->flash('message', 'Succesfully Send SMS !!');
      return ['status'=>true,'redirect'=>route('staffs.home.all')];
    }

    public function noteProfile($id){
      $staff = Staff::with(['category'])->find(decrypt($id));
      return view('staffs.note_profile',compact('staff'));
    }

}
