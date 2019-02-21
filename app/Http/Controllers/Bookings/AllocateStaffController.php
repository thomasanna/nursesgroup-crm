<?php

namespace App\Http\Controllers\Bookings;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Booking;
use App\Models\BranchContact;
use App\Models\Driver;
use App\Models\Transportation;
use App\Models\ClientUnitSchedule;
use App\Models\TripClub;
use App\Models\BookingLog;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Booking\StaffAllocate;
use Carbon\Carbon;
use App\Helpers\Direction;
use Auth;
use Log;
use App\Http\Controllers\Payments\PaymentHelper;

class AllocateStaffController
{
    public function viewAllocation(Request $request,$id,$page,$searchKeyword=""){
        $staffList = Staff::all();
        $booking = Booking::find(decrypt($id));
        $times = ClientUnitSchedule::where('clientUnitId',$booking->unit->clientUnitId)
                                    ->where('staffCategoryId',$booking->category->categoryId)
                                    ->where('shiftId',$booking->shiftId)
                                    ->first();
        if(!empty($times)){
            $booking->startTime = $times->startTime;
            $booking->endTime = $times->endTime;
        }else{
            if($page=='current'){
                return redirect(route('booking.current'))
                        ->with('message','Client schedule data is missing! Please update the same to proceed.');

            }else{
                return redirect(route('booking.all'))
                        ->with('message','Client schedule data is missing! Please update the same to proceed.');
            }
        }

        $startTime = Carbon::createFromFormat('H:i:s', $booking->startTime);
        $endTime = Carbon::createFromFormat('H:i:s', $booking->endTime);

        $diffInHours = $times->totalHoursStaff;

        $bookingDate = Carbon::createFromFormat('Y-m-d', $booking->date);
        $booking->isWeekend = $bookingDate->isWeekend();

        if((empty($booking->modeOfTransport))&&
           (!empty($booking->staffId))&&
           (!empty($booking->staff))&&
           (!empty($booking->staff->modeOfTransport))){

            $booking->modeOfTransport = $booking->staff->modeOfTransport;
        }

        $staffShiftCost = 0 ;
        $travelDistance = 0 ;
        if((!empty($booking->staffId))&&
           (!empty($booking->staff))){
             $shifType = 'day';
             switch (strtolower($booking->shift->name)) {
               case 'early':
                 $shifType = "day";
                 break;
               case 'late':
                 $shifType = "day";
                 break;
               case 'longday':
                 $shifType = "day";
                 break;
               case 'night':
                 $shifType = "night";
                 break;
             }

            $staffShiftCost = PaymentHelper::calculateHourlyRate($booking,$shifType);
            if(empty($booking->inBoundPickupTime)){
                $endTime = Carbon::createFromFormat('H:i:s', $booking->endTime);
                $endTime->addMinutes(10);
                $booking->inBoundPickupTime = $endTime->toTimeString();
            }
        }

        $outTripClubs = TripClub::whereIn('dayNumber',[date('N',strtotime($booking->date)),date('N',strtotime('+1 day', strtotime($booking->date)))])
                            ->where('driverId',$booking->outBoundDriverId)->get();

        $inTripClubs = TripClub::where('driverId',$booking->inBoundDriverId)
                        ->whereIn('dayNumber',[date('N',strtotime($booking->date)),date('N',strtotime('+1 day', strtotime($booking->date)))])
                        ->get();

        $driverList = Driver::where('status',1)->orderBy('forname','ASC')->get();
        $liftStaffs = Booking::Join('staffs','staffs.staffId','bookings.staffId')
                        ->where('bookings.staffStatus',3)
                        ->where('staffs.modeOfTransport',1)
                        ->whereDate('bookings.date',$booking->date)
                        ->get();

        $extraTA = empty($booking->extraTA)?0:$booking->extraTA;
        $booking->total = (empty($booking->transportAllowence)?0:$booking->transportAllowence)
                            +((empty($booking->bonus)?0:$booking->bonus)*$diffInHours)
                            +$staffShiftCost
                            +$extraTA;

        $viewData = compact('outTripClubs','inTripClubs','page','staffList',"booking","staffShiftCost","travelDistance","diffInHours","driverList","searchKeyword","liftStaffs");
        return view('bookings.allocateStaff',$viewData);
    }

