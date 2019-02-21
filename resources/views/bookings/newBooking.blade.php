@extends('layouts.template')
@section('title','New Booking')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Booking</h4>
    </div>

    <div class="pull-right">
      <a href="javascript:void(0);" action="{{route('booking.step.one.row')}}"
      token="{{ csrf_token() }}"
      class="btn btn-primary newRow">
        <i class="fa  fa-plus" aria-hidden="true"></i>New Row</a>
      <a href="javascript:void(0);" data-url="{{route('booking.current')}}" id="back_btn" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Filter Area -->
<!-- <div class="box box-default">
  <div class="box-body">

  </div>
</div> -->
<!-- Filter Area -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row col-md-8">
  <ul class="smallHdr" adminId="{{Auth::user()->adminId}}">
    <li>{{Auth::user()->name}}</li>
    <li>| {{date('d-M-Y, D')}}</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- form start -->
    <form action="{{route('booking.step.one.action')}}" method="post" >
    <!-- general form elements -->
      <div class="box box-primary">
          {!! csrf_field() !!}
          <div class="box-body newBookingForm">
            <div class="row">
              <div class='col-sm-3'>
                <div class='form-group'>
                    <label for="date">Unit</label>
                    <select class="form-control select2" name="unitId"
                    action="{{route('client_unit_contact.get.booking')}}"
                    token="{{ csrf_token() }}">
                      <option value=""></option>
                      @foreach($units as $unit)
                      <option value="{{$unit->clientUnitId}}">{{ $unit->alias or $unit->name }}</option>
                      @endforeach
                    </select>
                    <p class="error">Unit is required</p>
                </div>
              </div>
              <div class='col-sm-3'>
                <div class='form-group'>
                    <label for="date">Mode of Request</label>
                    <select class="form-control select2" name="modeOfRequest">
                      <option value=""></option>
                      <option value="1">Email</option>
                      <option value="2">Phone</option>
                      <option value="3">SMS</option>
                    </select>
                    <p class="error">Mode of Request is required</p>
                </div>
              </div>
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Requested By</label>
                    <select class="form-control select2 reqBy" name="requestedBy">
                    </select>
                    <p class="error">Requested By is required</p>
                </div>
              </div>
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Requested Date</label>
                    <input class="form-control" name="requestedDate" value="{{date('d-M-Y')}}" type="text" />
                </div>
              </div>
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Requested Time</label>
                    <input class="form-control " name="requestedTime" value="{{date('H:i')}}" type="text" />
                </div>
              </div>
            </div>
            <div class="row shiftRow">
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Date</label>
                    <input class="form-control datepicker" name="date[]" type="text" placeholder="Select a date" autocomplete="off" />
                    <p class="error">Date is required</p>
                </div>
              </div>
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Shift</label>
                    <select class="form-control select2" name="shiftId[]">
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
                    <label for="date">Staff Category</label>
                    <select class="form-control select2" name="categoryId[]">
                      <option value=""></option>
                      @foreach($categories as $category)
                      <option value="{{$category->categoryId}}">{{$category->name}}</option>
                      @endforeach
                    </select>
                    <p class="error">Staff Category is required</p>
                </div>
              </div>
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label>Number of Shifts</label>
                    <select class="form-control select2" name="numbers[]">
                      @for($i=1;$i < 11;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                </div>
              </div>
              <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="mobile">Important Notes</label>
                    <textarea class="form-control" rows="1" name="importantNotes[]" type="text" /></textarea>
                </div>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
      </div>
      <div class="box-footer pull-right">
        <input type="submit" class="btn btn-success" value="Save"/>
      </div>
    <!-- /.box -->
    </form>
  </div>
</div>
<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>

<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{ asset('public/js/jquery.timepicker.min.js') }}"></script>
<script src="{{asset('public/js/pages/bookings/step_one.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
<style type="text/css">
  .select2-container--default .select2-results>.select2-results__options{max-height: 500px;}
  .modal-dialog{margin-top: 230px;}
</style>
@endpush
