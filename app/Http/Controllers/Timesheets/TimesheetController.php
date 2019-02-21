<?php

namespace App\Http\Controllers\Timesheets;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Timesheet;
use App\Models\TimesheetLog;
use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPlainSMS as SendPlainSMSEvent;
use App\Models\ClientUnit;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\ClientUnitSchedule;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\BookingLog;
use Auth;
use DB;

class TimesheetController
{
    public function list(){
      $shifts = Shift::where('status',1)->get();
      $units = ClientUnit::where('status',1)->orderBy('name','ASC')->get();
      $staffs = Staff::where('status', 1)->orderBy('forname','ASC')->get();
      $categories = StaffCategory::where('status',1)->get();
      // FUTURE BOOKING COUNT
      $bookSumry = Timesheet::join('bookings','bookings.bookingId','timesheets.bookingId')
                    ->whereBetween('bookings.date',[date('Y-m-d', strtotime('-15 day')),date('Y-m-d')])
                    ->select('bookings.date', 
                        DB::raw('sum(Case When status = 2 Then 1 Else 0 End) as verifiedCount'),
                        DB::raw('sum(Case When status <> 2 Then 1 Else 0 End) as uncheckedCount'))
                    ->groupBy('date')
                    ->get();
        // FUTURE BOOKING COUNT
        /*HCA RGN COUNT*/
        $hcaRgnCount = Timesheet::join('bookings','bookings.bookingId','timesheets.bookingId')
                    ->whereBetween('bookings.date',[date('Y-m-d', strtotime('-60 day')),date('Y-m-d')])
                    ->select(DB::raw('sum(Case When staffId IS NULL AND categoryId=2 Then 1 Else 0 End) as hcaCount'),DB::raw('sum(Case When staffId IS NULL AND categoryId=1 Then 1 Else 0 End) as rgnCount'))
                    ->where('status',2)->get();
        /*HCA RGN COUNT*/
    //   return $hcaRgnCount;

      return view('timesheets.list',compact('shifts', 'units', 'staffs', 'categories','bookSumry','hcaRgnCount'));
    }

