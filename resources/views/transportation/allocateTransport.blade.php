@extends('layouts.template')
@section('title','Allocate Transportation')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Allocate Transportation</h4>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-left shftSmry">
      <ul class="smallHdr">
        <li><strong>&nbsp;{{$driver->forname}} {{$driver->surname}}</strong></li>
        <li>|&nbsp;{{date('d-M-Y D',strtotime($date))}}</li>
        <li>|&nbsp;{{$trips[0]->booking->unit->alias}} > {{$trips[0]->booking->unit->address}}</li>
      </ul>
    </div>
    <div class="pull-right">
      <a href="{{route('transportation.current.trips')}}" class="btn btn-warning">
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

        <div class="box-body">
          @php
          $inTripCount = 1;
          $outTripCount = 1;
          @endphp
          @foreach($trips as $trip)
          <div class='row reviewPayment'>
            <div class='col-sm-1 wdth5 m-l-10'>
              <div class='form-group'>
                  <label for="date">Trip Id</label>
                  <input class="form-control" disabled="disabled" type="text" value="{{$trip->tripId}}" />
              </div>
            </div>
            <div class='col-sm-1 wdth10'>
              <div class='form-group'>
                  <label for="date">Direction</label>
                  @if($trip->direction==1)
                    <input class="form-control" disabled="disabled" type="text" value="OB - {{$outTripCount}}"  />
                    @php
                    $outTripCount++;
                    @endphp
                  @else
                    <input class="form-control" disabled="disabled" type="text" value="IB - {{$inTripCount}}"  />
                    @php
                    $inTripCount++;
                    @endphp
                  @endif
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Staff</label>
                  <input class="form-control" disabled="disabled" type="text"
                  value="{{$trip->booking->staff->forname}} {{$trip->booking->staff->surname}}" />

              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Pick Up</label>
                  @if($trip->direction ==1)
                  <input class="form-control" disabled="disabled" type="text" value="{{$trip->staff->pickupLocation}}" />
                  @else
                  <input class="form-control" disabled="disabled" type="text" value="{{$trip->booking->unit->address}}" />
                  @endif
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Drop Off</label>
                  @if($trip->direction ==1)
                  <input class="form-control" disabled="disabled" type="text" value="{{$trip->booking->unit->address}}" />
                  @else
                  <input class="form-control" disabled="disabled" type="text" value="{{$trip->staff->pickupLocation}}" />
                  @endif
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <label for="date">PickUp Time</label>
                  <input class="form-control" disabled="disabled" type="text" value="{{$trip->pickupTime}}" />
              </div>
            </div>
            <form action="{{route('transportation.save.order')}}" method="post">
              {!! csrf_field() !!}
              <div class='col-sm-1 wdth4 allctTrip'>
                <div class='form-group'>
                    <label for="date">Order</label>
                    <input type="hidden" value="{{$trip->tripId}}" name="tripId">
                    <input class="form-control" name="order" type="text" value="{{$trip->order}}"  />
                </div>
              </div>
              <div class='col-sm-1 wdth4 svbtn'>
                <div class='form-group'>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
              </div>
            </form>
          </div>
          @endforeach
        </div>
        <hr>
        <form action="{{route('transportation.allocate.action')}}" method="post" >
        {!! csrf_field() !!}
        <input type="hidden" name="clubId" value="{{$trips[0]->clubId}}">
        <input type="hidden" name="date" value="{{$trips[0]->date}}">
        <div class="box-body">
          <div class="row">
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Total Miles</label>
                  <input class="form-control" disabled="disabled"  type="text" value="{{$trips->sum('booking.distenceToWorkPlace')}}" />
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Rate per Mile</label>
                  <input class="form-control" disabled="disabled" type="text" value="{{$driver->ratePerMile}}" />
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Amount</label>
                  <input class="form-control" disabled="disabled" type="text" value="{{($driver->ratePerMile*$trips->sum('booking.distenceToWorkPlace'))*2}}" />
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Aggreed Amount</label>
                  <input class="form-control" name="aggreedAmount" type="text" value="{{$trip->aggreedAmount}}" />
              </div>
            </div>

          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="submit" class="btn btn-success pull-right" value="Allocate" autocomplete="off">
        </div>

      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
