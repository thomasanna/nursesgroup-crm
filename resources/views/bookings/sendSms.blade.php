@extends('layouts.template')
@section('title','Allocate Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title pull-left">SMS</h4>
        @if(Session::has('message'))<span class="alert-msg" style="margin-left:30px">{{Session::get('message')}}</span>@endif
    </div>
    <div class="pull-right">
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
    <li>#{{$booking->bookingId}}
    </li>
    <li>|&nbsp;
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
    <li>|&nbsp;{{date('H:i',strtotime($booking->startTime))}}</li>
    <li>|&nbsp;{{date('H:i',strtotime($booking->endTime))}}</li>
    <li class="redFont">|&nbsp;{{$booking->staff->forname}} {{$booking->staff->surname}}</li>
  </ul>
</div>

<input type="hidden" name="bookingDateTime" id="bookingDateTime" value="{{$booking->date." ".$booking->startTime}}">

<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
        <input type="hidden" name="bookingId" id="bookingId" value="{{encrypt($booking->bookingId)}}">

        <div class="box-body">
          @if($booking->modeOfTransport==2)
          <div class="row">
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Outbound Driver</label>
                  <input class="form-control" readonly type="text"  name="outboundDriver" value=""/>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Pick Time</label>
                  <input class="form-control" readonly type="text"  name="outPickTime"  value="{{$booking->outBoundPickupTime}}"/>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Pickup Location</label>
                  <input class="form-control" readonly type="text"  name="pickupLocation" value="{{$booking->staff->pickupLocation}}"/>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Inbound Driver</label>
                  <input class="form-control" readonly type="text" name="inboundDriver" value=""/>
              </div>
            </div>
          </div>
          @endif

          <div class="row">
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Rate</label>
                  <input class="form-control" readonly type="text" name="rate" value="{{$staffShiftCost}}"/>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">No of Hours</label>
                  <input class="form-control" readonly type="text" name="noOfHours" value="{{$diffInHours}}"/>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">TA</label>
                  <input class="form-control" readonly type="text" name="TA" value="{{(empty($booking->transportAllowence)?0:$booking->transportAllowence)}}"/>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Extra TA</label>
                  <input class="form-control" readonly type="text" name="extraTA" value="{{empty($booking->extraTA)?0:$booking->extraTA}}"/>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Bonus</label>
                  <input class="form-control" readonly type="text" name="bonus" value="{{$booking->bonus}}"/>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Total Amount</label>
                  <input class="form-control" readonly type="text" name="totalAmount" value="{{$total}}"/>
              </div>
            </div>
          </div>
          <hr>

          <div class="row">
            <div class='col-sm-1'></div>
            <div class='col-sm-2'>
              <form method="post" @if($confirm['btn']==0) action="{{route('booking.send.shift.confirm.sms')}}" @endif>
                {!! csrf_field() !!}
                <div class='form-group'>
                    <label for="date">Shift confirmation</label>
                    <textarea class="form-control" name="shiftConfirmation" rows="8" >{{$confirm['sms']}}</textarea>
                    <input type="submit"  @if($confirm['btn']==1) disabled="disabled" @endif value="@if($confirm['btn']==0) Send SMS Now @else SMS Sent {{$confirm['time']}} @endif"
                    class="btn @if($confirm['btn']==0) btn-warning @else btn-success @endif pull-right m-t-10 w100">
                    <input type="hidden" name="bookingId" value="{{encrypt($booking->bookingId)}}" >
                    <input type="hidden" name="page" value="{{$page}}">
                </div>
              </form>
            </div>
            <div class='col-sm-2'>
              <form method="post" action="{{route('booking.send.final.shift.confirm.sms')}}">
                {!! csrf_field() !!}
                <div class='form-group'>
                    <label for="date">Final shift confirmation</label>
                    <textarea class="form-control" name="finalShiftConfirm" rows="8" >{{$final['sms']}}</textarea>
                    @if($isBeforeTwentyTwoHr)
                      <input type="submit" @if($booking->bonusAuthorizedBy == NULL) disabled="disabled" @endif value="@if($final['btn']==0) Send SMS Now @else SMS Sent {{$final['time']}} @endif"
                      class="btn @if($final['btn']==0) btn-warning @else btn-success @endif pull-right m-t-10 w100">
                    @endif
                    <input type="hidden" name="bookingId" value="{{encrypt($booking->bookingId)}}" >
                    <input type="hidden" name="page" value="{{$page}}">
                </div>
              </form>
            </div>
            <div class='col-sm-2'>
              <form method="post" action="{{route('booking.send.transport.sms')}}">
                {!! csrf_field() !!}
                <div class='form-group'>
                    <label for="date">Transport</label>
                    <textarea class="form-control" name="transportSms" rows="8" >{{$transport['sms']}}</textarea>
                    @if($isBeforeTwentyTwoHr)
                      <input type="submit"  value="@if($transport['btn']==0) Send SMS Now @else SMS Sent {{$transport['time']}} @endif"
                      class="btn @if($transport['btn']==0) btn-warning @else btn-success @endif pull-right m-t-10 w100">
                    @endif
                    <input type="hidden" name="bookingId" value="{{encrypt($booking->bookingId)}}" >
                    <input type="hidden" name="page" value="{{$page}}">
                </div>
              </form>
            </div>
            <div class='col-sm-2'>
              <form method="post"  @if($payment['btn']==0) action="{{route('booking.send.payment.sms')}}" @endif>
                {!! csrf_field() !!}
                <div class='form-group'>
                    <label for="date">Payment</label>
                    <textarea class="form-control" name="paymentSms" rows="8" >{{$payment['sms']}}</textarea>
                     <input type="submit" @if($payment['btn']==1) disabled="disabled" @endif  value="@if($payment['btn']==0) Send SMS Now @else SMS Sent {{$payment['time']}} @endif"
                      class="btn @if($payment['btn']==0) btn-warning @else btn-success @endif pull-right m-t-10 w100">
                    <input type="hidden" name="bookingId" value="{{encrypt($booking->bookingId)}}" >
                    <input type="hidden" name="page" value="{{$page}}">
                </div>
              </form>
            </div>
            <div class='col-sm-2'>
              <form method="post" action="{{route('booking.send.other.sms')}}">
                {!! csrf_field() !!}
                <div class='form-group'>
                    <label for="date">Other Messages</label>
                    <textarea class="form-control" name="otherSms" rows="8" ></textarea>
                    <input type="submit" value="Send SMS" class="btn btn-primary pull-right m-t-10 w100">
                    <input type="hidden" name="bookingId" value="{{encrypt($booking->bookingId)}}" >
                    <input type="hidden" name="page" value="{{$page}}">
                </div>
              </form>
            </div>
            <div class='col-sm-1'></div>
          </div>

          <br>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
@if($booking->importantNotes)
<div class="row">
  <div class="col-md-12">
    <label for="">Important Notes</label>
    <textarea name="name" class="form-control redBgrnd" rows="4">{{$booking->importantNotes}}</textarea>
  </div>
</div>
@endif
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