    public function getStaffInfo(Request $request){
        $staff = Staff::find($request->staffId);
        $booking = Booking::find(decrypt($request->bookingId));
        $times = ClientUnitSchedule::where('clientUnitId',$booking->unit->clientUnitId)
                                    ->where('staffCategoryId',$booking->category->categoryId)
                                    ->where('shiftId',$booking->shiftId)
                                    ->first();
        $booking->startTime = $times->startTime;
        $booking->endTime = $times->endTime;

        $dutyTime = $times->totalHoursStaff;

        $travelTime = $this->getTravelTime($booking, $staff);
        $outBoundPickupTime = "";
        if($travelTime>0){
            $startTime = Carbon::createFromFormat('H:i:s', $booking->startTime);
            $startTime->subMinutes(10);
            $startTime->subMinutes($travelTime);
            $outBoundPickupTime = $startTime->toTimeString();
        }

        $travelDistance = $this->getTravelDistance($booking, $staff);
        $transportAllowence = $booking->transportAllowence;
        if($staff->modeOfTransport==1){

            if($travelDistance>0 && $transportAllowence ==NULL){
                if($travelDistance <= 15.00){
                    $transportAllowence = 1.00;
                }
                if($travelDistance >= 15.1 && $travelDistance <= 30.0){
                    $transportAllowence = 2.00;
                }
                if($travelDistance >= 30.1 && $travelDistance <= 50.0){
                    $transportAllowence = 3;
                }
                if($travelDistance >= 50.1 && $travelDistance <= 70.0){
                    $transportAllowence = 4;
                }
            }
        }

        $modeOfTransport = $staff->modeOfTransport;
        $shiftCost =  $this->getStaffShiftRate($booking, $staff,$times)/$times->totalHoursUnit;
        return compact("shiftCost", "modeOfTransport", "outBoundPickupTime","travelDistance","transportAllowence",'dutyTime');
    }

