@extends('layouts.template')
@section('title','Review Weeks')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h3 class="box-title"><strong>Review Weeks</strong></h3>

    </div>
    <div class="text-center col-md-4">
      <h3>
        {{$payments[0]->booking->staff->forname}} {{$payments[0]->booking->staff->surname}}  | Week {{$payments[0]->paymentWeek}}</h3>
    </div>
    @if(session()->has('message'))
    <div class="col-md-3">
      <span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>
    </div>
    @endif
    <div class="pull-right">
      <a href="{{route('payment.payee.weeks.list')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
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
        <div class="box-body">
        <input type="hidden" name="numPayments" value="{{count($payments)}}">
          @foreach($payments as $key => $payment)
          <div class='row reviewPayment'>
            <div class='col-sm-1 wdth5 m-l-10'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Book <br>Id</label>
                @endif
                  <input class="form-control text-right" name="name" type="text" value="{{$payment->bookingId}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-1 wdth10'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Shift <br>Date</label>
                @endif
                  <input class="form-control text-right" name="name" type="text" value="{{$payment['DateDay']}}"  readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth10'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Unit <br>Name</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{$payment->booking->unit->alias}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Staff <br>Hrs</label>
                @endif
                  <input class="form-control text-right staffHours{{$key}}" name="staffHours" type="text" value="{{number_format($payment->timesheet->staffHours,2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Rate <br>per hr</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payment['ratePerHr'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Gross <br>Pay</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payment['grossPay'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Total <br>TA</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text" value="{{number_format($payment['grossTA'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth5'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Other <br>Pay</label>
                @endif
                  <input class="form-control text-right" name="phone" type="text"
                  value="{{number_format($payment['otherPayAmount'],2)}}" readonly="readonly"/>

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                @if ($loop->first)
                  <label for="date">Shift <br>Total</label>
                @endif
                  <input class="form-control text-right shiftGrandTotal{{$key}}" name="shiftGrandTotal" type="text" value="{{number_format($payment['shiftGrandTotal'],2)}}" readonly="readonly"/>
              </div>
            </div>
            <div class='col-sm-2 @if($payments->sum('archived') == count($payments)) w18 @else w34 @endif @if ($loop->first) m-t-43 @else m-t2 @endif'>
              <div class="form-group">
                <div class="col-sm-2 @if($payments->sum('archived') == count($payments)) wdth16 @else wdth12 @endif">
                  @if($payment->archived == 0)
                  <a href="{{route('payment.payee.week.move.to.archives',$payment->paymentId)}}" class="btn btn-primary">Pay</a>
                  @else
                  <a href="javascript:void(0)" class="btn btn-success @if($payments->sum('archived') == count($payments)) p6-5 @endif" disabled="disabled">Pay</a>
                  @endif
                </div>
                @if($payment->archived == 0)
                <div class="col-sm-2 wdth15">
                  <a href="{{route('payment.payee.week.move.to.next.week',$payment->paymentId)}}" class="btn btn-primary">Move</a>
                </div>
                @endif
                <div class="col-sm-4 @if($payments->sum('archived') == count($payments)) wdth24 @else wdth16 @endif">
                  <a href="{{route('payment.payee.week.revert',$payment->paymentId)}}"
                  class="btn btn-danger @if($payments->sum('archived') == count($payments)) p6-5 @endif">Revert</a>
                </div>
                <div class="col-sm-2 @if($payments->sum('archived') == count($payments)) wdth37 @else wdth23 @endif">
                  <a class="btn btn-info @if($payments->sum('archived') == count($payments)) p6-5 @endif" data-toggle="tooltip" title="{{$payment->remarks}}">Comments</a>
                </div>
                <div class="col-sm-2 wdth12">
                  <a class="btn btn-warning @if($payments->sum('archived') == count($payments)) p6-5 @endif" data-toggle="tooltip" title="{{$payment->remarks}}">Log</a>
                </div>
              </div>
            </div>
            @if($payments->sum('archived') == count($payments))
            <div class="col-sm-1 wdth4">
              @if ($loop->first)
                <label for="date">SHIFT <br>NIC</label>
              @endif
              <input class="form-control text-right" name="nicSplitup" type="text"
              @if($payments[0]->archive->employerNIC != '') value="{{number_format(($payments[0]->archive->employerNIC/$payments->sum('staffHours'))*$payment->timesheet->staffHours,2)}}" @endif
              readonly="readonly"/>
            </div>
            <div class="col-sm-1 wdth4">
              @if ($loop->first)
                <label for="date">SHIFT <br>Pension</label>
              @endif
              <input class="form-control text-right" name="pensionSplitup" type="text"
              @if($payments[0]->archive->employerPension != '') value="{{number_format(($payments[0]->archive->employerPension/$payments->sum('staffHours'))*$payment->timesheet->staffHours,2)}}" @endif
              readonly="readonly"/>
            </div>
            <div class="col-sm-1 wdth4">
              @if ($loop->first)
                <label for="date">Shift <br>Cost</label>
              @endif
              <input class="form-control text-right shiftCost{{$key}}" name="shiftCost" type="text"
              @if($payments[0]->archive->employerNIC != '') value="{{
                number_format((($payments[0]->archive->employerNIC/$payments->sum('staffHours'))*$payment->timesheet->staffHours) +
                (($payments[0]->archive->employerPension/$payments->sum('staffHours'))*$payment->timesheet->staffHours) +
                ($payment['shiftGrandTotal']),2)}}" @endif
              readonly="readonly"/>
            </div>
            <div class="col-sm-1 wdth5">
              @if ($loop->first)
                <label for="date">Effective <br>Hrly Rate</label>
              @endif
              @php $shiftCost = (($payments[0]->archive->employerNIC/$payments->sum('staffHours'))*$payment->timesheet->staffHours) +
            (($payments[0]->archive->employerPension/$payments->sum('staffHours'))*$payment->timesheet->staffHours) +
            ($payment['shiftGrandTotal']); @endphp
              <input class="form-control text-right effectiveHrlyRate{{$key}}" name="effectiveHrlyRate" type="text"
              @if($payments[0]->archive->employerNIC != '') value="{{
                number_format($shiftCost/$payment->timesheet->staffHours,2)}}"  @endif
              readonly="readonly"/>
            </div>
            @endif

          </div>
          @endforeach
        </div>
        <hr>
        <div class="box-body">
          <div class="row">
          <div class="col-md-12 p-l-0">
            <div class="col-md-3">
              <div class='col-sm-6'>
                <div class='form-group'>
                    <label for="date">To be paid for <br>this week</label>
                    <input type="text" class="form-control text-right" name=""  value="{{$payments[0]->bookingsNum}}" readonly="readonly">
                </div>
              </div>
              <div class='col-sm-6'>
                <div class='form-group'>
                    <label for="date">Included this <br>week payment</label>
                    <input type="text" class="form-control text-right" name=""  value="{{$payments[0]->weekPayment}}" readonly="readonly">
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class='col-sm-1 wdth11'>
                <div class='form-group'>
                    <label for="date">Weekly <br>Pay</label>
                    <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('weeklyPay'),2)}}" readonly="readonly"/>
                </div>
              </div>
              <div class='col-sm-1 wdth11'>
                <div class='form-group'>
                    <label for="date">HL <br>Pay</label>
                    <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('hldyPay'),2)}}" readonly="readonly"/>
                </div>
              </div>
              <div class='col-sm-1 wdth10'>
                <div class='form-group'>
                    <label for="date">Total <br>TA</label>
                    <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('totalTA'),2)}}" readonly="readonly"/>
                </div>
              </div>
              <div class='col-sm-1 wdth10'>
                <div class='form-group'>
                    <label for="date">Total <br>Other</label>
                    <input class="form-control text-right" name="phone" type="text" value="{{number_format($payments->sum('otherPayAmount'),2)}}" readonly="readonly"/>
                </div>
              </div>
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Grand <br>Total</label>
                    <input class="form-control text-right grandTotal" name="grandTotal" type="text" readonly="readonly"
                    value="{{number_format($payments->sum('shiftGrandTotal'),2)}}" />
                    <input type="hidden" name="totalStaffHrs" value={{$payments->sum('staffHours')}}
                </div>
              </div>
            </div>
          </div>


          </div>
        </div>
        <hr>
        <div class="box-body">
          @if($payments->sum('archived') == count($payments))
          <div class="btn btn-sm btn-info btn-flat pull-left col-md-12 m-b-15"><strong>Bright Pay Details</strong></div>
          <form action="{{route('payment.payee.week.bright.pay')}}"  method="POST">
          <div class="row">
            {!! csrf_field() !!}
              <input type="hidden" name="staffId" value="{{$payments[0]->booking->staffId}}">
              <input type="hidden" name="week" value="{{$payments[0]->paymentWeek}}">
              <input type="hidden" name="bookId" value="{{$payments[0]->booking->bookingId}}">
              <div class='form-group col-sm-1'>
                <label for="date">TAX <br>PAYABLE</label>
                <input type="text" class="form-control text-right" name="tax" @if($payments[0]->archive->tax != '') value="{{number_format($payments[0]->archive->tax,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">EMPLOYEE <br>NIC</label>
                <input type="text" class="form-control text-right" name="employeeNIC" @if($payments[0]->archive->employeeNIC != '') value="{{number_format($payments[0]->archive->employeeNIC,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">EMPLOYEE <br>PENSION</label>
                <input type="text" class="form-control text-right" name="employeePension" @if($payments[0]->archive->employeePension != '') value="{{number_format($payments[0]->archive->employeePension,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">STUDENT <br>LOAN</label>
                <input type="text" class="form-control text-right" name="studentLoan" @if($payments[0]->archive->studentLoan != '') value="{{number_format($payments[0]->archive->studentLoan,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">PAY <br>ADVANCE</label>
                <input type="text" class="form-control text-right" name="advance" @if($payments[0]->archive->advance != '') value="{{number_format($payments[0]->archive->advance,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
                <label for="date">DBS <br>AMOUNT</label>
                <input type="text" class="form-control text-right" name="dbs" @if($payments[0]->archive->dbs != '') value="{{number_format($payments[0]->archive->dbs,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">UNIFORM <br>AMOUNT</label>
                <input type="text" class="form-control text-right" name="uniform" @if($payments[0]->archive->uniform != '') value="{{number_format($payments[0]->archive->uniform,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">CANCELLATION <br>CHARGE</label>
                <input type="text" class="form-control text-right" name="cancellationCharge" @if($payments[0]->archive->cancellationCharge != '') value="{{number_format($payments[0]->archive->cancellationCharge,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">EMPLOYER <br>NIC</label>
                <input type="text" class="form-control text-right employerNIC" name="employerNIC" @if($payments[0]->archive->employerNIC != '') value="{{number_format($payments[0]->archive->employerNIC,2)}}" @endif>
              </div>
              <div class='form-group col-sm-1'>
              <label for="date">EMPLOYER <br>PENSION</label>
                <input type="text" class="form-control text-right employerPension" name="employerPension" @if($payments[0]->archive->employerPension != '') value="{{number_format($payments[0]->archive->employerPension,2)}}" @endif>
              </div>
            </div>
            @endif

            <div class="pull-right m-b-20">
              @if($payments->sum('archived') == 0)
                <a href="" disabled="disabled"  class="btn btn-success pull-right m-l-10" method="2">Generate RA</a>
                <a href="" class="btn btn-success pull-right" disabled="disabled" method="2">Save</a>

              @else
                @if($payments->sum('archived') == count($payments))
                <a href="{{route('payment.payee.ra')}}" class="btn btn-success pull-right m-l-10" method="2">Generate RA</a>
                @endif
                  <input type="submit" class="btn btn-success" value="Save"/>
              @endif

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
@push('scripts')
<script src="{{ asset('public/js/pages/payments/weeks/payeeReview.js') }}"></script>
<script src="{{asset('public/js/select2.min.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
