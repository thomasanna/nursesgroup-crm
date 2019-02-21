@extends('layouts.template')
@section('title','PAYE Payments - Weeks')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left" id="pageTitle">
        <h1 class="box-title">PAYE Payments - Weeks</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="{{route('payment.payee.ra.pdf')}}" id="proceedToPay"
        class="btn btn-success  @if(!session()->has('checkPayeePayment')||(count(session()->get('checkPayeePayment'))==0)) hidden @endif">
            <i class="fa  fa-plus" aria-hidden="true"></i>Proceed to RA
        </a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<div class="box box-default filter">
  <div class="box-body">
  <div class="row bgDarkBlue">
      <div class="col-sm-2 m-l-15">
        <div class="form-group">
            <label for="date">Staff</label>
            <select class="form-control" id="searchStaff">
              <option value=""></option>
              @foreach($staffs as $staff)
              <option value="{{$staff->staffId}}">{{$staff->forname}} {{$staff->surname}}</option>
              @endforeach
            </select>
        </div>
      </div>
      <div class="col-sm-2 m-l-15 filterSlected">
        <div class="form-group">
            <label for="date">Week</label>
            <select class="form-control" id="searchWeek">
              <option value=""></option>
              @foreach($taxWeeks as $week)
              <option value="{{$week->weekNumber}}" @if($week->weekNumber == $taxCurrentWeek->weekNumber) selected @endif>Week - {{$week->weekNumber}}</option>
              @endforeach
            </select>
        </div>
      </div>
      <div class="col-sm-1 pull-right">
        <div class="form-group">
            <a href="javascript:void(0)" class="btn btn-warning" style="margin-top:25px;" id="searchReset">Reset</a>
        </div>
      </div>
      <div class="col-sm-1 pull-right reports hidden">
        <div class="form-group">
            <a href="{{route('payment.payee.week.brightpay',0)}}" token="{{ csrf_token() }}"  class="btn btn-danger brightPay" style="margin-top:25px;">Bright Report</a>
        </div>
      </div>
      <div class="col-sm-1 pull-right reports hidden">
        <div class="form-group">
            <a href="{{route('payment.payee.week.report',0)}}" token="{{ csrf_token() }}" class="btn btn-primary payeeReport" style="margin-top:25px;">PAYE Report</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('payment.payee.weeks.data.list')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:2%;">#ID</th>
              <th style="width:10%;">Staff</th>
              <th style="width:10%;">Week</th>
              <th style="width:5%;">Amount</th>
              <th style="width:8%;">Total Shifts</th>
              <th style="width:8%;">Processed Shifts</th>
              <th style="width:10%;">Status</th>
              <th style="width:18%;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{ asset('public/js/pages/payments/weeks/payee.js') }}?{{time()}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
@endpush
