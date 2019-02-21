<?php

namespace App\Http\Controllers\Transportation;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Transportation;
use App\Models\ClientUnitSchedule;
use App\Models\TransportArchive;
use App\Models\TripClub;
use App\Models\TransportComplete;
use App\Models\Admin;
use App\Models\TaxYear;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Mail;
use Yajra\Datatables\Facades\Datatables;
use App\Mail\SendPlainSMS;

class TransportationController{

    public function viewTransport(){
        return view('transportation.transportation');
    }

    public function dataTransport(Request $request){
        $query = Transportation::where('status',1)->with(['driver','staff','booking'])
                  ->whereHas('booking',function ($booking){
                      return $booking->where('unitStatus','4')
                                      ->where('staffStatus','3')
                                      ->where('modeOfTransport','2');
                    })
                    ->selectRaw('*,SUM(CASE WHEN direction = 1 THEN 1 END) AS outCount,SUM(CASE WHEN direction = 2 THEN 1 END) AS inCount')
                    ->where('clubId','>',0)
                    ->where('direction','>',0)
                    ->orderBy('date', 'DESC')
                    ->orderBy('clubId', 'ASC')
                    ->orderBy('pickupTime', 'ASC')
                    ->orderBy('unitId', 'ASC')
                    ->orderBy('direction', 'ASC')
                    ->groupBy('clubId')
                    ->groupBy('date');
        $data = Datatables::of($query);
        $data->addIndexColumn();
        $data->editColumn('DT_Row_Index',function($trip){
            return 1;
        });
        $data->editColumn('date',function($trip){
          return date('d-M-Y, D',strtotime($trip->date));
        });
        $data->editColumn('direction',function($trip){
            if($trip->direction ==1){
                return "Outbond";
            }else{
                return "Inbond";
            }
        });
        $data->editColumn('staff.forname',function($trip){
            if($trip->staff->forname){ return $trip->staff->forname." ".$trip->staff->surname; }
        });

        $data->editColumn('pickuptime',function($trip){
            if($trip->direction ==1){
                return $trip->booking->outBoundPickupTime;
            }else{
                return $trip->booking->inBoundPickupTime;
            }
        });
        $data->editColumn('pickuplocation',function($trip){
            if($trip->direction ==1){
                return $trip->staff->pickupLocation;
            }else{
                return $trip->booking->unit->address;
            }
        });
        $data->editColumn('outCount',function($trip){
            if($trip->outCount){
                return $trip->outCount;
            }else{
                return 0;
            }
        });
        $data->editColumn('inCount',function($trip){
            if($trip->inCount){
                return $trip->inCount;
            }else{
                return 0;
            }
        });
        $data->editColumn('droppoint',function($trip){
            if($trip->direction ==1){
                return $trip->booking->unit->address;
            }else{
                return $trip->staff->pickupLocation;
            }
        });
        $data->editColumn('actions',function($trip){
          $html = "";
          $html .= "<a href='".route('transportation.allocate.trip',[$trip->clubId,$trip->date])."'
          class='btn btn-success btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Allocate</a>";

          if($trip->isEmailSent == 1){
            $rcBtn = "success";
          }else{
            $rcBtn = "warning";
          }

          $html .= "<a href='".route('transportation.send.trip.sms',[$trip->clubId,$trip->date])."'
          class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> SMS</a>";
          if($trip->isPaymentRecorded == 1){
            $rcBtn = "success";
          }else{
            $rcBtn = "primary";
          }

          $html .= "<a href='".route('transportation.proceed.payment',[$trip->clubId,$trip->date])."'
          class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Proceed Payment</a>";
          return $html;
        });
        $tableData =$data->make(true);
        return $tableData;
    }

    public function allocateTransport($clubId,$date){
      $trips = Transportation::where('status',1)->with(['driver','staff','booking'])
          ->where('date',$date)->where('clubId',$clubId)
          ->orderBy('order')
          ->get();
      $driver = Driver::find($trips[0]->driverId);
      $club = TripClub::find($clubId);
      return view('transportation.allocateTransport',compact('trips','driver','date','club'));
    }

    public function saveOrder(Request $request){
      $trip = Transportation::find($request->tripId);
      $trip->update([
        'order' =>$request->order
      ]);
      return redirect(route('transportation.allocate.trip',[$trip->clubId,$trip->date]));
    }

