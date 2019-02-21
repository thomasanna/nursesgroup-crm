@extends('layouts.template')
@section('title','Invoice')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Invoice <strong>{{$invoices[0]->booking->unit->name}}</strong> </h4>
    </div>
    @if(session()->has('message'))<div class="pull-left" style="margin-top:10px"></div><span class="alert-msg">{{session()->get('message')}}</span>@endif
    <div class="pull-right">
      @if($from ==0)
      <a href="{{route('invoices.monthly.pdf')}}" target="_blank"  class="btn btn-success">Download</a>
      @else
      <a href="{{route('invoices.monthly.pdf',[$invoices[0]->weekNumbr,$invoices[0]->booking->unitId])}}" target="_blank"  class="btn btn-success">Download</a>
      @endif

      <a href="{{route('invoices.monthly.internal.excel',[$month,$invoices[0]->booking->unitId])}}" class="btn btn-danger">Internal</a>

      @if($from ==0)
      <a href="{{route('invoices.monthly.email')}}" class="btn btn-primary">Email</a>
      @else
      <a href="{{route('invoices.monthly.email',[$month,$invoices[0]->booking->unitId])}}" class="btn btn-primary">Email</a>
      @endif
      @if($from ==0)
      <a href="{{route('invoices.month.review',[$month,$invoices[0]->booking->unitId])}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
      @else
      <a href="{{route('invoices.weeks.list')}}" class="btn btn-warning">
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
            <div class="col-sm-12">
              <div class="col-md-3">
                <img src="{{asset('public/images/Nurses_Group_Logo.jpg')}}" width="200">
              </div>
              <div class=" col-md-6 pull-right text-right"><h2>INVOICE</h2></div>
            </div>
            <div class="col-md-12">
              <div class="col-md-6 p-l-40">
                <div>To:</div>
                <div class="">
                  <div class="niNmbr "><strong>{{$invoices[0]->booking->unit->name}}</strong></div>
                  <div class="phnMr"><strong>{{$invoices[0]->booking->unit->address}}</strong></div>
                  <div class="phnMr"><strong>{{$invoices[0]->booking->unit->email}}</strong></div>
                  <div class="phnMr"><strong>{{$invoices[0]->clients->name}}</strong></div>
                  <div class="phnMr"><strong>{{$invoices[0]->clients->businessAddress}}</strong></div>
                </div>
              </div>
              <div class='col-sm-6 '>
                <div class="staffName pull-right">
                  <div><h5><strong>Correspondence Address:</strong></h5></div>
                  <div class="niNmbr ">Nurses Group, Yeovil Innovation Centre,</div>
                  <div class="phnMr">Barracks Close, Yeovil, Somerset, BA22 8RN</div>
                  <div class="phnMr"><strong>Phone: </strong> 01935315031</div>
                  <div class="emlAdrss"><strong>Email: </strong> accounts@nursesgroup.co.uk</div>
                  <div class="emlAdrss"><strong>Web: </strong>www.nursesgroup.co.uk</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="box-body">
          <div class="row">
            <table border="1" class="table table-bordered w97">
              <thead>
                <th class="text-center">Date</th>
                <th class="text-center">Work Place</th>
                <th class="text-center">Invoice Period</th>
                <th class="text-center">Invoice No</th>
                <th class="text-center">Grand Total</th>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">{{date('d-m-Y',strtotime($invoices[0]->invoiceDate))}}</td>
                  <td class="text-center">{{$invoices[0]->booking->unit->name}}, {{$invoices[0]->booking->unit->businessAddress}}</td>
                  <td class="text-center">{{$invoices[0]['periodDates']}}</td>
                  <td class="text-center">{{$invoices[0]->invoiceNumber}}</td>
                  <td class="text-center">£ {{number_format($invoices->sum('lineTotal'),2)}}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="row">
            <table border="1" class="table table-bordered w97">
              <thead>
                <th>Work ID</th>
                <th>Staff</th>
                <th>Staff Category</th>
                <th class="text-center">Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Hours Worked</th>
                <th>Hourly Rate</th>
                @if($invoices[0]->enic!=0)<th>ENIC 13.8% of staff cost</th>@endif
                <th>Travel Expenses</th>
                <th>Line Total</th>
              </thead>
              <tbody>
                @foreach($invoices as $invoice)
                <tr>
                  <td>{{$invoice->booking->bookingId}}</td>
                  <td>{{$invoice->booking->staff->forname}} {{$invoice->booking->staff->surname}}</td>
                  <td>{{$invoice->booking->category->name}}</td>
                  <td>{{$invoice['DateDay']}}</td>
                  <td>{{date('H:i',strtotime($invoice->timesheet->startTime))}}</td>
                  <td>{{date('H:i',strtotime($invoice->timesheet->endTime))}}</td>
                  <td>{{number_format($invoice->timesheet->unitHours,2)}}</td>
                  <td class="text-right">£ {{number_format($invoice->hourlyRate,2)}}</td>
                  @if($invoice->enic!=0)<td class="text-right">£ {{$invoice->enic}}</td>@endif
                  <td class="text-right">£ {{number_format($invoice->ta,2)}}</td>
                  <td class="text-right">£ {{number_format($invoice->lineTotal,2)}}</td>
                </tr>
                @endforeach
                <tr>
                  <td @if($invoices[0]->enic!=0) colspan="10" @else colspan="9" @endif class="rightInput"><strong>Total</strong></td>
                  <td class="rightInput"><strong>£ {{number_format($invoices->sum('lineTotal'),2)}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>


          <div class="box-body" style="line-height: 26px;">
            Full payment due in 28 days upon receipt of the invoice
            <br>
            Please make all payments to: JSS HEALTHCARE LTD, Sort Code: 30-95-89, Account Number: 51526968
            <br>
            Reference: Use Invoice Number
            <br>
            <div class="text-center centerInput"><strong>Thank you very much for choosing <span class="orangeFont">Nurses</span><span class="blueFont">Group</span></strong></div>
            <div class="col-sm-7 blueFont">JSS Healthcare Ltd, Yeovil Innovation Centre,Barracks Close, Yeovil, Somerset, BA22 8RN, Reg No: 09846338</div>
            <div class="col-sm-5 text-right"><img src="{{asset('public/images/jss_logo.jpg')}}" width="100px">
              <span class="blueFont ">JSS Healthcare Ltd. </span></div>
          </div>

          <div class="col-sm-12" style="line-height: 26px;">
            <div class="col-sm-7 bgOrange"></div>
            <div class="col-sm-5 bgBlue"></div>

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
