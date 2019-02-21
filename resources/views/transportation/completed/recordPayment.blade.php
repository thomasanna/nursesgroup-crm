@extends('layouts.template')
@section('title','Record Payment')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Record Payment</h4>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
      <a href="{{route('transportation.ra.download.pdf',[$compTrips[0]->driverId,$compTrips[0]->payeeWeek])}}" class="btn btn-success">Download</a>
      <a href="{{route('transportation.ra.email',[$compTrips[0]->driverId,$compTrips[0]->payeeWeek])}}" class="btn btn-primary">Email</a>
      <a href="{{route('transportation.review.completed.trips',[$compTrips[0]->payeeWeek,$compTrips[0]->driverId])}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
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
              <div class="staffName col-md-6">
                <div class="name">
                  {{$compTrips[0]->driver->forname}} {{$compTrips[0]->driver->surname}} 
                  <span class="weekNmbr">Week {{$compTrips[0]->payeeWeek}}</span> | 
                  <span class="weekNmbr">Club {{$compTrips[0]->trip->clubId}}</span>  
                </div>
                <div class="adrress1 ">{{$compTrips[0]->driver->address}}</div>
                <div class="niNmbr ">NI No : {{$compTrips[0]->driver->niNumber}}</div>
                <div class="phnMr">Mob : {{$compTrips[0]->driver->mobile}}</div>
                <div class="emlAdrss">{{$compTrips[0]->driver->email}}</div>
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
                <th>Trip ID</th>
                <th>Club ID</th>
                <th>Date</th>
                <th>Staff</th>
                <th>PickUp</th>
                <th>Drop</th>
                <th>Total Miles</th>
                <th>Amount</th>
              </thead>
              <tbody>
                @foreach($compTrips as $trip)
                <tr>
                  <td>{{$trip->trip->tripId}}</td>
                  <td>{{$trip->trip->clubId}}</td>
                  <td>{{date('d-M-Y,D',strtotime($trip->trip->date))}}</td>
                  <td>{{$trip->trip->staff->forname}} {{$trip->trip->staff->surname}}</td>
                  @if($trip->trip->direction ==1)
                    <td>{{$trip->trip->staff->pickupLocation}}</td>
                  @else
                   <td>{{$trip->trip->booking->unit->address}} ({{$trip->trip->booking->unit->alias}})</td>
                  @endif
                  @if($trip->trip->direction ==1)
                    <td>{{$trip->trip->booking->unit->address}} ({{$trip->trip->booking->unit->alias}})</td>
                  @else
                   <td>{{$trip->trip->staff->pickupLocation}}</td>
                  @endif
                  <td>{{number_format($trip->distenceToWorkPlace,2)}}</td>
                  <td>{{number_format(($trip->driver->ratePerMile * $trip->distenceToWorkPlace *2),2)}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="7"><strong>Total</strong></td>
                  <td><strong>{{number_format(($compTrips->sum('distenceToWorkPlace')*2)*$compTrips[0]->driver->ratePerMile,2)}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>


        <div class="box-body">
          Total amount shown above consist of weekly pay – ({{$compTrips->sum('weeklyPay')}}), Holiday pay({{$compTrips->sum('holidayPay')}}) and TA ({{$compTrips->sum('grossTa')}})
          and  subjected to Tax, NI and pension deduction. Separate payslip with detailed
          calculation will send out after submitting the report to HMRC.
          <br>
          <br>
          <img src="{{asset('public/images/jss_logo.jpg')}}" width="100px"> JSS Healthcare Ltd, Innovation Centre, Yeovil, BA22 8RN
        </div>
        <hr>
        <div class="box-body">
          RA prepared by  ( {{Auth::user()->name}} - {{date('Y-m-d H:i:s')}})
        </div>
        <hr>
        <div class="box-body">
          <div class="row">
            <form action="{{route('transportation.record.payment.action')}}" method="POST">
              {!! csrf_field() !!}
              <input type="hidden" name="driverId" value="{{$compTrips[0]->driverId}}">
              <input type="hidden" name="payeeWeek" value="{{$compTrips[0]->payeeWeek}}">
              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Payment Date</label>
                    <input class="form-control datepicker" name="paymentDate" @if($compTrips[0]->payment_status==1)) value="{{date('d-m-Y',strtotime($compTrips[0]->payment->paymentDate))}}" @endif required type="text" autocomplete="off">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="date">Bank</label>
                  <select name="bankId" class="form-control select2" required>
                    <option value=""></option>
                    <option value="1" @if($compTrips[0]->payment_status==1)) @if($compTrips[0]->payment->bankId ==1) selected @endif @endif>Natwest</option>
                    <option value="2" @if($compTrips[0]->payment_status==1)) @if($compTrips[0]->payment->bankId ==2) selected @endif @endif>Santader</option>
                    <option value="3" @if($compTrips[0]->payment_status==1)) @if($compTrips[0]->payment->bankId ==3) selected @endif @endif>Cash</option>
                    <option value="4" @if($compTrips[0]->payment_status==1)) @if($compTrips[0]->payment->bankId ==4) selected @endif @endif>Cheque</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Transaction Number</label>
                    <input class="form-control" name="transactionNumber" @if($compTrips[0]->payment_status==1)) value="{{$compTrips[0]->payment->transactionNumber}}" @endif type="text"  autocomplete="off">
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Handled By</label>
                    <select name="handledBy" class="form-control select2">
                      @foreach($admins as $admin)
                      <option value="{{$admin->adminId}}" @if($compTrips[0]->payment_status==1)) @if($compTrips[0]->payment->handledBy ==$admin->adminId) selected @endif @endif>{{$admin->name}}</option>
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
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/pages/transportation/recordPayment.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