    public function saveAllocation(Request $request){
        $validator = $request->validate([
            'staffId' => 'required'
        ]);

        $booking = Booking::find(decrypt($request->bookingId));

        if($request->finalConfirm != $booking->finalConfirm){
          if($request->finalConfirm ==1){
            $logContent = "Allocation Status <span class='logHgt'>Changed to Waiting For Confirmation</span> for <strong>".$booking->staff->forname." ".$booking->staff->surname."</strong>";
          }else{
            $logContent = "Allocation Status <span class='logHgt'>Changed to Confirmed</span> for <strong>".$booking->staff->forname." ".$booking->staff->surname."</strong>";
          }
          BookingLog::create([
            'bookingId' =>$booking->bookingId,
            'content' =>$logContent,
            'author' =>Auth::guard('admin')->user()->adminId,
          ]);
        }

        $booking->finalConfirm = $request->finalConfirm;
        $booking->modeOfTransport = $request->modeOfTransport;

        if($request->modeOfTransport==2){  // if mode of transport is Transport Required
            $booking->outBoundPickupTime = $request->outPickTime;
            $booking->inBoundPickupTime = $request->inPickTime;
            $booking->outBoundDriverType = $request->outboundDriverType;
            $booking->outBoundClubId = $request->outClubId;
            $booking->inBoundClubId = $request->inClubId;

            if(isset($request->outboundDriverType1)){
              if(!$booking->outBoundDriverId || $booking->outBoundDriverId != $request->outboundDriverType1){
                $outBoundlogContent = "OutBound Transportation <span class='logHgt'>Arranged</span> to <strong>".$booking->staff->forname." ".$booking->staff->surname;
                $outBoundlogContent .= "</strong> with <strong>".Driver::find($request->outboundDriverType1)->forname." ".Driver::find($request->outboundDriverType1)->surname."</strong>";
                BookingLog::create([
                'bookingId' =>$booking->bookingId,
                'content' => $outBoundlogContent,
                'author' =>Auth::guard('admin')->user()->adminId,
                ]);
              }
            }

            if($booking->outBoundDriverType == 1)
                $booking->outBoundDriverId = $request->outboundDriverType1;
            elseif($booking->outBoundDriverType == 2)
                $booking->outBoundDriverId = $request->outboundDriverType2;
            elseif($booking->outBoundDriverType == 3)
                $booking->outBoundDriverId = $request->outboundDriverType3;

            $outBoundTransportation = Transportation::firstOrCreate(
                                        ['bookingId'=>$booking->bookingId,'direction'=>1]
                                    );
            $outBoundTransportation->staffId = $booking->staffId;
            $outBoundTransportation->clubId = $booking->outBoundClubId;
            $outBoundTransportation->unitId = $booking->unitId;
            $outBoundTransportation->date = $booking->date;
            $outBoundTransportation->pickupTime = $booking->outBoundPickupTime;
            if($booking->outBoundDriverType == 1){
                $outBoundTransportation->driverId = $booking->outBoundDriverId;
                $outBoundTransportation->status = 1;
            }else{
                $outBoundTransportation->driverId = 0;
                $outBoundTransportation->status = 0;
            }
            $outBoundTransportation->save();
            if(isset($request->inboundDriverType1)){
              if(!$booking->inBoundDriverId || $booking->inBoundDriverId != $request->inboundDriverType1){
                $inBoundlogContent = "InBound Transportation <span class='logHgt'>Arranged</span> to <strong>".$booking->staff->forname." ".$booking->staff->surname;
                $inBoundlogContent .= "</strong> with <strong>".Driver::find($request->inboundDriverType1)->forname." ".Driver::find($request->inboundDriverType1)->surname."</strong>";
                BookingLog::create([
                  'bookingId' =>$booking->bookingId,
                  'content' => $inBoundlogContent,
                  'author' =>Auth::guard('admin')->user()->adminId,
                ]);
              }
            }


            $booking->inBoundDriverType = $request->inboundDriverType;
            if($booking->inBoundDriverType == 1)
                $booking->inBoundDriverId = $request->inboundDriverType1;
            elseif($booking->inBoundDriverType == 2)
                $booking->inBoundDriverId = $request->inboundDriverType2;
            elseif($booking->inBoundDriverType == 3)
                $booking->inBoundDriverId = $request->inboundDriverType3;

            $inBoundTransportation = Transportation::firstOrCreate(
                                        ['bookingId'=>$booking->bookingId,'direction'=>2]
                                    );

            $inBoundTransportation->staffId = $booking->staffId;
            $inBoundTransportation->unitId = $booking->unitId;
            $inBoundTransportation->clubId = $booking->inBoundClubId;
            $inBoundTransportation->date = $booking->date;
            $inBoundTransportation->pickupTime = $booking->inBoundPickupTime;
            if($booking->inBoundDriverType == 1){
                $inBoundTransportation->driverId = $booking->inBoundDriverId;
                $inBoundTransportation->status = 1;
            }else{
                $inBoundTransportation->driverId = 0;
                $inBoundTransportation->status = 0;
            }
            $inBoundTransportation->save();

        }

        $booking->bonus = $request->bonus;
        $booking->bonusReason = $request->bonusReason;
        $booking->bonusAuthorizedBy = Auth::user()->adminId;
        $booking->distenceToWorkPlace = $request->distanceToWorkplace;
        $booking->extraTA = $request->extraTA;
        $booking->transportAllowence = $request->transportAllowence;
        $booking->aggreedHrRate = $request->aggreedHrRate;
        $booking->save();

        $emailAddress = "0044".substr($booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);
        if($request->page=="current"){
            return redirect(route('booking.current'))->with('message','Succesfully allocated the staffs !!');
        }else{
            return redirect(route('booking.all'))->with('message','Succesfully allocated the staffs !!');
        }
    }

    public function viewChangeConfirm(Request $request,$id){
        $booking = Booking::find(decrypt($id));
        $branchContacts = BranchContact::where('branchId',1)->get();
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
        return view('bookings.changeConfirmStaff',compact('booking','branchContacts'));
    }

    public function saveChangeConfirm(Request $req){
        $booking = Booking::find(decrypt($req->bookingId));
        $booking->cancelExplainedReason =$req->cancelExplainedReason;
        $booking->cancelDate =date('Y-m-d',strtotime($req->cancelDate));
        $booking->cancelTime=date('H:i:s',strtotime($req->cancelTime));
        $booking->cancelCharge =$req->cancelCharge;
        $booking->cancelImapctFactor =$req->cancelImapctFactor;
        $booking->cancelInformUnit =$req->cancelInformUnit;
        $booking->cancelNotes =$req->cancelNotes;
        $booking->cancelAuthorizedBy =Auth::user()->adminId;
        $booking->staffId = null;
        $booking->save();

        if($req->nextAction==2){
            $booking->staffStatus = 4;
            $booking->staffAllocateStatus = NULL;
            $booking->unitStatus = 3;
            $booking->save();
            return redirect()->route('booking.current',$req->bookingId);
        }else{
            $booking->staffStatus = 1;
            $booking->staffAllocateStatus = NULL;
            $booking->save();
            return redirect()->route('booking.allocate.staff',$req->bookingId);
        }

    }

