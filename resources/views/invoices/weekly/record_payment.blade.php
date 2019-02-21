@extends('layouts.template')
@section('title','Record Payment Invoice')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Record Payment Invoice</h4>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
      <a href="{{route('invoices.weekly.list')}}" class="btn btn-warning">
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
              <div class="col-md-3"></div>
            </div>
            <div class="col-md-8 p-l-40">
              <div>To:</div>
              <div class="">
                <div class="niNmbr "><strong>{{$invoices[0]->booking->unit->name}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->booking->unit->address}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->booking->unit->email}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->clients->name}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->clients->businessAddress}}</strong></div>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class="staffName col-md-10">
                <div><h5><strong>Correspondence Address:</strong></h5></div>
                <div class="niNmbr ">Nurses Group, Yeovil Innovation Centre,</div>
                <div class="phnMr">Barracks Close, Yeovil, Somerset, BA22 8RN</div>
                <div class="phnMr"><strong>Phone: </strong> 01935315031</div>
                <div class="emlAdrss"><strong>Email: </strong> accounts@nursesgroup.co.uk</div>
                <div class="emlAdrss"><strong>Web: </strong>www.nursesgroup.co.uk</div>
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
                @if($invoices[0]->enic!=0) <th>ENIC 13.8% of staff cost</th> @endif
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
                  @if($invoice->enic!=0) <td class="text-right">£ {{$invoice->enic}}</td>@endif
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
            Please make all payments to: JSS HEALTHCARE LTD, Sort Code: 30-95-89, Account Number: 51526968 <br>
            Reference: Use Invoice Number
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

        <div class="box-body">
          <div class="row">
            <form action="{{route('invoices.weekly.record.payment.action')}}" method="POST">
              {!! csrf_field() !!}
              <input type="hidden" name="week" value="{{$week}}">
              <input type="hidden" name="unitId" value="{{$unitId}}">
              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Payment Date</label>
                    <input class="form-control" name="paymentDate" required type="text" @if($invoice->paymentDate)
                    value="{{date('d-M-Y',strtotime($invoice->paymentDate))}}" @endif autocomplete="off">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="date">Bank</label>
                  <select name="bankId" class="form-control" required>
                    <option value="1" @if($invoice->bankId == 1) selected="selected" @endif >Natwest</option>
                    <option value="2" @if($invoice->bankId == 2) selected="selected" @else selected="selected" @endif>Santader</option>
                    <option value="3" @if($invoice->bankId == 3) selected="selected" @endif >Cash</option>
                    <option value="4" @if($invoice->bankId == 4) selected="selected" @endif >Cheque</option>
                    <option value="5" @if($invoice->bankId == 5) selected="selected" @endif >Trust Account</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Transaction Number</label>
                    <input class="form-control" name="transactionNumber" required value="{{$invoice->transactionNumber}}" type="text"  autocomplete="off">
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Handled By</label>
                    <select name="handledBy" class="form-control">
                      @foreach($admins as $admin)
                      <option value="{{$admin->adminId}}"
                        @if(Auth::guard('admin')->user()->adminId == $admin->adminId) selected="selected" @endif>{{$admin->name}}</option>
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
<script src="{{ asset('public/js/pages/invoices/record_payment.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
@endpush
