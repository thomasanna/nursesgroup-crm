@extends('layouts.template')
@section('title','Timesheets')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left" id="pageTitle">
        <h1 class="box-title">Timesheets</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
  </div>
</div>
<!-- Header Navigation -->
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">

        <div class="row bgDarkBlue">
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Date</label>
                    <input class="form-control datepicker" id="searchDate" type="text" placeholder="Date" autocomplete="off" />
                </div>
              </div>
              <div class='col-sm-1'>
                <div class='form-group'>
                    <label for="date"> Category</label>
                    <select class="form-control select2" id="searchCategory">
                      <option value=""></option>
                      @foreach($categories as $category)
                      <option value="{{$category->categoryId}}">{{$category->name}}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Unit</label>
                    <select class="form-control select2" id="searchUnit">
                      <option value=""></option>
                      @foreach($units as $unit)
                      <option value="{{$unit->clientUnitId}}">{{ $unit->alias or $unit->name }}</option>
                      @endforeach
                    </select>
                    <p class="error">Unit is required</p>
                </div>
              </div>
              <div class='col-sm-1'>
                <div class='form-group'>
                    <label for="date">Shift</label>
                    <select class="form-control select2" id="searchShift">
                      <option value=""></option>
                      @foreach($shifts as $shift)
                      <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
                      @endforeach
                    </select>
                    <p class="error">Shift is required</p>
                </div>
              </div>
               <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Staff</label>
                    <select class="form-control select2" id="searchStaff">
                      <option value=""></option>
                      @foreach($staffs as $staff)
                      <option value="{{$staff->staffId}}">{{$staff->forname." ".$staff->surname}}</option>
                      @endforeach
                    </select>
                    <p class="error">Staff is required</p>
                </div>
              </div>

              

              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date"> TS Status</label>
                    <select class="form-control select2" id="tsStatus">
                      <option value=""></option>
                      <option value="0">New</option>
                      <option value="4">Bounced</option>
                      <option value="1">Checked</option>
                      <option value="2">Verified</option>
                    </select>
                </div>
              </div>
              <div class='col-sm-1'>
                <div class='form-group'>
                    <a href="javascript:void(0)" class="btn btn-warning" style="margin-top:25px;" id="searchReset">Reset</a>
                </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="row comingDays top10">
  <div class="futrRest">
  @foreach($bookSumry as $bookSumryItm)
    @if($bookSumryItm->uncheckedCount > 3)
    <span><div class="smalHead">{{date('d-D',strtotime($bookSumryItm->date))}}</div>
    <button class="btn btn-danger btn-xs mrs m-r-10">{{$bookSumryItm->verifiedCount}} | {{$bookSumryItm->uncheckedCount}} </button></span>
    @elseif($bookSumryItm->uncheckedCount <= 3 && $bookSumryItm->uncheckedCount > 0 )
    <span><div class="smalHead">{{date('d-D',strtotime($bookSumryItm->date))}}</div><button class="btn btn-warning btn-xs mrs m-r-10">{{$bookSumryItm->verifiedCount}} | {{$bookSumryItm->uncheckedCount}} </button></span>
    @else
    <span><div class="smalHead">{{date('d-D',strtotime($bookSumryItm->date))}}</div><button class="btn btn-success btn-xs mrs m-r-10">{{$bookSumryItm->verifiedCount}} | {{$bookSumryItm->uncheckedCount}} </button></span>
    @endif
   @endforeach
  </div>
  <div class="brdrLeft"></div>
  <div class="hcaRgnCount">
    @if(isset($hcaRgnCount->hcaCount))
      <span><div class="smalHead">HCA</div><button class="btn btn-primary btn-xs mrs m-r-10 pull-top">{{$hcaRgnCount->hcaCount}}</button></span>
    @else
    <span><div class="smalHead">HCA</div><button class="btn btn-success btn-xs mrs m-r-10 pull-top">0</button></span>
    @endif

    @if(isset($hcaRgnCount->rgnCount))
      <span><div class="smalHead">RGN</div><button class="btn btn-primary btn-xs mrs m-r-10 pull-top">{{$hcaRgnCount->rgnCount}}</button></span>
    @else
    <span><div class="smalHead">RGN</div><button class="btn btn-success btn-xs mrs m-r-10 pull-top">0</button></span>
    @endif
  </div>
</div>
<!-- Main row -->
<div class="row posAdjst">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('timesheet.data.list')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:2%;">#Id</th>
              <th style="width:6%;">Date</th>
              <th style="width:3%;">Shift</th>
              <th style="width:6%;">Unit</th>
              <th style="width:12%;">Staff</th>
              <th style="width:5%;">Category</th>
              <th style="width:12%;">Timesheet Number</th>
              <th style="width:4%;">TS Status</th>
              <th style="width:4%;">Log</th>
              <th style="width:8%;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>



@include('timesheets.checkIn')
@include('timesheets.verifyModal')
@include('timesheets.rejectionSmsModal')
@include('timesheets.timesheetLog')
@endsection

@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('public/js/select2.full.min.js')}}"></script>
<script src="{{asset('public/js/jquery.mCustomScrollbar.js') }}"></script>

<script src="{{asset('public/js/pages/timesheets/index.js')}}?{{time()}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.mCustomScrollbar.css') }}?{{ time() }}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
