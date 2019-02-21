@extends('layouts.template')
@section('title','Allocate Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Change Confirm Staff</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('booking.allocate.staff.confirm',encrypt($booking->bookingId))}}" class="btn btn-warning">
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

<div class="row col-md-12">
  <ul class="smallHdr">
    <li>
      @if($booking->isWeekend)
      <span class='redFont'>{{date('d-M-Y, D',strtotime($booking->date))}}</span>
      @else
      {{date('d-M-Y, D',strtotime($booking->date))}}
      @endif
    </li>
    <li>|&nbsp;{{empty($booking->unit->alias)?$booking->unit->name:$booking->unit->alias}}</li>
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
    <li>|&nbsp;{{date('h:i A',strtotime($booking->startTime))}}</li>
    <li>|&nbsp;{{date('h:i A',strtotime($booking->endTime))}}</li>
  </ul>
</div>

<input type="hidden" name="bookingDateTime" id="bookingDateTime" value="{{$booking->date." ".$booking->startTime}}">

<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('booking.change.confirm.staff.save')}}" method="post" id="changeConfirmForm" >
        {!! csrf_field() !!}
        <input type="hidden" name="bookingId" id="bookingId" value="{{encrypt($booking->bookingId)}}">
        <div class="box-body">
          <div class="row">
            <div class='col-sm-2 asnd'>
              <div class='form-group'>
                  <label for="Staff">Assigned Staff</label>
                  <div class="asndtf">
                    <strong>{{$booking->staff->forname." ".$booking->staff->surname}}</strong>
                  </div>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date</label>
                  <input type="text" class="form-control datepicker" name="cancelDate">
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                <label for="date">Time</label>
                  <input type="text" class="form-control timepicker" name="cancelTime">
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Time Diffrence</label>
                  <input type="text"  class="form-control" readonly name="timeDiffrence" value="0">
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Impact Factor</label>
                  <select class="form-control select2" name="cancelImapctFactor">
                    @for($i=1;$i<=10;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Cancellation Charge</label>
                  <input type="number" step="0.01"  class="form-control" name="cancelCharge" value="0">
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="unitStatus">Reason for cancel</label>
                  <textarea name="cancelExplainedReason" class="form-control" rows="4" cols="80"></textarea>
              </div>
            </div>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label for="unitStatus">Notes</label>
                  <textarea name="cancelNotes" class="form-control" rows="4" cols="80"></textarea>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                <div class="pull-right m-t-25">
                  <a href="#" class="btn btn-primary"> Send Report Email </a>
                </div>
              </div>
            </div>

          </div>

          <div class="row">

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Inform Unit</label>
                  <select class="form-control select2" name="cancelInformUnit">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                  </select>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Authorized by</label>
                  <select class="form-control select2" name="cancelAuthorizedBy">
                    <option value=""></option>
                    @foreach($branchContacts as $item)
                    <option value="{{$item->branchContactId}}">{{$item->name}}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Next Action</label>
                  <select class="form-control select2" name="nextAction">
                    <option value=""></option>
                    <option value="1">Search for Another</option>
                    <option value="2">Cancelled Shift</option>
                  </select>
              </div>
            </div>
          </div>
        </div>

        <div class="box-footer">
          <div class="pull-right">
            <input type="submit" class="btn btn-primary" value="Save"/>
          </div>
        </div>
        <!-- /.box-body -->
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/jquery.timepicker.min.js')}}"></script>
<script src="{{asset('public/js/moment.min.js')}}"></script>
<script src="{{asset('public/js/pages/bookings/change-confirm-staff.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.timepicker.min.css') }}">

@endpush
