@extends('layouts.template')
@section('title','Unit Bills - Monthly')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left" id="pageTitle">
        <h1 class="box-title">Unit Bills - Monthly</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
  </div>
</div>
<!-- Header Navigation -->
<div class="box box-default filter">
  <div class="box-body">
  <div class="row bgDarkBlue">
      <div class='col-sm-2'>
        <div class='form-group'>
            <label for="date">Unit</label>
            <select class="form-control" id="searchUnit">
              <option value=""></option>
              @foreach($units as $unit)
              <option value="{{$unit->clientUnitId}}">{{ $unit->alias or $unit->name }}</option>
              @endforeach
            </select>
            <p class="error">Unit is required</p>
        </div>
      </div>
      <div class='col-sm-2'>
        <div class='form-group'>
            <label for="date">Month</label>
            <select class="form-control" id="searchMonth">
              <option value=""></option>
              @for($m=1; $m<=12; ++$m)
                  <option value="{{date('n', mktime(0, 0, 0, $m, 1))}}">{{date('F', mktime(0, 0, 0, $m, 1))}}</option>;
              @endfor
            </select>
            <p class="error">Unit is required</p>
        </div>
      </div>
        <div class="col-sm-1 pull-right">
          <div class="form-group">
              <a href="javascript:void(0)" class="btn btn-warning" style="margin-top:25px;" id="searchReset">Reset</a>
          </div>
        </div>
      </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Main row -->
<div class="row">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('invoices.monthly.data')}}"
              token="{{ csrf_token() }}"
              checkUrl="{{route('payment.payee.check')}}">
          <thead>
            <tr>
              <!-- <th style="width:2%;">#Id</th> -->
              <!-- <th style="width:10%;">Date</th> -->
              <!-- <th style="width:10%;">Shift</th> -->
              <th style="width:13%;">Unit</th>
              <!-- <th style="width:5%;">Log</th> -->
              <!-- <th style="width:13%;">Staff</th> -->
              <!-- <th style="width:5%;">Category</th>
              <th style="width:8%;">TS Number</th>-->
              
              <th style="width:7%;">Invoice Month</th>
              <th style="width:5%;">Status</th> 
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


@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{ asset('public/js/pages/invoices/monthlyList.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
@endpush