    public function allocateAction(Request $request){
      $trips = Transportation::where('status',1)->with(['driver','staff','booking'])
          ->where('date',$request->date)->where('clubId',$request->clubId)
          ->update([
            'aggreedAmount' =>$request->aggreedAmount,
            'status'  =>1
          ]);
      return redirect(route('transportation.current.trips'))->with('message',"Succesfully Allocated");
    }

    public function sendTripSms($clubId,$date){
      $outs = Transportation::where('status',1)->with(['driver','staff','booking'])
          ->where('date',$date)->where('clubId',$clubId)->where('direction',1)
          ->orderBy('order')
          ->get();

      $ins = Transportation::where('status',1)->with(['driver','staff','booking'])
          ->where('date',$date)->where('clubId',$clubId)->where('direction',2)
          ->orderBy('order')
          ->get();

      if(count($outs) > 0){
        $clubId = $outs[0]->clubId;
        $unitAlias = $outs[0]->booking->unit->alias;
        $unitAddrss = $outs[0]->booking->unit->address;
        $driver = Driver::find($outs[0]->driverId);
      }else{
        $clubId = $ins[0]->clubId;
        $unitAlias = $ins[0]->booking->unit->alias;
        $unitAddrss = $ins[0]->booking->unit->address;
        $driver = Driver::find($ins[0]->driverId);
      }


      $club = TripClub::find($clubId);

      $sms = "Trip Details : ".date('d-M-Y D     ',strtotime($date))."\n\n";
      if(count($outs) > 0){ $sms .="Outbound Trips.\n\n"; }
      foreach ($outs as $key=>$value) {
        $sms .=($key+1).") ".$value->booking->staff->forname." ".$value->booking->staff->surname." - ";
        $sms .=date('h:i A',strtotime($value->pickupTime)).", From ".$value->staff->pickupLocation.",";
        $sms .=" Drop to ".$value->booking->unit->alias.",".$value->booking->unit->address.".     ";
        $sms .="\n";
      }
      $sms .="\n";
      $sms .="Inbound Trips.\n\n";
      foreach ($ins as $key=>$value) {
        $sms .=($key+1).") ".$value->booking->staff->forname." ".$value->booking->staff->surname." - ";
        $sms .=date('h:i A',strtotime($value->pickupTime)).", From ".$value->booking->unit->alias.",";
        $sms .=" Drop to ".$value->staff->pickupLocation.".     ";
        $sms .="\n";
      }

      $sms .= "\nPlease Confirm,\n";
      $sms .= "Nurses group.\n";

      return view('transportation.sendSms',compact(
        'driver','date','club','sms','unitAlias','unitAddrss'
      ));
    }

    public function sendTripSmsAction(Request $req){
      $driver = Driver::find($req->clubId);
      $emailAddress = "0044".substr('07949846378', 1).'@mail.mightytext.net';
      $emailAddress = str_replace(" ","",$emailAddress);
      $message =$req->sms;
      Mail::to($emailAddress)->queue(new SendPlainSMS($message));
      return redirect(route('transportation.current.trips'))->with('message',"Succesfully Sent SMS");
    }


    public function proceedPayment($clubId,$date){
      $taxYears = TaxYear::all();
      $trips = Transportation::with(['driver','staff','booking'])
          ->where('date',$date)->where('clubId',$clubId)
          ->orderBy('order')
          ->get();
      $driver = Driver::find($trips[0]->driverId);
      return view('transportation.proceedToPayment',compact('trips','driver','date','taxYears'));
    }

    public function proceedPaymentSave(Request $req){
      $trips = Transportation::where('status',1)->where('date',$req->date)->where('clubId',$req->clubId);
      $trips->update([
        'taxYear' =>$req->taxYear,
        'payeeWeek' =>$req->payeeWeek,
      ]);

      return redirect(route('transportation.proceed.payment',[$req->clubId,$req->date]));
    }
    public function proceedPaymentAction($clubId,$date){
      $trips = Transportation::where('status',1)->with(['driver','staff','booking'])
          ->where('date',$date)->where('clubId',$clubId)
          ->orderBy('order')
          ->get();

      foreach ($trips as $trip) {
        $trip->update(['status'=>0]);
        TransportComplete::updateOrCreate([
          'tripId'  =>$trip->tripId,
          'driverId'  =>$trip->driverId,
          'taxYear'  =>$trip->taxYear,
          'payeeWeek'  =>$trip->payeeWeek,
        ]);
      }

      return redirect(route('transportation.current.trips'))->with('message',"Succesfully Moved to Completed Trips");
    }
}

