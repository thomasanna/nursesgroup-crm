@extends('layouts.template')
@section('title','Search Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10 mainHead" search="{{$searchKeyword}}" page="{{$page}}">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Search Staff</h4>
    </div>
    @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px;float: left;">{{session()->get('message')}}</span>@endif
    <div class="pull-left shftSmry">
      <ul class="smallHdr">
        <li>#{{$booking->bookingId}}
        </li>
        <li>|&nbsp;
          @if($booking->isWeekend)
          <span class='redFont'>{{date('d-M-Y, D',strtotime($booking->date))}}</span>
          @else
          {{date('d-M-Y, D',strtotime($booking->date))}}
          @endif
        </li>
        <li>|<strong>&nbsp;{{empty($booking->unit->alias)?$booking->unit->name:$booking->unit->alias}}</strong></li>
        <li>|&nbsp;
          @if($booking->categoryId ==1)
          <span class='redFont'>{{$booking->category->name}}</span>
          @elseif($booking->categoryId ==3)
          <span class='yellowFont'>{{$booking->category->name}}</span>
          @else
          {{$booking->category->name}}
          @endif
        </li>
        <li>|&nbsp;{{$booking->shift->name}}</li>
        <li>|&nbsp;{{date('H:i',strtotime($booking->startTime))}}</li>
        <li>|&nbsp;{{date('H:i',strtotime($booking->endTime))}}</li>
      </ul>
    </div>
    <div class="pull-right">
      <a class="btn btn-success sendSms  @if(!session()->has('checkState')||(count(session()->get('checkState'))==0)) hidden @endif"
        data-target="#previewSms" data-toggle="modal">
        <i class="fa  fa-plus" aria-hidden="true"></i>Preview SMS
      </a>
      @if($page =="current")
      <a href="{{route('booking.current',$searchKeyword)}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
      @endif
      @if($page =="all")
      <a href="{{route('booking.all',$searchKeyword)}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
      @endif
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-10">
    <form action="{{route('booking.step.one.changestatus')}}" method="post" id="changeStaffStatus">
      <input type="hidden" name="bookingId" value="{{encrypt($booking->bookingId)}}">
      <input type="hidden" id="staffId" value="{{$booking->staffId}}">
      {!! csrf_field() !!}
      <div class='row'>
        <div class='col-sm-5 asnd'>
          <label for="date">Assigned Staff</label>
          @if(!empty($booking->staffId))
          <div class="asndtf">
            <strong>{{$booking->staff->forname." ".$booking->staff->surname}}</strong>
          </div>
          @endif
        </div>
        <div class="col-md-1">
          <a class="btn btn-primary cncl" href="{{route('booking.clear.staff',[encrypt($booking->bookingId),$page,$searchKeyword])}}">Clear</a>
        </div>
        <div class='col-sm-4'>
          <div class='form-group'>
              <label for="date">Staff Status</label>
              <select class="form-control select2" name="staffStatus">
                <option value=""></option>
                <option @if($booking->staffStatus==2) selected @endif value="2">Potential</option>
                <option @if($booking->staffStatus==3) selected @endif value="3">Confirmed</option>
                <option @if($booking->staffStatus==5) selected @endif value="5">Dummy</option>
              </select>
              <input type="hidden" name="search" value="{{$searchKeyword}}">
              <input type="hidden" name="page" value="{{$page}}">
          </div>
        </div>
        <div class='col-sm-1'>
          <input type="submit" class="btn btn-success pull-right" style="margin-top: 25px;" value="Save"/>
        </div>
        @if($booking->staffStatus==3 && $booking->confirmSmsStatus==0)
        <div class="col-sm-1">
          <a href="javascript:void(0)" class="sptSmsNow btn btn-danger pull-right"
          data-target="#previewSmsSpot" data-toggle="modal" style="margin-top: 25px;">SEND SMS</a>
        </div>
        @endif

        @if($booking->confirmSmsStatus==1)
          <div class="col-sm-1">
            <a href="javascript:void(0)" class="sptSmsNow btn btn-success pull-right"
            data-target="#previewSmsSpot" data-toggle="modal" style="margin-top: 25px;">SMS SENT</a>
          </div>
        @endif
      </div>
    </form>

    <div class="row">
      <div class='col-sm-5'>
      <label>Important Notes</label> : <span class="redFont">{{$booking->importantNotes}}</span>
      </div>
      <div class='col-sm-5'>
        <label for="date">Search Update</label>
        <input class="form-control" name="searchUpdate" type="text" value="{{$booking->searchUpdate}}"
        searchUpdateUrl="{{route('booking.search.update')}}" />
      </div>
    </div>

  </div>
</div>

<!-- Main row -->
<div class="row searchPage">
  <div class="col-md-12">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#avaiableStaffs">Avaibale Staffs</a></li>
      <li><a data-toggle="tab" class="permenentStaffs-tab" href="#permenentStaffs">Permanent Staffs</a></li>
      <li><a data-toggle="tab" class="priorityStaff-tab" href="#priorityStaffs">Priority Staffs</a></li>
      <li><a data-toggle="tab" class="previouslyWorked-tab" href="#previouslyWorked">Previously Worked Staffs</a></li>
      <li><a data-toggle="tab" class="inThisZone-tab" href="#inThisZone">Staffs in this zone</a></li>
    </ul>
    <input type="hidden" id="selectedStaffs" data-ids='{!!json_encode($selectedStaffs)!!}'>

    <div class="tab-content">
      <div id="avaiableStaffs" class="tab-pane fade in active">
        <input type="hidden" id="assignUrl" value="{{route('booking.assign.staff')}}">
        <input type="hidden" id="bookingId" value="{{encrypt($booking->bookingId)}}">
        <table id="avialableStaffTable" class="table table-striped table-bordered table-hover"
              fetch="{{route('booking.data.available.staff',[encrypt($booking->bookingId)])}}"
              token="{{ csrf_token() }}"
              checkUrl="{{route('booking.store.checked.state')}}">
          <thead>
            <tr>
              <th style="width:5%;">
                <input type='checkbox' class='checkAll' value='1' />
              </th>
              <th style="width:10%;">Name</th>
              <th style="width:7%;">Phone</th>
              <th style="width:6%;">Category</th>
              <th style="width:5%;">SMS</th>
              <th style="width:12%;">Actions</th>
              <th style="width:6%;">Log Status </th>
              <th style="width:6%;">Log</th>
              <th style="width:11%;">Avaiblity</th>
              <th style="width:10%;"> Current History</th>
              <th style="width:2%;">H/W</th>
            </tr>
          </thead>
        </table>
      </div>
      <div id="permenentStaffs" class="tab-pane fade in">
        <table id="permenentStaffsTable" class="table table-striped table-bordered table-hover"
              fetch="{{route('booking.data.permenent.staff',[encrypt($booking->bookingId)])}}"
              token="{{ csrf_token() }}"
              checkUrl="{{route('booking.store.checked.state')}}">
            <thead>
                <tr>
                  <th style="width:5%;">
                    <input type='checkbox' class='checkAll' value='1' />
                  </th>
                  <th style="width:10%;">Name</th>
                  <th style="width:7%;">Phone</th>
                  <th style="width:6%;">Category</th>
                  <th style="width:5%;">SMS</th>
                  <th style="width:12%;">Actions</th>
                  <th style="width:6%;">Log Status </th>
                  <th style="width:6%;">Log</th>
                  <th style="width:11%;">Avaiblity</th>
                  <th style="width:10%;"> Current History</th>
                  <th style="width:2%;">H/W</th>
                </tr>
              </thead>
        </table>
      </div>

      <div id="priorityStaffs" class="tab-pane fade in">
        <table id="priorityStaffTable" class="table table-striped table-bordered table-hover"
              fetch="{{route('booking.data.priority.staff',[encrypt($booking->bookingId)])}}"
              token="{{ csrf_token() }}"
              checkUrl="{{route('booking.store.checked.state')}}">
          <thead>
            <tr>
              <th style="width:5%;">
                <input type='checkbox' class='checkAll' value='1' />
              </th>
              <th style="width:10%;">Name</th>
              <th style="width:7%;">Phone</th>
              <th style="width:6%;">Category</th>
              <th style="width:5%;">SMS</th>
              <th style="width:12%;">Actions</th>
              <th style="width:6%;">Log Status </th>
              <th style="width:6%;">Log</th>
              <th style="width:11%;">Avaiblity</th>
              <th style="width:10%;"> Current History</th>
              <th style="width:2%;">H/W</th>
            </tr>
          </thead>
        </table>
      </div>

      <div id="previouslyWorked" class="tab-pane fade in">
        <table id="previouslyWorkedStaffTable" class="table table-striped table-bordered table-hover"
              fetch="{{route('booking.data.prev.worked.staff',[encrypt($booking->bookingId)])}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:5%;">
                <input type='checkbox' class='checkAll' value='1' />
              </th>
              <th style="width:10%;">Name</th>
              <th style="width:7%;">Phone</th>
              <th style="width:6%;">Category</th>
              <th style="width:5%;">SMS</th>
              <th style="width:12%;">Actions</th>
              <th style="width:6%;">Log Status </th>
              <th style="width:6%;">Log</th>
              <th style="width:11%;">Avaiblity</th>
              <th style="width:10%;"> Current History</th>
              <th style="width:2%;">H/W</th>
            </tr>
          </thead>
        </table>
      </div>

      <div id="inThisZone" class="tab-pane fade in">
        <table id="staffInThisZoneTable" class="table table-striped table-bordered table-hover"
              fetch="{{route('booking.data.in.zone.staff',[encrypt($booking->bookingId)])}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:5%;">
                <input type='checkbox' class='checkAll' value='1' />
              </th>
              <th style="width:10%;">Name</th>
              <th style="width:7%;">Phone</th>
              <th style="width:6%;">Category</th>
              <th style="width:5%;">SMS</th>
              <th style="width:12%;">Actions</th>
              <th style="width:6%;">Log Status </th>
              <th style="width:6%;">Log</th>
              <th style="width:5%;">Avaiblity</th>
              <th style="width:5%;"> Current History</th>
              <th style="width:2%;">H/W</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>

@include('bookings.partials.search.previewSmsPopup')
@include('bookings.partials.search.previewSmsPopupSpot')
@include('bookings.partials.search.staffLogBook')
@include('bookings.partials.search.emptyModal')
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{asset('public/js/jquery.mCustomScrollbar.js') }}"></script>
<script src="{{asset('public/js/pages/bookings/search_staff.js')}}?{{time()}}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>

@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.mCustomScrollbar.css') }}?{{ time() }}">
<link rel="stylesheet" href="{{ asset('public/css/app.css') }}?{{time()}}">
@endpush