    public function data(Request $request){
        $query = Timesheet::with(['booking','booking.shift','booking.unit','booking.category','booking.staff']);
        $query->select('bookings.*','timesheets.timesheetId','timesheets.startTime','timesheets.endTime','timesheets.status','timesheets.number','timesheets.verifiedBy');
        $query->Join('bookings','bookings.bookingId','timesheets.bookingId');

        if(empty($request->columns[1]['search']['value'])){
          $query->whereDate('bookings.date','=',date('Y-m-d',strtotime("-1 days")));
        }
        $result = $query->orderBy('bookings.date','ASC');
        $data = Datatables::of($query);
        $data->addIndexColumn();

        $data->filterColumn('booking.date', function($timesheet, $keyword) {
            $dates = explode(" - ",$keyword);
            $timesheet->whereBetween('bookings.date',[date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))]);
        });
        $data->editColumn('booking.date',function($timesheet){
            if($timesheet->booking->date){ return date('d-M-Y, D',strtotime($timesheet->booking->date)); }
        });

        $data->filterColumn('booking.shift.name', function($booking, $keyword) {
            $booking->where('bookings.shiftId', $keyword);
        });

        $data->editColumn('booking.unit.name',function($timesheet){
            return $timesheet->booking->unit->alias;
        });

        $data->filterColumn('booking.unit.name', function($booking, $keyword) {
            $booking->where('bookings.unitId', $keyword);
        });

        $data->filterColumn('booking.staff.forname', function($booking, $keyword) {
            $booking->where('bookings.staffId', $keyword);
        });

        $data->editColumn('booking.staff.forname',function($timesheet){
            return $timesheet->booking->staff->forname." ".$timesheet->booking->staff->surname;
        });

        $data->filterColumn('booking.category.name', function($booking, $keyword) {
            $booking->where('bookings.categoryId', $keyword);
        });

        $data->editColumn('startTime',function($timesheet){
            if($timesheet->startTime){ return date('H:i',strtotime($timesheet->startTime)); }
        });
        $data->editColumn('endTime',function($timesheet){
            if($timesheet->endTime){ return date('H:i',strtotime($timesheet->endTime)); }
        });
        $data->editColumn('status',function($timesheet){
            if($timesheet->status ==0){
               if($timesheet->verifiedBy){
                    return "<span class='label label-danger'>Bounced</span>";
               }else{
                    return "<span class='label label-primary'>New</span>";
               }
            }
            if($timesheet->status ==1){ return "<span class='label label-warning'>Checked</span>";}
            if($timesheet->status ==2){ return "<span class='label label-success'>Verified</span>";}
        });
        $data->filterColumn('status', function($booking, $keyword) {
            $booking->where('status', $keyword);
        });
        $data->editColumn('tslog',function($timesheet){
            return "<button class='btn btn-primary btn-xs mrs openLogModal name='".$timesheet->booking->staff->forname." ".$timesheet->booking->staff->surname.  
             "' category='".$timesheet->booking->category->name."'  booking='".$timesheet->booking->bookingId."' shift='".$timesheet->booking->shift->name."' unit='".$timesheet->booking->unit->name."'>Shift Log</button>";
       
        });

        $data->filterColumn('status', function($booking, $keyword) {
            switch ($keyword) {
                case 4:
                    $booking->where('status', 0)->whereNotNull('verifiedBy');
                    break;
                case 0:
                    $booking->where('status', $keyword)->whereNull('verifiedBy');;
                    break;
                default:
                    $booking->where('status', $keyword);
                    break;
            }

        });
        $data->editColumn('actions',function($timesheet){
            switch ($timesheet->status) {
                case 0:
                    $html = "<a href='javascript:void(0)' id=".encrypt($timesheet->timesheetId)." class='btn btn-success btn-xs mrs openCheckin' style='margin: 0 5px;'><i class='fa fa-pencil'></i> CheckIn</a>";
                    break;
                case 1:
                    $html = "<a href='javascript:void(0)' id=".encrypt($timesheet->timesheetId)." class='btn btn-primary btn-xs mrs openVerfy' style='margin: 0 5px;'>Verify</a>";
                    break;
                case 2:
                    $html = "<a href='javascript:void(0)' id=".encrypt($timesheet->timesheetId)." class='btn btn-primary btn-xs mrs openVerfy' style='margin: 0 5px;'>Verify</a>";
                    break;
                    break;
                case 3:
                    $html = "<a href='javascript:void(0)' id=".encrypt($timesheet->timesheetId)." class='btn btn-success btn-xs mrs openCheckin' style='margin: 0 5px;'><i class='fa fa-pencil'></i> CheckIn</a>";
                    break;
            }
            return $html;
        });
        $tableData =$data->make(true);
        return $tableData;
    }

    public function getTimesheet(){
        $timesheet = Timesheet::with([
          'booking','booking.unit','booking.shift',
          'booking.category','booking.shift','booking.staff',
        ])->find(decrypt(request('timesheetId')));

        $timesheet['scheduleStaffHours'] = ClientUnitSchedule::where('clientUnitId',$timesheet->booking->unitId)
                                        ->where('staffCategoryId',$timesheet->booking->categoryId)
                                        ->where('shiftId',$timesheet->booking->shiftId)
                                        ->first();
        return $timesheet;
    }

    public function checkInAction(Request $request){
        $timesheet = Timesheet::find($request->timesheetId);
        $timesheet->number = $request->number;
        $timesheet->timesheetRefId = $request->timesheetRefId;
        $timesheet->comments  = $request->comments;
        $timesheet->breakHours = $request->breakHours;
        $timesheet->staffHours = $request->staffHours;
        $timesheet->unitHours = $request->unitHours;
        $timesheet->startTime = date('H:i:s',strtotime($request->startTime));
        $timesheet->endTime = date('H:i:s',strtotime($request->endTime));
        $timesheet->status = 1; //checked
        $timesheet->checkInBy = auth()->user()->adminId;
        $timesheet->save();

        BookingLog::create([
          'bookingId' =>$timesheet->bookingId,
          'content' =>"<span class='logHgt'>Timesheet Marked </span> as <strong>CheckIn</strong> ",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        return 1;
    }

    public function verifyAction(Request $request){
        $timesheet = Timesheet::find($request->timesheetId);
        $timesheet->comments  = $request->comments;
        $timesheet->status = 2; //verified
        $timesheet->verifiedBy = auth()->user()->adminId;
        $timesheet->save();

        BookingLog::create([
          'bookingId' =>$timesheet->bookingId,
          'content' =>"<span class='logHgt'>Timesheet Marked </span> as <strong>Verified</strong> ",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        $payment = Payment::where('bookingId',$request->bookingId)->first();
        if(empty($payment)){
            $this->generatePaymentAndInvoice($timesheet);
        }
        return 1;
    }

    public function revertAction(Request $request){
        $timesheet = Timesheet::find($request->timesheetId);
        $timesheet->status = 0; //reverted
        $timesheet->comments = $request->remarks;
        $timesheet->verifiedBy = auth()->user()->adminId;
        $timesheet->save();
        BookingLog::create([
          'bookingId' =>$timesheet->bookingId,
          'content' =>"<span class='logHgt'>Timesheet Marked </span> as <strong>Reverted</strong> ",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        return 1;
    }

    private function generatePaymentAndInvoice($booking){
        $payment = Payment::firstOrCreate(['bookingId' =>$booking->bookingId]);

        $payment->update([
            'timesheetId' =>$booking->timesheetId,
            'status' =>0,
        ]);

        $invoice = Invoice::firstOrCreate(['bookingId' =>$booking->bookingId]);
        
        $invoice->update([
            'timesheetId' =>$booking->timesheetId,
            'status' =>0,
        ]);
        return true;
    }

    public function getTimesheetLog(Request $req){
        $logs = BookingLog::with('admin')->where('bookingId',decrypt(request('bookingid')))->latest()->get();
        $html = view('timesheets.logTemplate',compact('logs'));
        return $html;
    }

    public function verifySMSAction(Request $req){
        $timesheetID = $req->timesheetId;
        $timesheet = Timesheet::with(['booking','booking.staff','booking.shift','booking.unit'])
                        ->join('bookings','bookings.bookingId','timesheets.bookingId')
                        ->where('timesheetId',$timesheetID)->first();

                        $date = date('d-M-Y',strtotime($timesheet->date));
        $shiftName = $timesheet->booking->shift->name;
        $staffName = $timesheet->booking->staff->forname." ".$timesheet->booking->staff->surname;
        $unitName = $timesheet->booking->unit->name;

        $emailAddress = "0044".substr($timesheet->booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);

        $smsMessage = "Dear ".$staffName.", we have successfully checked in ".$date.", ".$unitName.", ".$shiftName." , timesheet with our payroll system.Thank you Nurses Group.";
        Mail::to($emailAddress)->queue(new SendPlainSMSEvent($smsMessage));
        $timesheet->update([
                'smsAcceptedStatus'    =>1
            ]);
        BookingLog::create([
          'bookingId' =>$timesheet->bookingId,
          'content' =>"Timesheet <span class='logHgt'> Accepted SMS </span> <strong>Sent</strong> ",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);
        
        return ['status'=>true];
    }

    public function rejectSMSDetails(Request $req) {
        $timesheetID = $req->timesheetId;
        $timesheet = Timesheet::with(['booking','booking.staff','booking.shift','booking.unit'])
                        ->join('bookings','bookings.bookingId','timesheets.bookingId')
                        ->where('timesheetId',$timesheetID)->first();
        $date = date('d-M-Y',strtotime($timesheet->booking->date));
        $shiftName = $timesheet->booking->shift->name;
        $staffName = $timesheet->booking->staff->forname." ".$timesheet->booking->staff->surname;
        $unitName = $timesheet->booking->unit->name;
        $message = "Dear ".$staffName.", we are sorry to inform that the timesheet for ".$date.", ".$unitName.", ".$shiftName." , couldn’t log into our system. ……. Thank you Nurses Group";
        return ['data'=>$message];
    }

    public function verifyResendSMSAction(Request $req) {
        $timesheetID = $req->timesheetId;
        $messages = $req->messages;
        $timesheet = Timesheet::with(['booking','booking.staff','booking.shift','booking.unit'])
                        ->join('bookings','bookings.bookingId','timesheets.bookingId')
                        ->where('timesheetId',$timesheetID)->first();

        $emailAddress = "0044".substr($timesheet->booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);

        $smsMessage = $messages;
        Mail::to($emailAddress)->queue(new SendPlainSMSEvent($smsMessage));
        $timesheet->update([
                'smsRejectedStatus'    =>1
            ]);
        BookingLog::create([
          'bookingId' =>$timesheet->bookingId,
          'content' =>"Timesheet <span class='logHgt'> Reject SMS </span> <strong>Sent</strong> ",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);
        return ['status'=>true];
    }


}

?>
