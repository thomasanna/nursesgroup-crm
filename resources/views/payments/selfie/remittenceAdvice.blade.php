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
      <a href="{{route('payment.selfie.ra.pdf')}}" target="_blank"  class="btn btn-success">Download</a>
      @else
      <a href="{{route('payment.selfie.ra.pdf',[$payments[0]->paymentWeek,$payments[0]->booking->staff->staffId])}}"  target="_blank" class="btn btn-success">Download</a>
      @endif
      @if($from ==0)
      <a href="{{route('payment.selfie.ra.email')}}" class="btn btn-primary">Email</a>
      @else
      <a href="{{route('payment.selfie.ra.email',[$payments[0]->paymentWeek,$payments[0]->booking->staff->staffId])}}" class="btn btn-primary">Email</a>
      @endif
      @if($from ==0)
      <a href="{{route('payment.selfie.week.review',[$payments[0]->paymentWeek,$payments[0]->booking->staff->staffId])}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
      @else
      <a href="{{route('payment.selfie.weeks.list')}}" class="btn btn-warning">
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
            <div class='col-sm-4'>
              <div class="staffName col-md-10">
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
            <div class='col-sm-4'>
              <div class="staffName col-md-6">
                <div class="name" style="font-size: 18px;">{{$payments[0]->booking->staff->selfPaymentCompanyName}}</div>
                <div class="adrress1 " style="margin-top: 44px;">{{$payments[0]->booking->staff->selfPaymentCompanyRegAddress}}</div>
                <div class="niNmbr ">Reg No : {{$payments[0]->booking->staff->selfPaymentCompanyNumber}}</div>
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
          Timesheets period run from Monday to Sunday every week. Please submit your timesheet to us by 12 noon Monday to be paid on the following Friday to payroll@nursesgroup.co.uk. The rate agreed above including Holiday pay, WTR, NI, Employee Pension, TA and all other benefits as agreed. Separate payslip with detailed calculation will be emailed you shortly.
          <br>
          Thank you very much for working with Nurses Group.
          <br>
          <div class="text-right m-r-25" >
            <img src="{{asset('public/images/jss_logo.jpg')}}" width="100px"> JSS Healthcare Ltd, Innovation Centre, Yeovil, BA22 8RN
          </div>
        </div>
        <hr>

        <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/branches/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
