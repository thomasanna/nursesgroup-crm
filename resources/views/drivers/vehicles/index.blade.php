@extends('layouts.template')
@section('title','Driver Vehicles')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Driver Vehicles</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="{{route('drivers.home')}}" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
          Back</a>
        <a href="{{route('driver.vehicles.new',encrypt($driver->driverId))}}" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New Vehicle</a>
    </div>
    <div class="pull-right m-r-10 w13">
      <select class="form-control select2"
      name="switchProgress"
      action="{{route('drivers.change.progress')}}"
      token="{{ csrf_token() }}"
      applicant="{{encrypt($driver->driverId)}}">
        <option value="1" @if($driver->trainingProgress==1) selected="selected" @endif>To Be Started</option>
        <option value="2" @if($driver->trainingProgress==2) selected="selected" @endif>In Progress</option>
        <option value="3" @if($driver->trainingProgress==3) selected="selected" @endif>Completed</option>
      </select>
    </div>
    <div class="pull-right m-r-10 m-t-7">
      <label for="">Progress</label>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Main row -->
<div class='row col-md-8'>
  <ul class="smallHdr">
    <li>{{$driver->forname}} {{$driver->surname}}</li>
    <li>{{$driver->email}}</li>
    <li>{{$driver->mobile}}</li>
  </ul>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding trainings">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('driver.vehicles.data')}}"
              token="{{ csrf_token() }}"
              driver="{{encrypt($driver->driverId)}}">
          <thead>
            <tr>
              <th style="width=3%;">No</th>
              <th style="width=10%;">Make</th>
              <th style="width=10%;">Model</th>
              <th style="width=10%;">Registration Number</th>
              <th style="width=5%;">Color</th>
              <th style="width=5%;">Status</th>
              <th style="width=20%;">Actions</th>
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
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/pages/drivers/vehicles/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/pages/drivers/index.css') }}">
@endpush
