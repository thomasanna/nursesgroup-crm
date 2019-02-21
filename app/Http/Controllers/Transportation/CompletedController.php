<?php

namespace App\Http\Controllers\Transportation;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\TransportComplete;
use App\Models\Transportation;
use App\Models\TransportPayment;
use App\Models\TransportArchive;
use App\Models\Admin;

use DB;
use Log;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendTransportRemittenceAdvice;


class CompletedController
{
	public function viewCompleted(){
        return view('transportation.completed.list');
    }

    public function dataCompleted(Request $request){
        $query = TransportComplete::with(['driver','trip'])->Join('transportations','transportations.tripId','transport_completes.tripId')
        		->Join('staffs','staffs.staffId','transportations.staffId')
        		->select("transport_completes.*","transportations.aggreedAmount")
        		->addSelect(DB::raw('group_concat(ng_staffs.forname) as staffNames'))
        		->addSelect(DB::raw('count(*) as noOfTrips'))
        		->groupBy('transport_completes.driverId')
        		->groupBy('transport_completes.payeeWeek');
        $data = Datatables::of($query);
        $data->addIndexColumn();
        $data->editColumn('payeeWeek',function($trip){
            return "Week <strong>".$trip->payeeWeek."</strong>";
        });
        $data->editColumn('aggreedAmount',function($trip){
            return number_format($trip->aggreedAmount,2);
        });
        $data->editColumn('status',function($trip){
            return "<button class='btn btn-xs mrs btn-primary'>Active</button>";
        });
        $data->editColumn('actions',function($trip){
          $html = "";
          $html .= "<a href='".route('transportation.review.completed.trips',[$trip->payeeWeek,$trip->driverId])."' class='btn btn-success btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Review</a>";

          if($trip->raGenerated == 1){
            $rcBtn = "success";
          }else{
            $rcBtn = "warning";
          }

          $html .= "<a href=''
          class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> RA</a>";
          if($trip->emailSent == 1){
            $rcBtn = "success";
          }else{
            $rcBtn = "warning";
          }

          $html .= "<a href=''
          class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Email</a>";
          if($trip->paymentRecorded == 1){
            $rcBtn = "success";
          }else{
            $rcBtn = "warning";
          }

          $html .= "<a href='".route('transportation.record.payment',[$trip->driverId,$trip->payeeWeek])."'
          class='btn btn-".$rcBtn." btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Record Payment</a>";
          return $html;
      	});
        $tableData =$data->make(true);
        return $tableData;
    }

    public function reviewCompleted($week,$driverId){
    	$compTrips = TransportComplete::Join('transportations','transportations.tripId','transport_completes.tripId')
          ->Join('bookings','bookings.bookingId','transportations.bookingId')
    			->where('transport_completes.driverId',$driverId)
    			->where('transport_completes.payeeWeek',$week)
    			->selectRaw('*,SUM(CASE WHEN direction = 1 THEN 1 END) AS outCount,SUM(CASE WHEN direction = 2 THEN 1 END) AS inCount')
          ->selectRaw('SUM(ng_bookings.distenceToWorkPlace) as totalMiles')
          ->groupBy('transportations.date')
    			->get();
    	return view('transportation.completed.review',compact('compTrips'));
    }

    public function extractCompleted(){
      $trips = Transportation::where('driverId',request('driverId'))
          ->where('payeeWeek',request('payeeWeek'))
          ->where('date',request('date'))
          ->get();
      return view('transportation.completed.extractLine',compact('trips'));
    }

    public function markAsToPay($driverId,$week,$date){
      TransportComplete::where('driverId',$driverId)
          ->where('payeeWeek',$week)
          ->whereHas("trip", function($q) use($date){
             $q->where("date",$date);
          })
          ->update(['proceedToPay'=>1]);
      return redirect(route('transportation.review.completed.trips',[$week,$driverId]))->with('message',"Succesfully marked as to Pay");
    }

    public function moveTripWeek($driverId,$week,$date){
      TransportComplete::where('driverId',$driverId)
          ->where('payeeWeek',$week)
          ->whereHas("trip", function($q) use($date){
             $q->where("date",$date);
          })
          ->increment('payeeWeek');
      return redirect(route('transportation.review.completed.trips',[$week,$driverId]))->with('message',"Succesfully moved to next week");
    }

    public function showRa($driverId,$week){
      $compTrips = $this->getTrips($driverId,$week);
      TransportComplete::where('driverId',$driverId)->where('payeeWeek',$week)->update(['raGenerated' =>1]);
      return view('transportation.completed.remittenceAdvice',compact('compTrips'));
    }

    public function downloadRaPdf($driverId,$week){
      $compTrips = $this->getTrips($driverId,$week);
      $pdf = PDF::loadView('email.transport.remittanceAdvice',compact('compTrips'))->setPaper('A4', 'landscape');
      return $pdf->download('trip-'.$driverId.'-'.$week.'-ra.pdf');
    }

    public function sendEmailRa($driverId,$week){
      $compTrips = $this->getTrips($driverId,$week);
      Mail::to('jishadp369@gmail.com')->cc('mcjobi@nursesgroup.co.uk')->queue(new SendTransportRemittenceAdvice($compTrips));
      TransportComplete::where('driverId',$driverId)->where('payeeWeek',$week)->update(['emailSent' =>1]);
      return redirect(route('transportation.ra.view',[$driverId,$week]))->with('message',"Succesfully generated PDF");
    }

    public function getTrips($driverId,$week){
      $trips = TransportComplete::Join('transportations','transportations.tripId','transport_completes.tripId')
          ->Join('bookings','bookings.bookingId','transportations.bookingId')
          ->where('transport_completes.driverId',$driverId)
          ->where('transport_completes.payeeWeek',$week)
          ->orderBy('order')
          ->get();
      return $trips;
    }

    public function recordPayment($driverId,$week){
      $compTrips = $this->getTrips($driverId,$week);
      $admins = Admin::all();
      return view('transportation.completed.recordPayment',compact('compTrips','admins'));
    }

    public function recordPaymentAction(Request $req){
      $trips = TransportComplete::where('driverId',request('driverId'))->where('payeeWeek',request('payeeWeek'))->get();

      foreach ($trips as $trip) {
        TransportPayment::firstOrCreate([
            'completedTransId' => $trip->completedTransId,
            'paymentDate' => date('Y-m-d',strtotime($req->paymentDate)),
            'bankId' => $req->bankId,
            'transactionNumber' => $req->transactionNumber,
            'handledBy' => $req->handledBy,
        ]);

        $trip->update(['paymentRecorded' =>1]);

        TransportArchive::firstOrCreate([
          'tripId'  =>$trip->tripId
        ]);
      }
      return redirect(route('transportation.completed.trips'))->with('message',"Succesfully Payment Recorded");
    }
}
