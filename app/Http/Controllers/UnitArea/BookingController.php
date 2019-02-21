<?php

namespace App\Http\Controllers\UnitArea;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Timesheet;
use App\Models\BookingUnitStatus;
use App\Models\BookingLog;
use App\Models\TimesheetLog;
use App\Models\Shift;
use App\Models\ClientUnit;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\Booking;
use App\Models\Admin;
use Auth;

class BookingController{
  public function index(){
    $categories = StaffCategory::where('status',1)->get();
  	$shifts = Shift::where('status',1)->get();
  	return view('unitArea.bookings',compact(['categories','shifts']));
  }

  public function data(Request $req){
    $unitId = Auth::guard('unit')->user()->clientUnitId;
    $query = Booking::with('category','staff','unit','shift')->where('unitId',$unitId);

    if(empty($req->columns[1]['search']['value'])){
      $query->whereDate('bookings.date','=',date('Y-m-d',strtotime("-1 days")));
    }
    $data = Datatables::of($query);
    $data->addIndexColumn();

    $data->filterColumn('booking.date', function($payment, $keyword) {
      $dates = explode(" - ",$keyword);
      $payment->whereBetween('bookings.date',[date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))]);
    });

    $data->editColumn('booking.date',function($payment){
        if($payment->date) { return date('d-M-Y, D',strtotime($payment->date)); }
    });

    $data->filterColumn('shift.name', function($payment, $keyword) {
      $payment->where('bookings.shiftId', $keyword);
    });

    $data->filterColumn('category.name', function($payment, $keyword) {
        $payment->where('bookings.categoryId', $keyword);
    });

    $data->editColumn('actions',function($payment){
      $html = "<a href=".route('users.edit',encrypt($payment->adminId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Edit</a>";
      $html .= "<a action=".route('users.delete',encrypt($payment->adminId))." class='btn btn-danger btn-xs mrs'";
      $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Delete</a>";
      return $html;
    });
    $tableData =$data->make(true);
    return $tableData;

  }
  public function newAction(Request $req) {
    // for ($i=0; $i < count($req->shiftId); $i++) {
      for ($i=0; $i < $req->numbers; $i++) {
        $booking = Booking::create([
          'categoryId'=>$req->categoryId,
          'date'=>date('Y-m-d',strtotime($req->requestedDate)),
          'unitId'=>Auth::guard('unit')->user()->clientUnitId,
          'unitStatus'=>4 ,
          'shiftId'=>$req->shiftId,
          'type'=>2
        ]);
      }
    // }

    return ['status'=>true];
  }

  public function new(){
    $categories = StaffCategory::where('status',1)->get();
    $shifts = Shift::where('status',1)->get();
    return view('unitArea.newBooking',compact(['categories','shifts']));
  }

}
