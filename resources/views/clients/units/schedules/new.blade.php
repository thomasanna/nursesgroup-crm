@extends('layouts.template')
@section('title','New Unit Schedule')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Schedule Configuration</h4>
    </div>
    <div class="pull-left m-t-5">
      @if(session()->has('message'))<span class="alert-msg m-l-10">{{session()->get('message')}}</span>@endif
    </div>
    @if(Request::segment(5)==1)
    <div class="pull-right m-r-10">
        <a href="{{route('client_units.staffs.home')}}" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
          Back</a>
    </div>
    @else
    <div class="pull-right m-r-10">
        <a href="{{route('client_units.home')}}" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
          Back</a>
    </div>
    @endif
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
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    @foreach($categories as $category)
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title" style="color:#{{$category['colorCode']}}">{{$category['name']}}</h3>
      </div>
      <!-- form start -->
        <div class="box-body">
          @foreach($shifts as $shift)
          <form action="{{route('client_unit_schedules.save')}}" method="post" >
            {!! csrf_field() !!}
            <input type="hidden" name="clientUnitId" value="{{$clientUnitId}}">
            <input type="hidden" name="categoryId" value="{{encrypt($category['categoryId'])}}">
            <input type="hidden" name="shiftId" value="{{encrypt($shift->shiftId)}}">
            <div class="row">
              <div class="col-sm-2 w20">
                <div class="form-group earlyClr">
                    <label>&nbsp;<br>&nbsp;</label>
                    <input class="form-control" style="background:#{{$shift->colorCode}}"disabled="disabled" type="text" value="{{$shift->name}}">
                </div>
              </div>
              <div class="col-sm-2 w20">
                <div class="form-group">
                    <label>Start <br>Time</label>
                    <input class="form-control timepicker" name="startTime" type="text"
                    @if(isset($category['shifts'][$shift->shiftId]['schedules']['startTime']))
                    value="{{date('H:i',strtotime($category['shifts'][$shift->shiftId]['schedules']['startTime']))}}"
                    @endif>
                </div>
              </div>
              <div class="col-sm-2 w20">
                <div class="form-group">
                    <label>End <br>Time</label>
                    <input class="form-control timepicker" name="endTime" type="text"
                    @if(isset($category['shifts'][$shift->shiftId]['schedules']['endTime']))
                    value="{{date('H:i',strtotime($category['shifts'][$shift->shiftId]['schedules']['endTime']))}}"
                    @endif>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group">
                    <label>UnPaid<br>Break</label>
                    <input class="form-control timepicker" name="unPaidBreak" type="text"
                    @if(isset($category['shifts'][$shift->shiftId]['schedules']['unPaidBreak']))
                      value="{{date('H:i',strtotime($category['shifts'][$shift->shiftId]['schedules']['unPaidBreak']))}}"
                    @endif>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group">
                    <label>Paid<br>Break</label>
                    <input class="form-control timepicker" name="paidBreak" type="text"
                    @if(isset($category['shifts'][$shift->shiftId]['schedules']['paidBreak']))
                      value="{{date('H:i',strtotime($category['shifts'][$shift->shiftId]['schedules']['paidBreak']))}}"
                    @endif>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group">
                    <label>Units<br> Hours</label>
                    <input class="form-control" name="totalHoursUnit" type="text"
                    @if(isset($category['shifts'][$shift->shiftId]['schedules']['totalHoursUnit']))
                      value="{{$category['shifts'][$shift->shiftId]['schedules']['totalHoursUnit']}}"
                    @endif>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group">
                    <label>Staffs<br> Hours</label>
                    <input class="form-control" name="totalHoursStaff" type="text"
                    @if(isset($category['shifts'][$shift->shiftId]['schedules']['totalHoursStaff']))
                      value="{{$category['shifts'][$shift->shiftId]['schedules']['totalHoursStaff']}}"
                    @endif>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group">
                    <label>&nbsp;<br>&nbsp;</label>
                    <input type="submit" value="Save" class="btn btn-primary form-control">
                </div>
              </div>
            </div>
          </form>
          @endforeach
        </div>
        <!-- /.box-body -->
    </div>
    @endforeach
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('public/js/pages/clients/units/schedules/new.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">
@endpush
