<?php

namespace App\Http\Controllers\Bookings;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Notifications\SendPlainSMS;
use App\Models\Booking;
use App\Models\BookingAlertLog;
use App\Models\Staff;
use App\Models\ClientUnitSchedule;
use App\Models\StaffAvailability;
use App\Models\BookingPriorityStaff;
use App\Models\ClientUnitInformLog;
use App\Models\StaffLog;
use App\Models\BookingLog;
use Illuminate\Support\Facades\Notification;
use Mail;
use Auth;
use Log;
use Cache;
use Session;
use Carbon\Carbon;
use App\Mail\SendPlainSMS as SendPlainSMSEvent;

class SearchController
{
    public function allocateStaff($bookingId,$page,$searchKeyword=""){
      $expiresAt = now()->addMinutes(1440);
      if (Cache::has('booking_'.decrypt($bookingId))) { $booking = Cache::get('booking_'.decrypt($bookingId)); }else{ $booking = Booking::find(decrypt($bookingId)); Cache::put('booking_'.decrypt($bookingId), $booking,$expiresAt);}

      $times = ClientUnitSchedule::where('clientUnitId',$booking->unit->clientUnitId)
                                    ->where('staffCategoryId',$booking->category->categoryId)
                                    ->where('shiftId',$booking->shiftId)
                                    ->first();
      if(!empty($times)){
        $booking->startTime = $times->startTime;
        $booking->endTime = $times->endTime;
      }else{
        $booking->startTime = '';
        $booking->endTime = '';
      }

      $bookingDate = Carbon::createFromFormat('Y-m-d', $booking->date);
      $booking->isWeekend = $bookingDate->isWeekend();
      $shiftVowel = "a";
      if(strtolower($booking->shift->name)=="early"){
        $shiftVowel = "an";
      }

      $alertSms = 'Hi, Please could you cover '.$shiftVowel.' '.$booking->shift->name.' Shift ('.date('h:i A',strtotime($booking->startTime)). '-'.date('h:i A',strtotime($booking->endTime)).')';
      $alertSms.= ' in '.$booking->unit->alias.' on '.date('d/m/Y,D',strtotime($booking->date)).'. Waiting for your reply, Thank You, NursesGroup';


      $staffIds = !empty(session()->get('checkState'))?session()->get('checkState'):[];
      $selectedStaffs = Staff::select('forname','surname')->whereIn('staffId',$staffIds)->get();

      return view('bookings.searchStaff',compact('page','booking','alertSms','selectedStaffs','searchKeyword'));
    }

