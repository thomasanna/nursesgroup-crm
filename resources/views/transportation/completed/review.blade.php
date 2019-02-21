@extends('layouts.template')
@section('title','Review Trips')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Review Trips</h4>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-left shftSmry">
      <ul class="smallHdr">
        <li><strong>#{{$compTrips[0]->driver->driverId}},{{$compTrips[0]->driver->forname}} {{$compTrips[0]->driver->surname}}</strong></li>
        <li>|&nbsp;Week {{$compTrips[0]->payeeWeek}}</li>
      </ul>
    </div>
    <div class="pull-right">
      <a href="{{route('transportation.completed.trips')}}" class="btn btn-warning">
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
      <div class="box-body">
        @foreach($compTrips as $trip)
          <div class='row reviewTrip'>
            <div class='col-sm-2 wdth10 m-l-10'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Date</label>@endif
                  <input class="form-control" name="name" disabled="disabled" type="text" value="{{date('d-M-Y',strtotime($trip->trip->date))}}"   />

              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Time Frame</label>@endif
                  <input class="form-control" name="phone" disabled="disabled" type="text" value="{{date('A',strtotime($trip->trip->pickupTime))}}" />

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Club Id</label>@endif
                  <input class="form-control" name="phone" disabled="disabled" type="text" value="{{$trip->clubId}}" />

              </div>
            </div>
            <div class='col-sm-1 wdth4'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">No of OB</label>@endif
                  <input class="form-control" name="phone" disabled="disabled" type="text" value="{{$trip->outCount or 0}}" />

              </div>
            </div>
            <div class='col-sm-1 wdth4'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">No of IB</label>@endif
                  <input class="form-control" name="phone" isabled="disabled" type="text" value="{{$trip->inCount or 0}}"  />

              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Unit</label>@endif
                  <input class="form-control" name="phone" disabled="disabled" type="text" value="{{$trip->trip->unit->alias}}" />

              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Total Miles</label>@endif
                  <input class="form-control" name="phone" disabled="disabled" type="text" value="{{number_format($trip->totalMiles,2)}}"/>

              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Amount</label>@endif
                  <input class="form-control" name="totalBonus" disabled="disabled" type="text" value="{{number_format(($trip->driver->ratePerMile * $trip->totalMiles *2),2)}}"  />
              </div>
            </div>
            <div class='col-sm-3 @if($loop->iteration==1) alignSpcl @endif @if($loop->iteration!=1) mtEmpty @endif'>
              <div class="col-sm-12">
                <a href="javascript:void(0)" action="{{route('transportation.extract.completed.trips')}}"
                  token="{{csrf_token()}}"
                  payeeweek="{{$trip->payeeWeek}}"
                  driverid="{{$trip->driverId}}"
                  date="{{$trip->trip->date}}"
                  class="btn btn-success extractIt">Extract</a>
                @if($trip->proceedToPay == 0)
                <a href="{{route('transportation.mark.as.to.pay',[$trip->driverId,$trip->payeeWeek,$trip->trip->date])}}" class="btn btn-primary">Pay</a>
                @else
                <a href="javascript:void(0)" class="btn btn-success" disabled="disabled">Pay</a>
                @endif
                <a href="{{route('transportation.move.trip.week',[$trip->driverId,$trip->payeeWeek,$trip->trip->date])}}" class="btn btn-primary">Move</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <hr>
      <div class="box-body">
        <div class="row">
          <div class='col-sm-2'>
            <div class='form-group'>
                <label for="date">Reviewed By</label>
                <input class="form-control" name="phone" readonly type="text" value="{{Auth::user()->name}}" />
            </div>
          </div>
          <div class='col-sm-7'>
            <div class='form-group'>
                <label for="date"></label>
            </div>
          </div>
          @if($compTrips->sum('proceedToPay')==count($compTrips))
          <div class='col-sm-3'>
            <div class='form-group'>
                <label for="date">&nbsp;</label>
                <a href="{{route('transportation.ra.view',[$compTrips[0]->driverId,$compTrips[0]->payeeWeek])}}" class="btn btn-success pull-right m-l-10">Generate RA</a>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
    <!-- /.box -->
  </div>
</div>

<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>
<!-- Modal -->
@include('transportation.completed.review-extract')
<!-- Modal -->
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/transportation/completed/review.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
