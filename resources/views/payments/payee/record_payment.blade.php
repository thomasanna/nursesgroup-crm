@extends('layouts.template')
@section('title','Remittence Advice')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Remittence Advice</h4>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
      @if($from ==0)
      <a href="{{route('payment.payee.ra.pdf')}}" target="_blank"  class="btn btn-success">Download</a>
      @else
      <a href="{{route('payment.payee.ra.pdf',[$payments[0]->timesheet->paymentWeek,$payments[0]->booking->staff->staffId])}}" target="_blank"  class="btn btn-success">Download</a>
      @endif
      @if($from ==0)
      <a href="{{route('payment.payee.ra.email')}}" class="btn btn-primary">Email</a>
      @else
      <a href="{{route('payment.payee.ra.email',[$payments[0]->timesheet->paymentWeek,$payments[0]->booking->staff->staffId])}}" class="btn btn-primary">Email</a>
      @endif
      @if($from ==0)
      <a href="{{route('payment.payee.week.review',[$payments[0]->timesheet->paymentWeek,$payments[0]->booking->staff->staffId])}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
      @else
      <a href="{{route('payment.payee.weeks.list')}}" class="btn btn-warning">
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

<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
        <hr>
        <div class="box-body">
          <div class="row">
            <div class='col-sm-9'>
              <div class="staffName col-md-8">
                <div class="name">
                  <div>{{$payments[0]->booking->staff->forname}} {{$payments[0]->booking->staff->surname}} </div>
                  <span class="weekNmbr">Week {{$payments[0]->paymentWeek}}</span>
                  <span class="weekNmbr p-l-40">No: {{$payments[0]->archive->raNumber}}</span>   
                  <span class="weekNmbr p-l-40">Date: {{date('d-M-Y',strtotime($payments[0]->archive->raDate))}}</span>   
                </div>
                <div class="adrress1 ">{{$payments[0]->booking->staff->address}}</div>
                <div class="niNmbr ">NI No : {{$payments[0]->booking->staff->niNumber}}</div>
                <div class="phnMr">Mob : {{$payments[0]->booking->staff->mobile}}</div>
                <div class="emlAdrss">{{$payments[0]->booking->staff->email}}</div>
                </div>
              <div class="weekNmbr col-md-6"></div>
            </div>
            <div class="col-md-3">
              <img src="{{asset('public/images/Nurses_Group_Logo.jpg')}}" width="200">
              <div class="">
                Yeovil Innovation Centre<br>
                Yeovil, BA22 8RN <br>
                Ph: 01935 350366 <br>
                Email: payroll@nursesgroup.co.uk
              </div>
            </div>
            <div class='col-sm-3'>

            </div>
          </div>
        </div>
        <hr>
        <hr>

        <div class="box-body">
          <div class="row">
            <table border="1" class="table table-bordered w97">
              <thead>
                <th>Book ID</th>
                <th>Date</th>
                <th>Unit</th>
                <th>Timesheet</th>                
                <th>Description</th>
                <th>Line Total</th>
              </thead>
              <tbody>
                @foreach($payments as $payment)
                <tr>
                  <td>{{$payment->booking->bookingId}}</td>
                  <td>{{$payment['DateDay']}}</td>
                  <td>{{$payment->booking->unit->alias}}</td>
                  <td>{{$payment->timesheetNum}}</td>
                  <td>{{$payment->Desc}}</td>
                  <td>£ {{$payment->shiftTotal}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="5"><strong>Total</strong></td>
                  <td><strong>£ {{number_format($payments->sum('shiftTotal'),2)}}</strong></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>


        <div class="box-body">
          Total amount shown above consist of weekly pay – ({{$payments->sum('weeklyPay')}}), Holiday pay({{$payments->sum('holidayPay')}}) and TA ({{$payments->sum('grossTa')}})
          and  subjected to Tax, NI and pension deduction. Separate payslip with detailed
          calculation will send out after submitting the report to HMRC.
          <br>
          <br>
          <div class="text-right m-r-25" >
            <img src="{{asset('public/images/jss_logo.jpg')}}" width="100px"> JSS Healthcare Ltd, Innovation Centre, Yeovil, BA22 8RN
          </div>
        </div>
        <hr>
        <div class="box-body">
          RA prepared by	( {{Auth::user()->name}} - {{date('Y-m-d H:i:s')}})
        </div>
        <hr>

        <div class="box-body">
          <div class="row">
            <form action="{{route('payment.payee.ra.record.payment.action')}}" method="POST">
              {!! csrf_field() !!}
              <input type="hidden" name="staffId" value="{{$payments[0]->booking->staffId}}">
              <input type="hidden" name="week" value="{{$payments[0]->paymentWeek}}">
              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Payment Date</label>
                    <input class="form-control" name="paymentDate" required type="text" @if($payments[0]->archive->paymentDate)
                    value="{{date('d-M-Y',strtotime($payments[0]->archive->paymentDate))}}" @endif autocomplete="off">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="date">Bank</label>
                  <select name="bankId" class="form-control" required>
                    <option value="1" @if($payments[0]->archive->bankId == 1) selected="selected" @endif >Natwest</option>
                    <option value="2" @if($payments[0]->archive->bankId == 2) selected="selected" @else selected="selected" @endif>Santader</option>
                    <option value="3" @if($payments[0]->archive->bankId == 3) selected="selected" @endif >Cash</option>
                    <option value="4" @if($payments[0]->archive->bankId == 4) selected="selected" @endif >Cheque</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Transaction Number</label>
                    <input class="form-control" name="transactionNumber" required value="{{$payments[0]->archive->transactionNumber}}" type="text"  autocomplete="off">
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Handled By</label>
                    <select name="handledBy" class="form-control">
                      @foreach($admins as $admin)
                      <option value="{{$admin->adminId}}"
                        @if($payments[0]->archive->handledBy == $admin->adminId) selected="selected" @endif>{{$admin->name}}</option>
                      @endforeach
                    </select> 
                </div>
              </div>
              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <input type="submit" class="btn btn-success m-t-25" value="Record Payment"/>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{ asset('public/js/pages/payments/weeks/record_payment.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
@endpush