    public function dataAvaialableStaff($bookingId,Request $req){
      $expiresAt = now()->addMinutes(1440);
      if (Cache::has('booking_'.decrypt($bookingId))) { $booking = Cache::get('booking_'.decrypt($bookingId)); }
      $bookCategoryId = $booking->categoryId;
      $bookingId = decrypt($bookingId);
      if (Cache::has('bookedStaffs')) { $bookedStaffs = Cache::get('bookedStaffs'); }else{
        $bookedStaffs = Booking::where("date",$booking->date)->where("staffStatus",3)->pluck('staffId')->toArray();
        Cache::put('bookedStaffs', $bookedStaffs,$expiresAt);
      }

      $staff = Staff::with(['category','log'=>function ($query){
        $query->orderBy('created_at', 'desc')->take(1);
      }])
      ->select('staffs.*','staff_logs.priority as logPriority')
      ->join('staff_avaiablity', 'staff_avaiablity.staffId','staffs.staffId')
      ->join('client_unit_zones', 'client_unit_zones.zoneId','staffs.zoneId')
      // ->leftJoin('booking_alert_logs',function ($query) use($bookingId)
      //     {
      //       $query->on("booking_alert_logs.staffId","staffs.staffId")
      //             ->where("booking_alert_logs.bookingId",$bookingId);
      //     })
      ->leftJoin('staff_logs',function ($query){
        $query->on("staff_logs.staffId","staffs.staffId")
              ->orderBy('staffLogId','asc');
      })
      ->join('staff_preferred_units', 'staff_preferred_units.staffId','staffs.staffId')
      ->orderBy('staffs.performancePoint','DESC')
      ->orderBy('staffs.forname','ASC')
      ->where('staffs.status',1)
      ->whereNotIn('staffs.staffId',$bookedStaffs)
      ->where('staff_avaiablity.date',$booking->date)
      ->where('staffs.categoryId',$bookCategoryId)
      ->groupBy('staffs.staffId');

      $dataTable= Datatables::of($staff);

      return $dataTable->addIndexColumn()
      ->addColumn('checkbox',function($staff){
        $checkState = session()->get('checkState');
        $checked = "";
        if($checkState){
          if(in_array($staff->staffId,$checkState)){
            $checked = "checked='checked'";
          }
        }
        return "<input type='checkbox' ".$checked." class='icheckBox' value='$staff->staffId' />";
      })
      ->editColumn('forname',function($staff){
        if($staff->forname){ return $staff->forname." ".$staff->surname; }
      })
      ->editColumn('sms',function($staff){
        if(!empty($staff->bookingAlertLogId)){
          return "<span class='label label-success'>Sent</span>";
        }else{
          return "<span class='label label-danger'>Not Sent</span>";
        }
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
        if (Cache::has('staff_availability_'.$staff->staffId)) {  $availability = Cache::get('staff_availability_'.$staff->staffId); }else{
          $availability = StaffAvailability::where('staffId',$staff->staffId)
            ->whereIn('date',[$today,$tomorrow,$dayAftrTmrow,$dayAftrTmrowPone,$dayAftrTmrowPtwo])->get();
          Cache::put('staff_availability_'.$staff->staffId, $availability,10);
        }
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
      ->editColumn('actions',function($staff) use($booking){
          $html = "<a href='javascript:void(0)' data-staff-id=".encrypt($staff->staffId)." class='btn btn-primary btn-xs mrs assign-btn' style='margin: 0 5px;'>Assign</a>";
          $html .= "<a href='javascript:void(0)' data-staff-id=".encrypt($staff->staffId)." date=".$booking->date." action=".route('booking.make.unavailable.staff');
          $html .= " class='btn btn-danger btn-xs mrs unavailBtn' style='margin: 0 5px;'>Unavailable</a>";
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
        })
      ->make(true);
    }

    public function dataPermanentStaff($bookingId,Request $req){
      if (Cache::has('booking_'.decrypt($bookingId))) { $booking = Cache::get('booking_'.decrypt($bookingId)); }else{ $booking = Booking::find(decrypt($bookingId)); Cache::put('booking_'.decrypt($bookingId), $booking,$expiresAt);}
      $bookCategoryId = $booking->categoryId;
      $bookingId = decrypt($bookingId);
      if (Cache::has('bookedStaffs')) { $bookedStaffs = Cache::get('bookedStaffs'); }else{
        $bookedStaffs = Booking::where("date",$booking->date)->where("staffStatus",3)->pluck('staffId')->toArray();
        Cache::put('bookedStaffs', $bookedStaffs,$expiresAt);
      }
      $staff = Staff::with(['category','log'=>function ($query){
        $query->orderBy('created_at', 'desc')->take(1);
      }])
      ->select('staffs.*','staff_logs.priority as logPriority')
      // ->leftJoin('booking_alert_logs',function ($query) use($bookingId){
      //   $query->on("booking_alert_logs.staffId","staffs.staffId")
      //         ->where("booking_alert_logs.bookingId",$bookingId);
      // })
      ->leftJoin('staff_logs',function ($query){
        $query->on("staff_logs.staffId","staffs.staffId")
              ->orderBy('staffLogId','asc');
      })
      ->join('staff_preferred_units', 'staff_preferred_units.staffId','staffs.staffId')
      ->orderBy('staffs.performancePoint','DESC')
      ->orderBy('staffs.forname','ASC')
      ->where('staffs.status',1)
      ->where('staffs.isPermenent',1)  // FILTER ONLY PERMANENT STAFFS
      ->whereNotIn('staffs.staffId',$bookedStaffs)
      ->where('staffs.categoryId',$bookCategoryId)
      ->groupBy('staffs.staffId');

      $dataTable= Datatables::of($staff);

      return $dataTable->addIndexColumn()
      ->addColumn('checkbox',function($staff){
        $checkState = session()->get('checkState');
        $checked = "";
        if($checkState){
          if(in_array($staff->staffId,$checkState)){
            $checked = "checked='checked'";
          }
        }
        return "<input type='checkbox' ".$checked." class='icheckBox' value='$staff->staffId' />";
      })
      ->editColumn('forname',function($staff){
        if($staff->forname){ return $staff->forname." ".$staff->surname; }
      })
      ->editColumn('sms',function($staff){
        if(!empty($staff->bookingAlertLogId)){
          return "<span class='label label-success'>Sent</span>";
        }else{
          return "<span class='label label-danger'>Not Sent</span>";
        }
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
      ->editColumn('actions',function($staff) use($booking){
          $html = "<a href='javascript:void(0)' data-staff-id=".encrypt($staff->staffId)." class='btn btn-primary btn-xs mrs assign-btn' style='margin: 0 5px;'>Assign</a>";
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
        })
      ->make(true);
    }

    public function dataPriorityStaff($bookingId,Request $req){
      $expiresAt = now()->addMinutes(1440);
      if (Cache::has('booking_'.decrypt($bookingId))) { $booking = Cache::get('booking_'.decrypt($bookingId)); }else{ $booking = Booking::find(decrypt($bookingId)); Cache::put('booking_'.decrypt($bookingId), $booking,$expiresAt);}
      $bookCategoryId = $booking->categoryId;
      $bookingId = decrypt($bookingId);
      if (Cache::has('prior_staffs_'.$booking->date)) { $priorStaffs = Cache::get('prior_staffs_'.$booking->date); }else{
        $priorStaffs = BookingPriorityStaff::where("date",$booking->date)->pluck('staffId')->toArray();
        Cache::put('prior_staffs_'.$booking->date, $priorStaffs,$expiresAt);
      }
      $staff = Staff::with(['category','log'=>function ($query){
        $query->orderBy('created_at', 'desc')->take(1);
      }])
        ->select('staffs.*','staff_logs.priority as logPriority')
        ->leftJoin('staff_logs',function ($query){
          $query->on("staff_logs.staffId","staffs.staffId")
                ->orderBy('staffLogId','asc');
        })
        ->orderBy('staffs.performancePoint','DESC')
        ->orderBy('staffs.forname','ASC')
        ->where('status',1)
        ->whereIn('staffs.staffId',$priorStaffs)
        ->where('staffs.categoryId',$bookCategoryId)
        ->groupBy('staffs.staffId');

      $dataTable= Datatables::of($staff);

      return $dataTable->addIndexColumn()
        ->addColumn('checkbox',function($staff){
          $checkState = session()->get('checkState');
          $checked = "";
          if($checkState){
            if(in_array($staff->staffId,$checkState)){
              $checked = "checked='checked'";
            }
          }
          return "<input type='checkbox' ".$checked." class='icheckBox' value='$staff->staffId' />";
        })
        ->editColumn('forname',function($staff){
          if($staff->forname){ return $staff->forname." ".$staff->surname; }
        })
        ->editColumn('sms',function($staff){
          if(!empty($staff->bookingAlertLogId)){
            return "<span class='label label-success'>Sent</span>";
          }else{
            return "<span class='label label-danger'>Not Sent</span>";
          }
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
        ->editColumn('actions',function($staff) use($booking){
            $html = "<a href='javascript:void(0)' data-staff-id=".encrypt($staff->staffId)." class='btn btn-primary btn-xs mrs assign-btn' style='margin: 0 5px;'>Assign</a>";
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
        })
        ->make(true);
    }

    public function dataPrevWorkedStaff($bookingId,Request $req){
      $expiresAt = now()->addMinutes(1440);
      if (Cache::has('booking_'.decrypt($bookingId))) {
        $booking = Cache::get('booking_'.decrypt($bookingId));
      }else{
        $booking = Booking::find(decrypt($bookingId)); Cache::put('booking_'.decrypt($bookingId), $booking,$expiresAt);
      }
      $bookCategoryId = $booking->categoryId;
      $bookingId = decrypt($bookingId);
      $staff = Staff::with(['category','log'=>function ($query){
        $query->orderBy('created_at', 'desc')->take(1);
      }])
        ->select('staffs.*','staff_logs.priority as logPriority')
        ->leftJoin('staff_logs',function ($query){
          $query->on("staff_logs.staffId","staffs.staffId")
                ->orderBy('staffLogId','asc');
        })
        ->Join('bookings', 'bookings.staffId','staffs.staffId')
        ->orderBy('staffs.performancePoint','DESC')
        ->orderBy('staffs.forname','ASC')
        ->where('staffs.status',1)
        ->whereDate('bookings.date','<',date('Y-m-d'))
        ->where('bookings.unitId',$booking->unitId)
        ->where('staffs.categoryId',$bookCategoryId)
        ->where('bookings.shiftId',$booking->shiftId)
        ->groupBy('staffs.staffId');

      $dataTable= Datatables::of($staff);

      return $dataTable->addIndexColumn()
      ->addColumn('checkbox',function($staff){
        $checkState = session()->get('checkState');
        $checked = "";
        if($checkState){
          if(in_array($staff->staffId,$checkState)){
            $checked = "checked='checked'";
          }
        }
        return "<input type='checkbox' ".$checked." class='icheckBox' value='$staff->staffId' />";
      })
      ->editColumn('forname',function($staff){
        if($staff->forname){ return $staff->forname." ".$staff->surname; }
      })
      ->editColumn('sms',function($staff){
        if(!empty($staff->bookingAlertLogId)){
          return "<span class='label label-success'>Sent</span>";
        }else{
          return "<span class='label label-danger'>Not Sent</span>";
        }
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
      ->editColumn('actions',function($staff) use($booking){
        if($this->checkIfStaffIsAssigned($staff->staffId,$booking)==0 && $staff->staffId != $booking->staffId){
          $html = "<a href='javascript:void(0)' data-staff-id=".encrypt($staff->staffId)." class='btn btn-primary btn-xs mrs assign-btn' style='margin: 0 5px;'>Assign</a>";
        }else{
          $html = "<span class='btn btn-xs mrs btn-success' style='margin: 0 5px;'>Assigned</span>";
        }
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
        })
      ->make(true);
    }

    public function dataStaffInZone($bookingId,Request $req){
      $expiresAt = now()->addMinutes(1440);
      if (Cache::has('booking_'.decrypt($bookingId))) {
        $booking = Cache::get('booking_'.decrypt($bookingId));
      }else{
        $booking = Booking::find(decrypt($bookingId)); Cache::put('booking_'.decrypt($bookingId), $booking,$expiresAt);
      }
      $bookCategoryId = $booking->categoryId;
      $bookingId = decrypt($bookingId);
      $staff = Staff::with(['category','log'=>function ($query){
        $query->orderBy('created_at', 'desc')->take(1);
      }])
      ->select('staffs.*')
      // ->leftJoin('booking_alert_logs',function ($query) use($bookingId){
      //       $query->on("booking_alert_logs.staffId","staffs.staffId")
      //             ->where("booking_alert_logs.bookingId",$bookingId);
      // })
      ->Join('staff_preferred_units', 'staff_preferred_units.staffId','staffs.staffId')
      ->orderBy('staffs.performancePoint','DESC')
      ->orderBy('staffs.forname','ASC')
      ->where('staffs.status',1)
      ->where('categoryId',$bookCategoryId)
      ->groupBy('staffs.staffId');
      $dataTable= Datatables::of($staff);

      return $dataTable->addIndexColumn()
      ->addColumn('checkbox',function($staff){
        $checkState = session()->get('checkState');
        $checked = "";
        if($checkState){
          if(in_array($staff->staffId,$checkState)){
            $checked = "checked='checked'";
          }
        }
        return "<input type='checkbox' ".$checked." class='icheckBox' value='$staff->staffId' />";
      })
      ->editColumn('forname',function($staff){
        if($staff->forname){ return $staff->forname." ".$staff->surname; }
      })
      ->editColumn('sms',function($staff){
        if(!empty($staff->bookingAlertLogId)){
          return "<span class='label label-success'>Sent</span>";
        }else{
          return "<span class='label label-danger'>Not Sent</span>";
        }
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
        return "<button class='btn btn-primary btn-xs mrs m-r-10 openAvailablty' staff='".$staff->staffId."'>Availabilty</button>";
      })
      ->editColumn('curentSts',function($staff){
        return "<button class='btn btn-primary btn-xs mrs m-r-10 openHistry' staff='".$staff->staffId."'>History</button>";
      })
      ->editColumn('actions',function($staff) use($booking){

        if($this->checkIfStaffIsAssigned($staff->staffId,$booking)==0 && $staff->staffId != $booking->staffId){
          $html = "<a href='javascript:void(0)' data-staff-id=".encrypt($staff->staffId)." class='btn btn-primary btn-xs mrs assign-btn' style='margin: 0 5px;'>Assign</a>";
        }else{
          $html = "<span class='btn btn-xs mrs btn-success' style='margin: 0 5px;'>Assigned</span>";
        }
        return $html;

      })
      ->editColumn('hw',function($staff){
        $startDate = Carbon::createFromFormat('Y-m-d',date('Y-m-d'));
        $currWeek = $startDate->copy()->startOfWeek();
        $dbExist = Booking::whereBetween('date',[$currWeek ,date('Y-m-d')])
                  ->where('staffId',$staff->staffId)
                  ->select('date','shiftId','unitId','categoryId')
                  ->groupBy('date')->get()->toArray();
        $hourCount = 0;
        foreach ($dbExist as $key => $row) {
          $unitSchedule = ClientUnitSchedule::where('clientUnitId',$row['unitId'])->where('staffCategoryId',$row['categoryId'])->where('shiftId',$row['shiftId'])->first();
          if(!empty($unitSchedule)){
            $hourCount +=  $unitSchedule->totalHoursUnit;
          }
        }
        return $hourCount;
      })
      ->make(true);
    }

    public function changestatus(Request $req){
      $booking = Booking::find(decrypt($req->bookingId));
      $logContentStaff = "";
      if($req->staffStatus == 2){
        $staffAllocateStatus = 3;
        $logContent = "Potential Staff ";
      }else{
        $staffAllocateStatus = 2; /// MAKE CONFIRM
        $logContent = "Confirm Staff ";
      }

      if($this->checkIfStaffIsAssigned($booking->staffId,$booking)==0 || $staffAllocateStatus ==3){ // not exist in other booking criteria
        $booking->staffAllocateStatus =  $staffAllocateStatus;
        $booking->staffStatus =$req->staffStatus;
        $booking->confirmedBy =Auth::guard('admin')->user()->adminId;
        $booking->confirmedAt =date('Y-m-d H:i:s');
        $booking->save();

        Cache::forget('bookedStaffs');
        Cache::forget('booking_'.$booking->bookingId);

        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' =>$logContent."<strong>".$booking->staff->forname." ".$booking->staff->surname."</strong> <span class='logHgt'>Assigned.</span>",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        BookingPriorityStaff::where('staffId',$booking->staffId)->whereDate('date',$booking->date)->delete();
        Cache::forget('prior_staffs_'.$booking->date);

        return redirect(route('booking.allocate.staff',[$req->bookingId,$req->page,$req->search]))->with('message','Successfuly Assigned a staff');
      }else{
        return redirect(route('booking.allocate.staff',[$req->bookingId,$req->page,$req->search]))->with('message','Error in staff Confirmation, Staff Confirmed in another booking.');
      }
    }

    public function assignStaff(Request $request){
      $booking = Booking::find(decrypt($request->bookingId));
      $booking->staffId =  decrypt($request->staffId);
      $booking->staffAllocateStatus =  3;
      $booking->staffStatus =  2;
      $booking->save();

      BookingPriorityStaff::where('staffId',$booking->staffId)->whereDate('date',$booking->date)->delete();
      Cache::forget('prior_staffs_'.$booking->date);
      Cache::forget('booking_'.$booking->bookingId);

      return ['url'=>route('booking.allocate.staff',[$request->bookingId,$request->page,$request->search])];
    }

    public function searchUpdate(Request $request){
      $booking = Booking::find(decrypt($request->bookingId));
      $booking->searchUpdate = $request->updateInfo;
      $booking->save();

      BookingLog::create([
        'bookingId' =>$booking->bookingId,
        'content' => "Search Update <span class='logHgt'>Saved</span> ".$request->updateInfo,
        'author' =>Auth::guard('admin')->user()->adminId,
      ]);
    }

    public function storeCheckedState(Request $req){
      $checkState = session()->get('checkState');
      if($req->type==0){
        $key = array_search($req->staff, $checkState);
        $index = "checkState.$key";
        session()->forget($index);
      }elseif($req->type==1){
        $checkStat[] =  $req->staff;
        session()->push('checkState', $req->staff);
      }elseif($req->type==2){
        session()->forget("checkState");
        $bookCategoryId = Booking::find(decrypt($req->booking))->categoryId;
        $staffs = Staff::where('status',1)->where('categoryId',$bookCategoryId)->get();
        $checkStat = [];
        foreach ($staffs as $staff) {
          $checkStat[] =  (string)$staff->staffId;
        }
        session()->put('checkState', $checkStat);
      }elseif($req->type==3){
        session()->forget("checkState");
      }

      $staffIds = !empty(session()->get('checkState'))?session()->get('checkState'):[];

      return Staff::select('forname','surname')->whereIn('staffId',$staffIds)->get();
    }

    public function sendBookingSms(Request $req){
      $bookingId = $req->bookingId;
      $checkState = session()->get('checkState');
      $booking =Booking::with(['shift','unit','category'])->find(decrypt($bookingId));
      $times = ClientUnitSchedule::where('clientUnitId',$booking->unit->clientUnitId)
                ->where('staffCategoryId',$booking->category->categoryId)
                ->where('shiftId',$booking->shiftId)
                ->first();
      $staffs = Staff::whereIn('staffId',$checkState)->get();
      foreach ($staffs as $staff) {
        $emailAddress = "0044".substr($staff->mobile, 1).'@mail.mightytext.net';
        $staff->email = str_replace(" ","",$emailAddress);
        $staff->message =$req->message;
        try {

          Mail::to($staff->email)->queue(new SendPlainSMSEvent($req->message));
          $bookingAlertLog = new BookingAlertLog();
          $bookingAlertLog->bookingId = $booking->bookingId;
          $bookingAlertLog->staffId = $staff->staffId;
          $bookingAlertLog->message = $staff->message;
          $bookingAlertLog->save();

          $booking->newSmsStatus = 1;
          $booking->save();
        } catch (\Exception $e) {
          session()->forget('checkState');
          session()->flash('message', 'Error Sending in SMS, Please try again !!');
          return ['status'=>true,'redirect'=>route('booking.allocate.staff',[$bookingId,$req->page,$req->search])];
        }
      }
      session()->forget('checkState');
      session()->flash('message', 'Succesfully Send SMS to selected staffs !!');
      return ['status'=>true,'redirect'=>route('booking.allocate.staff',[$bookingId,$req->page,$req->search])];
    }

    public function clearBookingStaff($bookingId,$page,$search=""){
      $booking = Booking::find(decrypt($bookingId));
      $logContent = "<span class='logHgt'>Cleared</span> <strong>".$booking->staff->forname." ".$booking->staff->surname."</strong> from Booking";
      $oldStaffId = $booking->staffId;
      $booking->update([
        'staffId'=>NULL,
        'staffStatus'=>1,
        'finalConfirm' =>1,
        'finalConfirmationSms' =>0,
        'confirmSmsStatus' =>0,
        'modeOfTransport'=>NULL,
        'outBoundDriver'=>NULL,
        'outBoundDriverType'=>NULL,
        'outBoundDriverId'  =>NULL,
        'inBoundDriverType'=>NULL,
        'inBoundDriverId' =>NULL
      ]);
      Cache::forget('bookedStaffs');
      Cache::forget('booking_'.$booking->bookingId);

      ClientUnitInformLog::where('bookingId' ,$booking->bookingId)->delete();
      BookingLog::create([
        'bookingId' =>$booking->bookingId,
        'content' => $logContent,
        'author' =>Auth::guard('admin')->user()->adminId,
      ]);

      if($oldStaffId){
        BookingPriorityStaff::create([
          'staffId' =>$oldStaffId,
          'date' =>$booking->date
        ]);
        Cache::forget('prior_staffs_'.$booking->date);
      }
      return redirect(route('booking.allocate.staff',[$bookingId,$page,$search]))->with('message','Successfuly Cleared staff');
    }

    public function checkIfStaffIsAssigned($staffId,$booking){
      switch ($booking->shiftId) {
        case '1':  // EARLY
          $checkNight = Booking::where("date",date('Y-m-d', strtotime('-1 day', strtotime($booking->date))))
                            ->where("shiftId",4)
                            ->where('staffId',$staffId)
                            ->where("staffStatus",3)->count();
          $checklongDay = Booking::where("date",$booking->date)
                            ->whereIn("shiftId",[1,3])
                            ->where('staffId',$staffId)
                            ->where("staffStatus",3)->count();
          if($checkNight > 0){
            return $checkNight;
          }
          if($checklongDay > 0){
            return $checklongDay;
          }
        break;
        case '2':  //LATE
          $checLongDayNight = Booking::where("date",$booking->date)
                            ->whereIn("shiftId",[2,3,4])
                            ->where('staffId',$staffId)
                            ->where("staffStatus",3)->count();
          return $checLongDayNight;
          break;
        case '3': // LONGDAY
          $checkNight = Booking::where("date",date('Y-m-d', strtotime('-1 day', strtotime($booking->date))))
                            ->where("shiftId",4)
                            ->where('staffId',$staffId)
                            ->where("staffStatus",3)->count();
          $checLongDayNight = Booking::where("date",$booking->date)
                            ->whereIn("shiftId",[1,2,3,4])
                            ->where('staffId',$staffId)
                            ->where("staffStatus",3)->count();
          if($checkNight > 0){
            return $checkNight;
          }
          if($checLongDayNight > 0){
            return $checLongDayNight;
          }
        break;
        case '4':  //NIGHT
          $checkNight = Booking::where("date",date('Y-m-d', strtotime('+1 day', strtotime($booking->date))))
                            ->whereIn("shiftId",[1,3])
                            ->where('staffId',$staffId)
                            ->where("staffStatus",3)->count();
          $checLongDayNight = Booking::where("date",$booking->date)
                            ->whereIn("shiftId",[2,3,4])
                            ->where('staffId',$staffId)
                            ->where("staffStatus",3)->count();
          if($checkNight > 0){
            return $checkNight;
          }
          if($checLongDayNight > 0){
            return $checLongDayNight;
          }
          break;
      }
    }

    public function getStaffLog(Request $req){
      $logs = StaffLog::with('admin')->where('staffId',$req->staffId)->orderBy('staffLogId','desc')->get();
      $html = view('bookings.partials.search.logTemplate',compact('logs'));
      return $html;
    }

    public function staffLogEntry(Request $req){
      StaffLog::create([
        'staffId' =>$req->staffId,
        'date' =>date('Y-m-d'),
        'priority' =>$req->priority,
        'entryBy' =>Auth::guard('admin')->user()->adminId,
        'content' =>$req->content,
      ]);
      $logs = StaffLog::with('admin')->where('staffId',$req->staffId)->orderBy('staffLogId','desc')->get();
      $html = view('bookings.partials.search.logTemplate',compact('logs'));
      return $html;
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
              $ifSingle = 1;
          }
          if($availabilty[$i]->night==1){
            if($ifSingle==1){ $html .=",";}
            $html .="N";
            $ifSingle = 1;
          }
          if($availabilty[$i]->absent==1){
            $html ="A";
            $ifSingle = 1;
          }
        }
      }
      if($ifSingle==0){ $html ="A"; }
      return $html;
    }

    public function makeUnavailableStaff(Request $req){
      StaffAvailability::where('date',$req->date)->where('staffId',decrypt($req->staffId))->delete();
      return 1;
    }

    public function getStaffAvailabiltyModal(){
      $today = date('Y-m-d');
      $tomorrow = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
      $dayAftrTmrow = date('Y-m-d', strtotime('+2 days', strtotime(date('Y-m-d'))));
      $dayAftrTmrowPone = date('Y-m-d', strtotime('+3 days', strtotime(date('Y-m-d'))));
      $dayAftrTmrowPtwo = date('Y-m-d', strtotime('+4 days', strtotime(date('Y-m-d'))));

      $availability = StaffAvailability::where('staffId',request('staffId'))
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
    }

    public function getStaffCurrentHistoryModal(){
      $dayBeforeYestrdy = date('Y-m-d', strtotime('-2 days', strtotime(date('Y-m-d'))));
      $yesterdy = date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))));
      $today = date('Y-m-d');
      $tomorrow = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
      $dayAftrTmrow = date('Y-m-d', strtotime('+2 days', strtotime(date('Y-m-d'))));

      $pastShifts = Booking::with(['shift'])->where('staffId',request('staffId'))->whereIn('date',[$dayBeforeYestrdy,$yesterdy,$today,$tomorrow,$dayAftrTmrow])->where('unitStatus',4)->get();
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
    }
}