    public function getStaffTA(Request $request){
        $staff = Staff::find($request->staffId);
        $booking = Booking::find(decrypt($request->bookingId));

        $transportAllowence = 0;
        $travelDistance = $this->getTravelDistance($booking, $staff);
        if($travelDistance>0){
            if($travelDistance <= 15){
                $transportAllowence = round(($travelDistance*1),2);
            }
            if($travelDistance > 15 && $travelDistance <= 30){
                $transportAllowence = round(($travelDistance*2),2);
            }
            if($travelDistance > 31 && $travelDistance <= 45){
                $transportAllowence = round(($travelDistance*3),2);
            }

        }

        return compact("transportAllowence");
    }

    private function getStaffShiftRate($booking, $staff,$times){
        $shiftId = $booking->shift->shiftId;
        $shiftType = null;
        if(($shiftId==1) || ($shiftId==3)){
            $shiftType = "DAY";
        }else{
            $shiftType = "NIGHT";
        }

        $bookingDate = Carbon::createFromFormat('Y-m-d', $booking->date);
        $isWeekend = $bookingDate->isWeekend();

        $staffRate = 0;
        if(($shiftType == "DAY")&&($isWeekend)){
            //weekend day
            $staffRate = $staff->payRateWeekendDay;
        }elseif(($shiftType == "NIGHT")&&($isWeekend)){
            //weekend night
            $staffRate = $staff->payRateWeekendNight;
        }elseif(($shiftType == "DAY")&&(!$isWeekend)){
            //week day
            $staffRate = $staff->payRateWeekday;
        }elseif(($shiftType == "NIGHT")&&(!$isWeekend)){
            //week night
            $staffRate = $staff->payRateWeekNight;
        }
        $startTime = Carbon::createFromFormat('H:i:s', $booking->startTime);
        $endTime = Carbon::createFromFormat('H:i:s', $booking->endTime);

        $diffInHours = $times->totalHoursUnit;
        // $diffInHours = ($startTime->diffInMinutes($endTime))/60;

        $staffShiftRate = $diffInHours*$staffRate;

        return $staffShiftRate;
     }

     private function getTravelTime($booking, $staff){
        $source = $staff->pincode;
        $destination = $booking->unit->postCode;
        if(!empty($source) && !empty($destination)){
            $count =0 ;
            while($count<10){
                $coordinates1 = Direction::getCoordinates($source);
                $coordinates2 = Direction::getCoordinates($destination);
                if ($coordinates1['status'] == 300 || $coordinates1['status'] == 250 || $coordinates2['status'] == 300 || $coordinates2['status'] == 250){
                    $count++;
                }else{
                    $result = 301;
                    $time = 0;
                    while ($result > 201) {
                        $dist = Direction::getDrivingDuration($coordinates1['lat'], $coordinates2['lat'], $coordinates1['long'], $coordinates2['long']);
                        $result=$dist['status'];
                        $time = $dist['time'];
                    }
                    return round(($time/60), 2);
                }

            }
        }
        return 0;
     }

     private function getTravelDistance($booking, $staff){
        $source = $staff->pincode;
        $destination = $booking->unit->postCode;
        if(!empty($source)&&!empty($destination)){
            $count =0 ;
            while($count<10){
                $coordinates1 = Direction::getCoordinates($source);
                $coordinates2 = Direction::getCoordinates($destination);
                if ($coordinates1['status'] == 300 || $coordinates1['status'] == 250 || $coordinates2['status'] == 300 || $coordinates2['status'] == 250){
                    $count++;
                }else{
                    $result = 301;
                    $distance = 0;
                    while ($result > 201) {
                        $dist = Direction::getDrivingDistance($coordinates1['lat'], $coordinates2['lat'], $coordinates1['long'], $coordinates2['long']);
                        $result=$dist['status'];
                        $distance = $dist['distance'];
                    }
                    return round((($distance/1000)*0.621), 2);
                }

            }
        }

        return 0;

     }

    public function getDriverClubs(Request $req){
        if($req->day==7) $tomorrow = 1; else $tomorrow =$req->day+1;
        $clubs = TripClub::with('driver')->where('driverId',$req->driverId)->whereIn('dayNumber',[$req->day,$tomorrow])->get();
        $days = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'];
        $html = "<option value=''></option>";
        foreach ($clubs as $club) {
            $html .= "<option value='".$club->clubId."'>".$days[$club->dayNumber]. " - ".$club->driver->forname." - ".$club->title."</option>";
        }
        return $html;
    }
}
