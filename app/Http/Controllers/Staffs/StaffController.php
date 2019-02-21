<?php

namespace App\Http\Controllers\Staffs;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Staff;
use App\Models\StaffCategory;
use App\Models\StaffTransport;
use App\Models\StaffBand;
use App\Models\Branch;
use App\Models\BranchZone;
use App\Models\Client;
use App\Models\ClientUnit;
use App\Models\StaffPreferredUnit;

use PDF;
use Log;
use Storage;
use Hash;


class StaffController
{
    public function indexActive($searchKeyword=""){
      return view('staffs.index_active',compact('searchKeyword'));
    }
    public function indexInactive(){
      return view('staffs.index_inactive');
    }
    public function indexTerminated(){
      return view('staffs.index_terminated');
    }

    public function dataStaffActive(Request $req){
      return Datatables::of(Staff::with('category','band','branch')->where('status',1)->orderBy('forname'))
        ->addIndexColumn()
        ->editColumn('email',function($staff){
          $html ="<a href='mailto:$staff->email'>".$staff->email."<a>";
          return $html;
        })
        ->editColumn('mobile',function($staff){
          $html ="<a href='tel:$staff->mobile'>".$staff->mobile."<a>";
          return $html;
        })
        ->editColumn('actions',function($staff) use($req){
          // REFERENCE COLORS
          $searchKeyword = $req->search['value'];
          // Log::info($req->search['value']);
          if($staff->referenceProgress==1) $referenceProgress = 'danger';
          if($staff->referenceProgress==2) $referenceProgress = 'warning';
          if($staff->referenceProgress==3) $referenceProgress = 'success';
          // REFERENCE COLORS
          if($staff->rtwProgress==1) $rtwProgress = 'danger';
          if($staff->rtwProgress==2) $rtwProgress = 'warning';
          if($staff->rtwProgress==3) $rtwProgress = 'success';
          // REFERENCE COLORS
          if($staff->dbsProgress==1) $dbsProgress = 'danger';
          if($staff->dbsProgress==2) $dbsProgress = 'warning';
          if($staff->dbsProgress==3) $dbsProgress = 'success';
          // REFERENCE COLORS
          if($staff->trainingProgress==1) $trainingProgress = 'danger';
          if($staff->trainingProgress==2) $trainingProgress = 'warning';
          if($staff->trainingProgress==3) $trainingProgress = 'success';
          // PERSONAL
          if($staff->personalProgress==1) $personalProgress = 'danger';
          if($staff->personalProgress==2) $personalProgress = 'warning';
          if($staff->personalProgress==3) $personalProgress = 'success';


          $html = "<a href=".route('staffs.edit',[encrypt($staff->staffId),$searchKeyword])." class='btn btn-".$personalProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Personal</a>";
          $html .= "<a href=".route('staffs.references.home',[encrypt($staff->staffId),$searchKeyword])." class='btn btn-".$referenceProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> References</a>";
          $html .= "<a href=".route('staffs.right.to.work.form',[encrypt($staff->staffId),$searchKeyword])." class='btn btn-".$rtwProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Right To Work</a>";
          $html .= "<a href=".route('staffs.dbs.home',[encrypt($staff->staffId),$searchKeyword])." class='btn btn-".$dbsProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> DBS</a>";
          $html .= "<a href=".route('staffs.training.home',[encrypt($staff->staffId),$searchKeyword])." class='btn btn-".$trainingProgress." btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-plus'></i> Trainings</a>";
          $html .= "<a href=".route('staffs.send.profile',[encrypt($staff->staffId),$searchKeyword])." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;'>View Profile</a>";
          return $html;
        })->make(true);
    }

