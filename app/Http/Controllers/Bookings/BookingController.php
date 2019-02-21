<?php

namespace App\Http\Controllers\Bookings;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Booking;
use App\Models\BookingUnitStatus;
use App\Models\ClientUnitSchedule;
use App\Models\BookingPriorityStaff;
use Auth;
use Log;
use Cache;
use DB;
use App\Models\ClientUnit;
use App\Models\Shift;
use App\Models\Admin;
use App\Models\ClientUnitContact;
use App\Models\ClientUnitInformLog;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\BookingSms;
use App\Models\BookingLog;
use App\Models\Driver;
use App\Http\Controllers\DashboardController;
use Carbon\Carbon;

class BookingController
{
    public function dashBoard(){
      $todayConfirmed = Booking::whereDate('date',date('Y-m-d'))->where('unitStatus',4)->count();
      return view('bookings.dashboard',compact('todayConfirmed'));
    }
    public function currentBooking($page,$searchKeyword=""){
      session()->forget("checkBooking");
      $expiresAt = now()->addMinutes(1440);
      if (Cache::has('shifts')) { $shifts = Cache::get('shifts'); }else{ $shifts = Shift::where('status',1)->get(); Cache::put('shifts', $shifts,$expiresAt);}
      if (Cache::has('units')) { $units = Cache::get('units'); }else{ $units = ClientUnit::where('status',1)->orderBy('name','ASC')->get(); Cache::put('units', $units,$expiresAt);}
      if (Cache::has('staffs')) { $staffs = Cache::get('staffs'); }else{ $staffs = Staff::where('status', 1)->orderBy('forname','ASC')->get(); Cache::put('staffs', $staffs,$expiresAt);}
      if (Cache::has('categories')) { $categories = Cache::get('categories'); }else{ $categories = StaffCategory::where('status',1)->get(); Cache::put('categories', $categories,$expiresAt);}
      // FUTURE BOOKING COUNT
      $bookSumry = Booking::whereBetween('date',[date('Y-m-d'),date('Y-m-d', strtotime('+15 day'))])
                  ->select('date', DB::raw('sum(Case When unitStatus = 4 Then 1 Else 0 End) as confirmedCount'),DB::raw('sum(Case When staffId IS NULL Then 1 Else 0 End) as uncoveredCount'))
                  ->groupBy('date')
                  ->where('unitId','<>',21)
                  ->where('unitStatus',4)->get();
      // FUTURE BOOKING COUNT
      /*HCA RGN COUNT*/
      $hcaRgnCount = Booking::whereDate('date','>=',date('Y-m-d'))
                  ->select(DB::raw('sum(Case When staffId IS NULL AND categoryId=2 Then 1 Else 0 End) as hcaCount'),DB::raw('sum(Case When staffId IS NULL AND categoryId=1 Then 1 Else 0 End) as rgnCount'))
                  ->where('unitId','<>',21)
                  ->where('unitStatus',4)->first();
      /*HCA RGN COUNT*/
      return view('bookings.bookings',compact('shifts', 'units', 'staffs', 'categories', 'searchKeyword','bookSumry','hcaRgnCount'));
    }
    public function dataCurrentBooking(Request $req){
      return $this->dataTable('current',$req);
    }
    public function dataTable($type,$req){
      $unitStatus = 0;
      $expiresAt = now()->addMinutes(5);
      session()->put('bookingPage', $req->start);
      $query = Booking::with(['staff','unit','category','shift'])->select('bookings.*');
      $query = $query->leftJoin('client_units', 'client_units.clientUnitId', 'bookings.unitId');
      if(empty($req->columns[3]['search']['value']) && empty($req->search['value'])){
          $query->whereDate('date',date('Y-m-d'));
      }
      if(!empty($req->search['value'])){
          $query->where('bookingId',$req->search['value']);
      }

      $query = $query->orderBy('bookings.date','ASC');
      $query = $query->orderBy('bookings.unitStatus','DESC');
      $query = $query->orderBy('bookings.shiftId','ASC');
      $query = $query->orderBy('client_units.name','ASC');
      $query = $query->orderBy('bookings.categoryId','ASC');

      $data = Datatables::of($query);

      $data->addIndexColumn();

      $data->filter(function ($query) use ($req) {
        if($req->has('thisWeek') && $req->get('thisWeek') > 0) {
          $currentTime = Carbon::now();
          $query->whereDate('date', '>=', $currentTime->startOfWeek()->format('Y-m-d'))
                ->whereDate('date', '<=', $currentTime->copy()->endOfWeek()->format('Y-m-d'));
        }
        if($req->has('transport') && $req->get('transport') > 0) {
          $query->where('bookings.modeOfTransport',$req->transport);
        }
      });
      $data->setRowClass(function ($booking) {
        if($booking->unitId == 21 && $booking->staffId != null){
          $checkAnotherUnit = Booking::whereDate('date',$booking->date)->where('staffId',$booking->staffId)->where('unitStatus',4)->count();
          if($checkAnotherUnit > 1){
            return $class = "tr-bg-yellow";
          }
        }


        $class = "";

        if($booking->unitStatus ==3){
          return  $class = "tr-bg-purple";
        }

        if($booking->unitStatus ==2){
          return  $class = "tr-bg-red";
        }
        if($booking->unitStatus ==5){
          return  $class = "tr-bg-orange";
        }

        if($booking->staffStatus !=3){
            if($booking->date){

                $bookingDate = Carbon::createFromFormat('Y-m-d', $booking->date);
                $today = Carbon::today();

                if(($booking->categoryId ==2) && ($bookingDate->diffInDays() <=1)){
                  return $class = "tr-bg-blue";
                }

                if(($booking->categoryId ==1)&&($bookingDate->diffInDays()<=2)){
                  return $class = "tr-bg-blue";
                }

                if($bookingDate->diffInDays()<=2){
                  return $class = "tr-bg-blue";
                }

                if(( $today->isFriday())&&($bookingDate->diffInDays()<=3)) {
                  return $class = "tr-bg-blue";
                }

            }
        }

        return  $class ;
      });
      $data->editColumn('checkbox',function($booking){
        return "<input type='checkbox' class='icheckBox' value='$booking->bookingId' />";
      });
      $data->editColumn('bookingId',function($booking){

        $info = "";

        $info .= "Handled by : ";
        if(!empty($booking->handledBy)){
          if (Cache::has('admin_'.$booking->handledBy)) {
            $handeldByName = Cache::get('admin_'.$booking->handledBy);
          }else {
            $handeldByName = Admin::where('adminId', $booking->handledBy)->first();
            Cache::forever('admin_'.$booking->handledBy, $handeldByName);
          }
          if($handeldByName)$info .= $handeldByName->name;
        }
        $info .= "\n";

        $info .= "Requested by : ";
        if(!empty($booking->requestedBy)){
          if(is_numeric($booking->requestedBy)){
            if (Cache::has('client_unit_contact_'.$booking->requestedBy)) {
              $unitContact = Cache::get('client_unit_contact_'.$booking->requestedBy);
            }else {
              $unitContact = ClientUnitContact::find($booking->requestedBy);
              Cache::forever('client_unit_contact_'.$booking->requestedBy, $unitContact);
            }

            if($unitContact){
              $info .=$unitContact->fullName;
            }
          }else{
            $info .=$booking->requestedBy;
          }

        }
        $info .= "\n";

        $info .= "Mode of request : ";
        if($booking->modeOfRequest==1){
          $info .= "Email";
        }
        elseif($booking->modeOfRequest==2){
          $info .= "Phone";
        }
        elseif($booking->modeOfRequest==3){
          $info .= "SMS";
        }
        $info .= "\n";

        $info .= "Requested at : ";
        if(!empty($booking->created_at)){
          $info .= date('d-M-Y',strtotime($booking->created_at))." ";
        }
        if(!empty($booking->requestedTime)){
          $info .= date('H:i:s',strtotime($booking->requestedTime)) ;
        }

        return '<div data-toggle="tooltip" title="'.$info.'" style="width:100%;">'.$booking->bookingId.'</div>';
      });
      $data->editColumn('type',function($booking){
        if($booking->type==1) return "<button class='btn btn-success btn-xs mrs'>NG</button>";
        else return "<button class='btn btn-primary btn-xs mrs'>UNIT</button>";
      });
      $data->editColumn('date',function($booking){
        if($booking->date){

          $bookingDate = Carbon::createFromFormat('Y-m-d', $booking->date);
          $isWeekend = $bookingDate->isWeekend();
          if($isWeekend){
            return "<span class='redFont'>".date('d-M-Y, D',strtotime($booking->date))."</span>";
          }else{
            return date('d-M-Y, D',strtotime($booking->date));
          }
        }
      });
      $data->filterColumn('date', function($booking, $keyword) {
        $dates = explode(" - ",$keyword);
        $booking->whereBetween('date',[date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))]);
      });
      $data->editColumn('staff.forname',function($booking){

        if($booking->staffId){
          if (Cache::has('staff_'.$booking->staffId)) {
            $staff = Cache::get('staff_'.$booking->staffId);
          }else {
            $staff = Staff::find($booking->staffId);
            Cache::forever('staff_'.$booking->staffId, $staff);
          }
          $info ="";
          $name = "";
          if($staff){
            $info .= "Mobile : ";

            $info.= $staff->mobile;
            $name = $staff->forname." ".$staff->surname;
          }

          return "<a data-toggle='tooltip' title='".$info."' target='_blank' title='".$booking->outdriver."' href=".route('booking.send.profile',[encrypt($booking->staffId)])." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'>".$name."</a>";
        }
      });
      $data->editColumn('staff.shiftlog',function($booking){
        if (Cache::has('booking_log_'.$booking->bookingId)) {
          $log = Cache::get('booking_log_'.$booking->bookingId);
        }else {
          $log = BookingLog::where('bookingId',$booking->bookingId)->where('type',2)->count();
          Cache::forever('booking_log_'.$booking->bookingId, $log);
        }
        if($log > 0) $class = 'warning'; else $class = 'success';
        $html ="<button class='btn btn-".$class." bookingLog btn-xs mrs' ";
        $html .="bookid='".$booking->bookingId."' ";
        $html .="staff='".$booking->staff->forname." ".$booking->staff->surname."' ";
        $html .="unit='".$booking->unit->alias."' ";
        $html .="category='".$booking->category->name."' ";
        $html .="shift='".$booking->shift->name."' ";
        $html .="date='".date('d-M-Y, D',strtotime($booking->date))."' ";
        $html .=">Shift Log</button>";
        return $html;
      });
      $data->editColumn('shift.name',function($booking){
        $style = "style=color:#".$booking->shift->colorCode;
        $aColor = "style='background:#fff;font-weight: bold;font-size: 14px;style='width:100%;'";
        if($booking->shiftId){
          $info = "";
          if (Cache::has('unit_schedule_'.$booking->unitId."_".$booking->categoryId."_".$booking->shiftId)) {
            $times = Cache::get('unit_schedule_'.$booking->unitId."_".$booking->categoryId."_".$booking->shiftId);
          }else {
            $times = ClientUnitSchedule::where('clientUnitId',$booking->unitId)->where('staffCategoryId',$booking->categoryId)->where('shiftId',$booking->shiftId)->first();
            Cache::forever('unit_schedule_'.$booking->unitId."_".$booking->categoryId."_".$booking->shiftId, $times);
          }
          if($times){
            $info .= "Start Time : ".date('H:i',strtotime($times->startTime));
            $info .= "\n";
            $info .= "End Time : ".date('H:i',strtotime($times->endTime));
          }

          return "<a ".$aColor." data-toggle='modal' data-book-id=".encrypt($booking->bookingId)." data-target='#editBookingModal' href='javascript:void(0)' class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><span data-toggle='tooltip' title='".$info."' ".$style.">".$booking->shift->name."</span></a>";
        }
      });
      $data->filterColumn('staff.forname', function($booking, $keyword) {
          $booking->where('bookings.staffId', $keyword);
      });
      $data->editColumn('unit.name',function($booking){
        $info = "";
        if (Cache::has('client_unit_contact_default'.$booking->unitId)) {
          $contact = Cache::get('client_unit_contact_default'.$booking->unitId);
        }else {
          $contact = ClientUnitContact::where('clientUnitId',$booking->unitId)->first();
          Cache::forever('client_unit_contact_default'.$booking->unitId, $contact);
        }

        $info.="Contact : ";
        $info.=$contact->phone;

        $style = "style='color:#000;'";
        if($booking->unitId==21){
          $style ="style='color:#f00;'";
        }

        if($booking->unit->alias){
          return "<span data-toggle='tooltip' ".$style." title='".$info."'>".$booking->unit->alias."</span>";
        }else{
          return "<span data-toggle='tooltip' ".$style." title='".$info."'>".$booking->unit->name."</span>";
        }
      });
      $data->filterColumn('unit.name', function($booking, $keyword) {
        if($keyword=='res'){
          $booking->where('bookings.unitId', '<>',21);
        }else{
          $booking->where('bookings.unitId', $keyword);
        }
      });
      $data->filterColumn('shift.name', function($booking, $keyword) {
        $booking->where('bookings.shiftId', $keyword);
      });
      $data->editColumn('unitStatus',function($booking) use($unitStatus){
        $html = "";
        if($booking->importantNotes){
          $html .= "<a class='imPnts' data-toggle='tooltip' title='".$booking->importantNotes."'><span class='impNts'>*</span></a>";
        }
        $html .="<a class='untStsClass' href='javascript:void(0)' data-toggle='modal' data-book-id=".encrypt($booking->bookingId)." data-target='#statusSwitch' >";
        $impNots = "";
        if (Cache::has('booking_status'.$booking->bookingId)) {
          $status = Cache::get('booking_status'.$booking->bookingId);
        }else {
          $status = BookingUnitStatus::where('bookingId',$booking->bookingId)->first();
          Cache::forever('booking_status'.$booking->bookingId, $status);
        }
        if($booking->unitStatus ==1){   // Temporary

          if($status){
            $info ="Temporary By : ";
            if (Cache::has('admin_'.$status->temporaryBy)) {
              $admin = Cache::get('admin_'.$status->temporaryBy);
            }else {
              $admin = Admin::where('adminId', $status->temporaryBy)->first();
              Cache::forever('admin_'.$status->temporaryBy, $admin);
            }
            $info.= $admin->name;
            $info .= "\n";
            $info.= "Temporary At : ";
            $info.= $status->temporaryAt;

            $html .=  "<span data-toggle='tooltip' title='".$info."' class='label label-primary'>".$impNots."TMP</span>";
          }else{
            $html .=  "<span class='label label-primary'>".$impNots."TMP</span>";
          }

        }
        if($booking->unitStatus ==2){  // Cancelled
          if($booking->cancelAuthorizedBy >0){

            if (Cache::has('admin_'.$booking->cancelAuthorizedBy)) {
              $admin = Cache::get('admin_'.$booking->cancelAuthorizedBy);
            }else {
              $admin = Admin::where('adminId', $booking->cancelAuthorizedBy)->first();
              Cache::forever('admin_'.$booking->cancelAuthorizedBy, $admin);
            }

            $toolTip = "Cancelled By : ";
            $toolTip .= $admin->name;
            $toolTip .= "\n";
            $toolTip .= "Cancelled On : ".date('d-m-Y',strtotime($booking->cancelDate))." ".date('H:i:s',strtotime($booking->cancelTime));
            $html .=  "<span data-toggle='tooltip' title='".$toolTip."' style='width:100%;' class='label label-danger'>".$impNots."CNL</span>";
          }else{
            $html .=  "<span class='label label-danger'>".$impNots."CNL</span>";
          }

        }
        if($booking->unitStatus ==3){  // Unable To Cover
          if($status){
            if (Cache::has('admin_'.$status->unableToCoverBy)) {
              $admin = Cache::get('admin_'.$status->unableToCoverBy);
            }else {
              $admin = Admin::where('adminId', $status->unableToCoverBy)->first();
              Cache::forever('admin_'.$status->unableToCoverBy, $admin);
            }

            $info ="Unable To Cover By : ";
            $info.= $admin->name;
            $info .= "\n";
            $info.= "Unable To Cover At : ";
            $info.= $status->unableToCoverAt;

            $html .=  "<span data-toggle='tooltip' title='".$info."' class='label label-warning'>".$impNots."UNC</span>";
          }else{
            $html .=  "<span class='label label-warning'>".$impNots."UNC</span>";
          }

        }
        if($booking->unitStatus ==4){  // Confirmed
          if($status){
            $info ="Confirmed By : ";
            if (Cache::has('admin_'.$status->confirmedBy)) {
              $admin = Cache::get('admin_'.$status->confirmedBy);
            }else {
              $admin = Admin::where('adminId', $status->confirmedBy)->first();
              Cache::forever('admin_'.$status->confirmedBy, $admin);
            }

            $info.= $admin->name;
            $info .= "\n";
            $info.= "Confirmed At : ";
           $info.= $status->confirmedAt;

            $html .=  "<span data-toggle='tooltip' title='".$info."' class='label label-success'>".$impNots."CNF</span>";
          }else{
            $html .=  "<span class='label label-success'>".$impNots."CNF</span>";
          }
        }
        if($booking->unitStatus ==5){  //Booking Error
          if($status){
          $name = '';

            $info ="Booking Error By : ";
            if (Cache::has('admin_'.$status->confirmedBy)) {
              $admin = Cache::get('admin_'.$status->confirmedBy);
            }else {
              $admin = Admin::where('adminId', $status->confirmedBy)->first();
              Cache::forever('admin_'.$status->confirmedBy, $admin);
            }

            $info.= $admin->name;
            $info .= "\n";
            $info.= "Booking Error At : ";
            $info.= $status->bookingErrorAt;

            $html .=  "<span data-toggle='tooltip' title='".$info."' class='label label-warning'>".$impNots."BER</span>";
          }else{
            $html .=  "<span class='label label-warning'>".$impNots."BER</span>";
          }

        }

        $html .= "</a>";
        return $html;
      });
      $data->editColumn('category.name',function($booking) use($unitStatus){
        $style = "style=font-weight:bold";
        if($booking->categoryId ==1){ return "<span ".$style." class='redFont'>".$booking->category->name."</span>";}
        if($booking->categoryId ==2){ return "<span class=''>".$booking->category->name."</span>";}
        if($booking->categoryId ==3){ return "<span class='yellowFont'>".$booking->category->name."</span>";}
        if($booking->categoryId ==4){ return "<span class=''>".$booking->category->name."</span>";}
        if($booking->categoryId ==5){ return "<span class=''>".$booking->category->name."</span>";}
      });
      $data->filterColumn('category.name', function($booking, $keyword) {
        $booking->where('bookings.categoryId', $keyword);
      });
      $data->editColumn('staffStatus',function($booking){

        if($booking->staffStatus ==1){ return "<a><span class='label label-primary stffSts'>NEW</span></a>";}
        if($booking->staffStatus ==2){ return "<a><span class='label label-warning stffSts'>INF</span></a>";}
        if($booking->staffStatus ==3){
          $html = "";
          if($booking->confirmedBy > 0){

            if (Cache::has('admin_'.$booking->confirmedBy)) {
              $admin = Cache::get('admin_'.$booking->confirmedBy);
            }else {
              $admin = Admin::where('adminId', $booking->confirmedBy)->first();
              Cache::forever('admin_'.$booking->confirmedBy, $admin);
            }

            $toolTip = "Confirmed By : ".$admin->name;
            $toolTip = "";
            $toolTip .= "\n";
            $toolTip .= "Confirmed On : ".date('d-m-Y H:i:s',strtotime($booking->confirmedAt));
            $html .=  "<a><span data-toggle='tooltip' title='".$toolTip."' class='label label-success stffSts'>CNF</span></a>";
          }else{
            $html .=  "<a><span class='label label-success stffSts'>CNF</span>";
          }
          return $html;
        }
        if($booking->staffStatus ==4){ return "<a><span class='label label-purple stffSts'>SCD</span></a>";}
        if($booking->staffStatus ==5){ return "<a><span class='label label-danger stffSts'>DMY</span></a>";}
      });
      $data->editColumn('actions',function($booking) use($unitStatus,$req,$type){
        $searchKeyword = $req->search['value'];
        $html = "";
        $html = "<a href=".route('booking.allocate.staff',[encrypt($booking->bookingId),$type,$searchKeyword])." class='btn btn-success btn-xs mrs' style='margin: 0 5px;'>";
        $html .="<span class='p4' data-toggle='tooltip' title='Search'><i class='fa fa-search'></i></span></a>";

        $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])."
                      class='btn ".(($booking->finalConfirm==2)?"btn-success":"btn-warning")." btn-xs mrs' style='margin: 0 5px;'>";
        $html .="<span class='p4' data-toggle='tooltip' title='Allocate'><i class='fa fa-edit'></i></span></a>";

        /*SMS*/
        if(!empty($booking->staffId)&&($booking->staffId>0)){
          if($booking->confirmSmsStatus == 1 && $booking->finalConfirmationSms ==0){
            $html .= "<a href=".route('booking.preview.send.sms',[encrypt($booking->bookingId),$type,$searchKeyword])." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'>";
            $html .="<span class='p4' data-toggle='tooltip' title='SMS'><i class='fa fa-comments'></i></span></a>";
          }
          if($booking->finalConfirmationSms == 1 ){
            $html .= "<a href=".route('booking.preview.send.sms',[encrypt($booking->bookingId),$type,$searchKeyword])." class='btn btn-success btn-xs mrs' style='margin: 0 5px;'>";
            $html .="<span class='p4' data-toggle='tooltip' title='SMS'><i class='fa fa-comments'></i></span></a>";
          }
          if(($booking->confirmSmsStatus == 0) && ($booking->finalConfirmationSms== 0)){
            $html .= "<a href=".route('booking.preview.send.sms',[encrypt($booking->bookingId),$type,$searchKeyword])." class='btn btn-warning btn-xs mrs' style='margin: 0 5px;'>";
            $html .="<span class='p4' data-toggle='tooltip' title='SMS'><i class='fa fa-comments'></i></span></a>";
          }
        }
        else{
          if(($booking->confirmSmsStatus == 0) && ($booking->finalConfirmationSms== 0)){
            $html .= "<a href=".route('booking.preview.send.sms',[encrypt($booking->bookingId),$type,$searchKeyword])." class='btn btn-warning btn-xs mrs' style='margin: 0 5px;'>";
            $html .="<span class='p4' data-toggle='tooltip' title='SMS'><i class='fa fa-comments'></i></span></a>";
          }
        }
        /*SMS*/

        if($booking->modeOfTransport==1){  // IF SELF DRIVE
          $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." class='btn btn-xs mrs btn-success'>SF</a>";
        }

        $style = "style='margin: 0 5px;'";
        if($booking->modeOfTransport==1){  // IF SELF DRIVE
          $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success'>SF</a>";
        }else if($booking->modeOfTransport==2){
          if($booking->outBoundDriverId > 0){
            switch ($booking->outBoundDriverType) {
              case 1:
                $drvrName = $this->getDriverName(1,$booking->outBoundDriverId);
                $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success drName'>".$drvrName."</a>";
                break;
              case 2:
                $drvrName = $this->getDriverName(2,$booking->outBoundDriverId);
                $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success drName'><span style='color:#fae843;'>".$drvrName."</span></a>";
                break;
              case 3:
                  $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success drName'><span style='color:#020EFC;'>TAXI</span></a>";
                break;
            }
          }
          if($booking->outBoundDriverId==NULL && $booking->outBoundDriverType==4 ){
            $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success'>SF</a>";
          }
          if($booking->outBoundDriverId==NULL  && $booking->outBoundDriverType !=4){
            $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-danger'>OT</a>";
          }

          if($booking->inBoundDriverId>0){
            switch ($booking->inBoundDriverType) {
              case 1:
                $drvrName = $this->getDriverName(1,$booking->inBoundDriverId);
                $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success drName'>".$drvrName."</a>";
                break;
              case 2:
                $drvrName = $this->getDriverName(2,$booking->inBoundDriverId);
                $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success drName'><span style='color:#fae843;'>".$drvrName."</span></a>";
                break;
              case 3:
                  $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success drName'><span style='color:#020EFC;'>TAXI</span></a>";
                break;
              case 4:
                $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success'>SF</a>";
                break;
            }
          }
          if($booking->inBoundDriverId==NULL && $booking->inBoundDriverType==4 ){
            $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-success'>SF</a>";
          }
          if($booking->inBoundDriverId==NULL && $booking->inBoundDriverType!=4){
            $html .= "<a href=".route('booking.allocate.staff.confirm',[encrypt($booking->bookingId),$type,$searchKeyword])." ".$style." class='btn btn-xs mrs btn-danger'>IN</a>";
          }

        }
        if($booking->informUnit==0){
          $html .="<span class='btn btn-xs mrs btn-warning unitInfo' href='javascript:void(0)' data-toggle='modal' title='Inform Unit' data-book-id=".encrypt($booking->bookingId)." data-target='#unitInformedModal'><i class='fa fa-h-square' aria-hidden='true'></i></span>";
        }else{
          $html .="<span class='btn btn-xs mrs btn-success unitInfo done' href='javascript:void(0)' title='Inform Unit' data-toggle='modal' data-book-id=".encrypt($booking->bookingId)." data-target='#unitInformedModal'><i class='fa fa-h-square' aria-hidden='true'></i></span>";
        }
        return $html;
      });
      $tableData =$data->make(true);
      return $tableData;
    }
    public function getDriverName($type,$primaryId){
      switch ($type) {
        case 1:
          if (Cache::has('driver_'.$primaryId)) {
            $driver = Cache::get('driver_'.$primaryId);
            return substr($driver->forname,0,4);
          }else{
            $driver = Driver::find($primaryId);
            Cache::forever('driver_'.$primaryId, $driver);
            return substr($driver->forname,0,4);
          }

          break;

        case 2:
          if (Cache::has('staff_'.$primaryId)) {
            $staff = Cache::get('staff_'.$primaryId);
            return substr($staff->forname,0,4);
          }else{
            $staff = Staff::find($primaryId);
            Cache::forever('staff_'.$primaryId, $staff);
            return substr($staff->forname,0,4);
          }
          break;
      }
    }
    public function newBookingStepOne(){
      $units = ClientUnit::where('status',1)->orderBy('name','ASC')->get();
      $shifts = Shift::where('status',1)->get();
      $categories = StaffCategory::where('status',1)->get();
      return view('bookings.newBooking',compact('units','shifts','categories'));
      $data = booking::all();
      return view(bookings.bookings)->with("data",$data);
    }
    public function StepOneRow(Request $req){
      $shifts = Shift::where('status',1)->get();
      $categories = StaffCategory::where('status',1)->get();
      return view('bookings.newBookNewRow',compact('shifts','categories'));
    }
    public function StepOneAction(Request $req){
      for ($i=0; $i < count($req->shiftId); $i++) {
        for ($j=0; $j < $req->numbers[$i]; $j++) {
          $booking = Booking::create([
            'categoryId'=>$req->categoryId[$i],
            'date'=>date('Y-m-d',strtotime($req->date[$i])),
            'handledBy'=>Auth::guard('admin')->user()->adminId,
            'unitId'=>$req->unitId,
            'unitStatus'=>4 ,
            'modeOfRequest'=>$req->modeOfRequest,
            'requestedBy'=>$req->requestedBy,
            'requestedTime'=>date('H:i:s',strtotime($req->requestedTime)),
            'requestedDate'=>date('Y-m-d',strtotime($req->requestedDate)),
            'shiftId'=>$req->shiftId[$i],
            'importantNotes'=>$req->importantNotes[$i],
          ]);

          BookingLog::create([
            'bookingId' =>$booking->bookingId,
            'content' =>'Booking <span class="logHgt">Created</span>',
            'author' =>Auth::guard('admin')->user()->adminId,
          ]);

          BookingUnitStatus::create([
            'bookingId'   =>$booking->bookingId,
            'confirmedBy'   =>Auth::guard('admin')->user()->adminId,
            'confirmedAt'   =>date('Y-m-d H:i:s')
          ]);
        }
      }
      return redirect(route('booking.current'))->with('message','Succesfully created new Booking !!');
    }
    public function changeBookingStatus(Request $req){
      $booking = Booking::find(decrypt($req->bookId));
      $booking->unitStatus =$req->unitStatus;

      $oldStaffId = $booking->staffId;

      if($req->unitStatus=="1"){  // Temporary
        $booking->staffStatus =1;
        BookingUnitStatus::where('bookingId',$booking->bookingId)->update([
            'temporaryBy'   =>Auth::guard('admin')->user()->adminId,
            'temporaryAt'   =>date('Y-m-d H:i:s')
          ]);
        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' =>'Unit Status Marked as Temporary',
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);
      }
      if($req->unitStatus=="2"){  // Cancelled
        $booking->staffStatus =$req->staffStatus;
        $booking->staffId = null;
        $booking->modeOfCancelRequest =$req->modeOfCancelRequest;
        $booking->cancelDate =date('Y-m-d');
        $booking->cancelTime=date('H:i:s');
        $booking->cancelRequestedBy =$req->cancelRequestedBy;
        $booking->cancelCharge =$req->cancelCharge;
        $booking->cancelInformUnitTo =$req->informedTo;
        $booking->cancelAuthorizedBy =Auth::user()->adminId;
        $booking->cancelExplainedReason =$req->cancelExplainedReason;
        $booking->canceledOrUTCreason =$req->canceledOrUTCreason;

        BookingUnitStatus::where('bookingId',$booking->bookingId)->update([
            'cancelledBy'   =>Auth::guard('admin')->user()->adminId,
            'cancelledAt'   =>date('Y-m-d H:i:s')
          ]);

        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' =>'Unit Status Marked as Cancelled',
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        if($oldStaffId){
          BookingPriorityStaff::create([
            'staffId' =>$oldStaffId,
            'date' =>$booking->date
          ]);
          Cache::forget('prior_staffs_'.$booking->date);
        }
      }
      if($req->unitStatus=="3"){  // Unable to Cover
        $booking->staffStatus =$req->staffStatus;
        $booking->staffId = null;
        BookingUnitStatus::where('bookingId',$booking->bookingId)->update([
            'unableToCoverBy'   =>Auth::guard('admin')->user()->adminId,
            'unableToCoverAt'   =>date('Y-m-d H:i:s')
          ]);
        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' =>'Unit Status Marked as Unable to Cover',
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        if($oldStaffId){
          BookingPriorityStaff::create([
            'staffId' =>$oldStaffId,
            'date' =>$booking->date
          ]);
        }
      }
      if($req->unitStatus=="4"){  // Confirmed
        $booking->staffStatus =1;
        BookingUnitStatus::where('bookingId',$booking->bookingId)->update([
            'confirmedBy'   =>Auth::guard('admin')->user()->adminId,
            'confirmedAt'   =>date('Y-m-d H:i:s')
          ]);
        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' =>'Unit Status Marked as Confirmed',
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);
      }
      if($req->unitStatus=="5"){  // Booking Error
        $booking->staffStatus =null;
        $booking->staffStatus =$req->staffStatus;

        BookingUnitStatus::where('bookingId',$booking->bookingId)->update([
            'bookingErrorBy'   =>Auth::guard('admin')->user()->adminId,
            'bookingErrorAt'   =>date('Y-m-d H:i:s')
          ]);
        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' =>'Unit Status Marked as Booking Error',
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);
        if($oldStaffId){
          BookingPriorityStaff::create([
            'staffId' =>$oldStaffId,
            'date' =>$booking->date
          ]);
        }

      }
      Cache::forget('booking_status'.$booking->bookingId);

      $booking->save();
      return $booking;
    }
    public function updateEdit(Request $req){
      $booking = Booking::find(decrypt($req->bookId));
      $booking->shiftId =$req->shiftId;
      $booking->importantNotes =$req->importNotes;
      $booking->save();
      return $booking;
    }
    public function getSingleBooking(Request $req){
      $booking = Booking::with(['unitcontacts','unitinformlog','canceled'])->find(decrypt($req->bookId));

      $times = ClientUnitSchedule::where('clientUnitId',$booking->unit->clientUnitId)
                                    ->where('staffCategoryId',$booking->category->categoryId)
                                    ->where('shiftId',$booking->shiftId)
                                    ->first();
      if(!empty($times)){
        $booking->startTime = date('h:i A',strtotime($times->startTime));
        $booking->endTime = date('h:i A',strtotime($times->endTime));
      }else{
        $booking->startTime = "";
        $booking->endTime = "";
      }

      $bookingDate = Carbon::createFromFormat('Y-m-d', $booking->date);
      $booking->isWeekend = $bookingDate->isWeekend();
      $booking->formatedDate = date('d-M-Y, D',strtotime($booking->date));
      $booking->unitName = empty($booking->unit->alias)?$booking->unit->name:$booking->unit->alias;
      $booking->categoryName = $booking->category->name;
      $booking->shiftName = $booking->shift->name;

      return $booking;
    }
    public function getBookingLog(Request $req){
      $logs = BookingLog::with('admin')->where('bookingId',$req->bookId)->orderBy('bookingLogId','desc')->get();
      $html = view('bookings.partials.logTemplate',compact('logs'));
      return $html;
    }
    public function bookingLogEntry(Request $req){
      BookingLog::create([
        'bookingId' =>$req->bookingId,
        'author' =>Auth::guard('admin')->user()->adminId,
        'type' =>2,
        'content' =>$req->content,
      ]);
      $logs = BookingLog::with('admin')->where('bookingId',$req->bookingId)->orderBy('bookingLogId','desc')->get();
      Cache::forget('booking_log_'.$req->bookingId);
      $html = view('bookings.partials.logTemplate',compact('logs'));
      return $html;
    }
    public function saveUnitInformedLog(Request $req){
      $log = ClientUnitInformLog::where('bookingId',$req->bookId)->first();
      if($log){
        $log->update([
          'informedTo' =>$req->informedTo,
          'modeOfInform' =>$req->modeOfInform,
          'date' =>$req->date,
          'time' =>$req->time,
          'notes' =>$req->notes
        ]);
      }else{
        ClientUnitInformLog::create([
          'bookingId'=>$req->bookId,
          'informedTo' =>$req->informedTo,
          'modeOfInform' =>$req->modeOfInform,
          'date' =>$req->date,
          'time' =>$req->time,
          'notes' =>$req->notes
        ]);
      }

      Booking::where('bookingId',$req->bookId)->update([
        'informUnit'=>1
      ]);

      BookingLog::create([
        'bookingId' =>$req->bookId,
        'author' =>Auth::guard('admin')->user()->adminId,
        'content' =>"<span class='logHgt'>Informed</span> ".ClientUnitContact::find($req->informedTo)->fullName." Unit",
      ]);
      return 1;
    }
}
