@extends('layouts.template')
@section('title','Unit Bills - Verification')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left" id="pageTitle">
        <h1 class="box-title">Unit Bills - Verification</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
  </div>
</div>
<!-- Header Navigation -->
<div class="box box-default filter">
  <div class="box-body">
  <div class="row bgDarkBlue">
      <div class="col-sm-2">
        <div class="form-group">
          <label for="date">Date</label>
          <input class="form-control datepicker" id="searchDate" type="text" placeholder="Date" autocomplete="off" />
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
            <label for="date">Shift</label>
            <select class="form-control" id="searchShift">
              <option value=""></option>
              @foreach($shifts as $shift)
              <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
              @endforeach
            </select>
            <p class="error">Shift is required</p>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
            <label for="date"> Category</label>
            <select class="form-control" id="searchCategory">
              <option value=""></option>
              @foreach($staffCategory as $category)
              <option value="{{$category->categoryId}}">{{$category->name}}</option>
              @endforeach
            </select>
            <p class="error">Category is required</p>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
            <label for="date">Staff</label>
            <select class="form-control" id="searchStaff">
              <option value=""></option>
              @foreach($staffs as $staff)
              <option value="{{$staff->staffId}}">{{$staff->forname}} {{$staff->surname}}</option>
              @endforeach
            </select>
            <p class="error">Staff is required</p>
        </div>
      </div>
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
              fetch="{{route('invoices.data')}}"
              token="{{ csrf_token() }}"
              checkUrl="{{route('payment.payee.check')}}">
          <thead>
            <tr>
              <th style="width:2%;">#Id</th>
              <th style="width:10%;">Date</th>
              <th style="width:10%;">Shift</th>
              <th style="width:13%;">Unit</th>
              <th style="width:13%;">Staff</th>
              <th style="width:5%;">Category</th>
              <th style="width:8%;">TS Number</th>
              <th style="width:8%;">Log</th>
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

<!-- Modal -->
@include('invoices.verify_popup')

<!-- Modal -->
@include('invoices.approve_popup')


@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}?{{time()}}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}?{{time()}}"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('public/js/pages/invoices/verification.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
@endpush