    public function dataStaffInactive(Request $req){
      return Datatables::of(Staff::with('category','band','branch')->where('status',0)->orderBy('forname'))
        ->addIndexColumn()
        ->editColumn('actions',function($staff){
          $html = "<a href=".route('staffs.edit',encrypt($staff->staffId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Personal</a>";
          $html .= "<a href=".route('staffs.delete',encrypt($staff->staffId))." class='btn btn-danger btn-xs mrs'";
          $html .= "token=".csrf_token()."><i class='fa fa-pencil'></i> Terminate</a>";
          return $html;
        })->make(true);
    }

    public function dataStaffTerminated(Request $req){
      return Datatables::of(Staff::with('category','band','branch')->onlyTrashed()->orderBy('forname'))
        ->addIndexColumn()
        ->editColumn('actions',function($staff){
          $html = "<a href=".route('staffs.edit',encrypt($staff->staffId))." class='btn btn-primary btn-xs mrs' style='margin: 0 5px;''><i class='fa fa-pencil'></i> Edit</a>";
          return $html;
        })->make(true);
    }

    public function edit($id,$searchKeyword=""){
      $categories = StaffCategory::where('status',1)->get();
      $bands = StaffBand::where('status',1)->get();
      $branches = Branch::where('status',1)->get();
      $transports = StaffTransport::where('status',1)->get();
      $staff = Staff::find(decrypt($id));
      $zones = BranchZone::where('branchId',$staff->branchId)->where('status',1)->get();
      $units = ClientUnit::where('status',1)->get();
      $staffPclients = StaffPreferredUnit::where('staffId',decrypt($id))->pluck('unitId')->toArray();
      ($staff->niDocumentFile==null) ? $staff->niDocumentExist = 0 : $staff->niDocumentExist = 1;
      return view('staffs.edit',compact('staff','categories','bands','transports','branches','zones','units','staffPclients','searchKeyword'));
    }

    public function update(Request $request){
      $validator = $request->validate([
          'title' => 'required',
          'forname' => 'required',
          'surname' => 'required',
          
          'categoryId' => 'required',
          'email' => 'required|email',
          'mobile' => 'required',
          'whatsappNumber' => 'required',
          'gender' => 'required',
          'address' => 'required',
          'modeOfTransport' => 'required',
          'pickupLocation' => 'required',
          'zoneId' => 'required',
          'bandId' => 'required',
          'paymentMode' => 'required',
          'payRateWeekday' => 'required',
          'payRateWeekNight' => 'required',
          'payRateWeekendDay' => 'required',
          'payRateWeekendNight' => 'required',
          'payRateSpecialBhday' => 'required',
          'payRateSpecialBhnight' => 'required',
          'payRateBhday' => 'required',
          'payRateBhnight' => 'required',
      ]);

      $staff = Staff::find(decrypt($request->pkId));
      $staff->update([
        'username' => $request->username,
        'password' => Hash::make($request->password),
      ]);
      $staff->update($request->except('_token','pkId','username','password'));

      $input = $request->except('_token','pkId','photo','niDocumentFile','username','password');
      //return $input;

      if ($request->hasFile('photo')) {
        if($staff->photo){
          $exists = Storage::disk('local')->exists('staff/photo'.$staff->photo);
          if($exists){
            Storage::disk('local')->delete('staff/photo/'.$staff->photo);
          }
        }
        $filename = time()."_".str_random(40).'.jpg';
        $request->photo->storeAs('staff/photo/',$filename);
        $input['photo'] = $filename;
      }

      if ($request->hasFile('niDocumentFile')) {
        if($staff->niDocumentFile){
          $exists = Storage::disk('local')->exists('staff/staff_ni/'.$staff->niDocumentFile);
          if($exists){
            Storage::disk('local')->delete('staff/staff_ni/'.$staff->niDocumentFile);
          }
        }

        $filename = time()."_".str_random(40).'.pdf';
        $request->niDocumentFile->storeAs('staff/staff_ni',$filename);
        $input['niDocumentFile'] = $filename;
      }


      if($request->has('dateOfBirth')){
        $input['dateOfBirth'] = date('Y-m-d',strtotime($request->dateOfBirth));
      }
      if($request->has('joinedDate')){
        $input['joinedDate'] = date('Y-m-d',strtotime($request->joinedDate));
      }
      if($request->has('nmcPinExpiryDate')){
        $input['nmcPinExpiryDate'] = date('Y-m-d',strtotime($request->nmcPinExpiryDate));
      }
      if($request->has('nmcPinReValidationDate')){
        $input['nmcPinReValidationDate'] = date('Y-m-d',strtotime($request->nmcPinReValidationDate));
      }
      $staff->update($input);
      if($request->unitIds){
        StaffPreferredUnit::where('staffId',decrypt($request->pkId))->delete();
        foreach ($request->unitIds as $value) {
          StaffPreferredUnit::create(['staffId'=>decrypt($request->pkId),'unitId'=>$value]);
        }
      }

      switch ($staff->status) {
        case 1:
          return redirect(route('staffs.home.active'))->with('message','Succesfully updated staff !!');
          break;
        case 0:
          return redirect(route('staffs.home.inactive'))->with('message','Succesfully updated staff !!');
          break;
      }
    }

    public function delete($id){
      $staff = Staff::find(decrypt($id));
      $staff->delete();
      session()->flash('message', 'Succesfully terminated staff !!');
      return redirect(route('staffs.home.inactive'));
    }

    public function changeStaffProgress(Request $req){
      $staff =Staff::find(decrypt($req->staff));
      switch ($req->page) {
        case 1:
          $staff->referenceProgress = $req->progress;
          break;
        case 2:
          $staff->rtwProgress = $req->progress;
          break;
        case 3:
          $staff->dbsProgress = $req->progress;
          break;
        case 4:
          $staff->trainingProgress = $req->progress;
          break;
        case 5:
          $staff->personalProgress = $req->progress;
          $staff->save();
          session()->flash('message', 'Succesfully updated Progress !!');
          return route('staffs.home.active');
          break;
      }
      $staff->save();
      session()->flash('message', 'Succesfully updated Progress !!');
      return 1;
    }

    public function sendProfile(Request $request,$id)  {

        $staff = Staff::with(['category','training','training.course'])->find(decrypt($id));
        $pdf = PDF::loadView('bookings.pdf.profile',compact('staff'))
                  ->setPaper('A4','potriat');
        session()->forget('checkPayeePayment');
        return $pdf->stream('selfie-ra.pdf');
    }
}
