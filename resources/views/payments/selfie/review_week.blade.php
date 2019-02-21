@extends('layouts.template')
@section('title','Review Weeks')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h3 class="box-title"><strong>Review Weeks</strong></h3>
        
    </div>
    <div class="text-center col-md-6">
      <h3>
        {{$payments[0]->booking->staff->forname}}
        {{$payments[0]->booking->staff->surname}} |
        {{$payments[0]->booking->staff->selfPaymentCompanyName}} |
        Week {{$payments[0]->paymentWeek}}
      </h3>
    </div>
    @if(session()->has('message'))
    <div class="text-center col-md-3">
      <span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>
    </div>
    @endif
    <div class="pull-right">
      <a href="{{route('payment.selfie.weeks.list')}}" class="btn btn-warning">
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
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('branches.save')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          @foreach($payments as $payment)
          <div class='row reviewPayment'>
            <div class='col-sm-1 wdth5 m-l-10'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Book Id</label>
                @endif
                  <input class="form-control text-right" name="name" type="text" value="{{$payment->bookingId}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Date</label>
                @endif
                  <input class="form-control text-right" name="name" type="text" value="{{$payment['DateDay']}}"  readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Unit</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{$payment->booking->unit->alias}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Staff Hours</label>
                @endif
                  <input class="form-control text-right @if($payment->scheduleStaffHours->totalHoursStaff != $payment->timesheet->staffHours) redBrdr1 @endif" name="phone" value="{{$payment->timesheet->staffHours}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Rate/hr</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payment['ratePerHr'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Gross Pay</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payment['grossPay'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Total TA</label>
                @endif
                  <input class="form-control readonly text-right" name="phone" type="text" value="{{number_format($payment['totalTA'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Other</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text"
                  value="{{number_format($payment['otherPayAmount'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth10'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Shift Grand Total</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payment['shiftGrandTotal'],2)}}" readonly="readonly"/>

              </div>
            </div>

            <div class='col-sm-3 @if ($loop->first) alignSpcl1 @else m-t-0 @endif'>
              <div class="col-sm-2 wd50px">
                @if($payment->archived == 0)
                <a href="{{route('payment.selfie.week.move.to.archives',$payment->paymentId)}}" class="btn btn-primary">Pay</a>
                @else
                <a href="javascript:void(0)" class="btn btn-success" disabled="disabled">Pay</a>
                @endif
              </div>
              @if($payment->archived == 0)
              <div class="col-sm-4 wd93px">
                <a href="{{route('payment.selfie.week.move.to.next.week',$payment->paymentId)}}" class="btn btn-primary">Next Week</a>
              </div>
              @endif
              <div class="col-sm-4 wd67px">
                <a href="{{route('payment.selfie.week.revert',$payment->paymentId)}}" class="btn btn-danger">Revert</a>
              </div>
              <div class="col-sm-2">
                <a class="btn btn-info" data-toggle="tooltip" title="{{$payment->remarks}}">Comments</a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <hr>
        <div class="box-body">
          <div class="row">
            <div class='col-sm-1 wdth5 m-l-10'>
              <div class='form-group'>
                  <label for="date"></label>
              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                  <label for="date"></label>
              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                  <label for="date"></label>
              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                  <label for="date"></label>
              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                  <label for="date">Weekly Pay</label>
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('weeklyPay'),2)}}" readonly="readonly"/>
              </div>
            </div>
            <div class='col-sm-1 wdth8'>
              <div class='form-group'>
                  <label for="date">HL Pay</label>
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('hldyPay'),2)}}" readonly="readonly"/>
              </div>
            </div>
            <div class='col-sm-1 wdth8'>
              <div class='form-group'>
                  <label for="date">Total TA</label>
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('totalTA'),2)}}" readonly="readonly"/>
              </div>
            </div>
            <div class='col-sm-1 wdth8'>
              <div class='form-group'>
                  <label for="date">Total Other</label>
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('otherPayAmount'),2)}}" readonly="readonly"/>
              </div>
            </div>
            <div class='col-sm-1 wdth10'>
              <div class='form-group'>
                  <label for="date">Week Grand Total</label>
                  <input class="form-control text-right" name="phone" type="text" readonly="readonly" 
                  value="{{number_format($payments->sum('shiftGrandTotal'),2)}}" />
              </div>
            </div>
          </div>
        </div>

        <hr>
         
        <div class="box-body">
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Number of shifts to be paid for this week</label>
                  <input type="text" class="form-control text-right wdth50 @if($payments[0]->bookingsNum <> $payments[0]->weekPayment) fontRed @endif" name=""  value="{{$payments[0]->bookingsNum}}" readonly="readonly">
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Number of shift included this week payment</label>
                  <input type="text" class="form-control text-right wdth50 @if($payments[0]->bookingsNum <> $payments[0]->weekPayment) fontRed @endif" name=""  value="{{$payments[0]->weekPayment}}" readonly="readonly">
              </div>
            </div>
            <div class='col-sm-4 m-t-25'>
              <div class='form-group col-sm-6 pull-right'>
                  <label for="date">&nbsp;</label>
                  @if($payments->sum('archived') == 0)
                  <a href="{{route('payment.selfie.ra')}}" class="btn btn-success pull-right m-l-10 hidden" disabled="disabled">Generate RA</a>
                  @else
                  <a href="{{route('payment.selfie.ra')}}" class="btn btn-success pull-right m-l-10">Generate RA</a>
                  @endif
              </div>
            </div>


          </div>
        </div>
        <!-- /.box-body -->

      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection