@extends('layouts.template')
@section('title','Current Bookings')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Bookings</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="javascript:void(0)" class="btn btn-success" id="todaySearch">Today</a>
        <a href="javascript:void(0)" class="btn btn-success" id="tomorrowSearch">Tomorrrow</a>
        <a href="javascript:void(0)" class="btn btn-success" id="fourDaySearch">Next 4 days</a>
        <input type="hidden" value="0" id="fourDayOption">
        <a href="javascript:void(0)" class="btn btn-success" id="newSearch">New Status</a>
        <a href="javascript:void(0)" class="btn btn-success" id="informedSearch">Informed</a>
        <a href="javascript:void(0)" class="btn btn-success" id="hcaSearch">HCA</a>
        <a href="javascript:void(0)" class="btn btn-success" id="rgnSearch">RGN</a>
        <a href="{{route('booking.quote.preview')}}" id="generateQuote" class="btn btn-success">Shift Report</a>
        <a href="{{route('booking.unit.report')}}" class="btn btn-success">Unit Report</a>
        <a href="{{route('booking.generate.fs.sms')}}" class="btn btn-success">FS SMS</a>
        <a href="{{route('booking.new.step.one')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>New Booking</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->


<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        @include('bookings.filter')
      </div>
    </div>
  </div>
</div>

<div class="row comingDays invisible">
  <div class="futrRest">
  @foreach($bookSumry as $bookSumryItm)
    @if($bookSumryItm->uncoveredCount > 3)
    <span><div class="smalHead">{{date('d-D',strtotime($bookSumryItm->date))}}</div><button class="btn btn-danger btn-xs mrs m-r-10">{{$bookSumryItm->confirmedCount}} | {{$bookSumryItm->uncoveredCount}} </button></span>
    @elseif($bookSumryItm->uncoveredCount <= 3 && $bookSumryItm->uncoveredCount > 0 )
    <span><div class="smalHead">{{date('d-D',strtotime($bookSumryItm->date))}}</div><button class="btn btn-warning btn-xs mrs m-r-10">{{$bookSumryItm->confirmedCount}} | {{$bookSumryItm->uncoveredCount}} </button></span>
    @else
    <span><div class="smalHead">{{date('d-D',strtotime($bookSumryItm->date))}}</div><button class="btn btn-success btn-xs mrs m-r-10">{{$bookSumryItm->confirmedCount}} | {{$bookSumryItm->uncoveredCount}} </button></span>
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
<div class="row">
  <div class="col-xs-12" style="margin-top: -45px;">
    <div class="box">

      <div class="box-body table-responsive no-padding bookings">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('booking.data.current')}}"
              token="{{ csrf_token() }}"
              search="{{$searchKeyword}}"
              checkUrl="{{route('booking.save.checked')}}">
          <thead>
            <tr>
              <th style="width:2%;"><input type='checkbox' class='checkAll' value='1' /></th>
              <th style="width:2%;">Shift Id</th>
              <th style="width:1%;">Source</th>
              <th style="width:9%;">Date</th>
              <th style="width:4%;">Shift</th>
              <th style="width:9%;">Unit</th>
              <th style="width:6%;">Category</th>
              <th style="width:8%;">Staff</th>
              <th style="width:4%;">Shift Log</th>
              <th style="width:1%;">U STS</th>
              <th style="width:1%;">S STS</th>
              <th style="width:13%;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>
<!-- Modal -->

@include('bookings.partials.unitStatus')
@include('bookings.partials.editBookingModel')
@include('bookings.partials.changeDriverModal')
@include('bookings.partials.bookingLogBook')
@include('bookings.partials.unitInformedModal')

<!-- Modal -->

@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/select2.full.min.js')}}"></script>
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.timepicker.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}"></script>
<script src="{{asset('public/js/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>

<script src="{{asset('public/js/pages/bookings/index.js')}}?{{time()}}"></script>
<script src="{{asset('public/js/pages/bookings/current_booking_popup.js')}}?{{time()}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.mCustomScrollbar.css') }}?{{ time() }}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">
<style type="text/css">
  .bigdrop.select2-container .select2-results {max-height: 500px !important;}
.bigdrop .select2-results {max-height: 500px;}
.bigdrop .select2-choices {min-height: 150px; max-height: 550px; overflow-y: auto;}
.select2-container--default .select2-results>.select2-results__options{max-height: 500px;}
div.dataTables_wrapper div.dataTables_processing{top: 8% !important;margin-top: 15px;}
</style>
@endpush
