@extends('layouts.template')
@section('title','Allocate Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Allocate Staff</h4>
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
    <li>|&nbsp;{{$diffInHours}} Hrs</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <input type="hidden" id="csrfToken" value="{{ csrf_token() }}">
      <input type="hidden" id="staffInfoURL" value="{{ route('booking.staff.info') }}">
      <input type="hidden" id="staffTAURL" value="{{ route('booking.staff.ta') }}">
      <input type="hidden" id="staffRate" value="{{$staffShiftCost}}">
      <input type="hidden" id="diffInHours" value="{{$diffInHours}}">
      <!-- form start -->
      <form action="{{route('booking.allocate.staff.save')}}" method="post" >
        {!! csrf_field() !!}
        <input type="hidden" name="bookingId" id="bookingId" value="{{encrypt($booking->bookingId)}}">
        <div class="box-body">
          <div class="row">
            <div class='col-sm-3 asnd'>
              <div class='form-group'>
                  <label for="Staff">Assigned Staff</label>
                  <div class="asndtf">
                    <strong>@if(!empty($booking->staffId)){{$booking->staff->forname." ".$booking->staff->surname}}@endif</strong>
                  </div>
                  <input type="hidden" class="form-control" name="staffId" value="{{$booking->staff->staffId}}">
                  <input type="hidden" class="form-control" name="page" value="{{$page}}">

                  <p class="error">Staff is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Staff Status</label>
                  <input type="text" class="form-control" name="staffAllocateStatus" readonly
                  @if($booking->staffAllocateStatus=='1') value="Dummy" @endif
                  @if($booking->staffAllocateStatus=='2') value="Confirmed" @endif
                  @if($booking->staffAllocateStatus=='3') value="Potential" @endif
                  >
                  <p class="error">Staff Status is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="finalConfirm">Final confirmation status</label>
                  <select class="form-control select2" name="finalConfirm">
                    <option @if($booking->finalConfirm == 1) selected @endif value="1">Waiting for confirmation</option>
                    <option @if($booking->finalConfirm == 2) selected @endif value="2">Confirmed</option>
                  </select>
              </div>
            </div>
            @if($booking->staffAllocateStatus==2)
            <div class='col-sm-3'>
              <div class="pull-right m-t-25">
              <a href="{{route('booking.change.confirm.staff',encrypt($booking->bookingId))}}" class="btn btn-primary">
                Change Confirm Staff
                </a>
              </div>
            </div>
            @endif


          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Mode of Transport</label>
                  <select class="form-control select2" name="modeOfTransport">
                    <option  @if($booking->modeOfTransport=='1') selected="selected" @endif value="1">Self</option>
                    <option  @if($booking->modeOfTransport=='2') selected="selected" @endif  value="2">Transport required</option>
                  </select>
                  <p class="error">Transport is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Distance to Workplace (Miles)</label>
                  <input type="text" class="form-control" name="distanceToWorkplace" readonly value="{{$travelDistance}} Miles" >
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Current HR Rate</label>
                  <input class="form-control" name="staffRate" readonly value="{{$staffShiftCost}}" />
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Aggreed HR Rate</label>
                  <input type="text" class="form-control" name="aggreedHrRate" value="{{$booking->aggreedHrRate}}">
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Max HR Rate</label>
                  <input type="text" class="form-control" readonly>
              </div>
            </div>
          </div>


          <div class=' @if($booking->modeOfTransport!='2') hidden @endif' id="transportDiv">

            <div class="row">

              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Outbound Driver Type</label>
                    <select class="form-control select2" name="outboundDriverType">
                      <option value=""></option>
                      <option @if($booking->outBoundDriverType == 1) selected @endif value="1">Private Driver</option>
                      <option @if($booking->outBoundDriverType == 2) selected @endif value="2">Possible lift</option>
                      <option @if($booking->outBoundDriverType == 3) selected @endif value="3">Public Transport</option>
                      <option @if($booking->outBoundDriverType == 4) selected @endif value="4">Self Transport</option>
                    </select>
                </div>
              </div>

              <div class='outboundDiv @if(empty($booking->outBoundDriverType)||($booking->outBoundDriverType< 1) ) hidden @endif'>
                <div class='col-sm-2'>
                  <div class='form-group @if($booking->outBoundDriverType != 1) hidden @endif'>
                    <label for="date">Outbound Driver</label>
                    <select class="form-control select2" name="outboundDriverType1" action="{{route('booking.get.driver.clubs')}}"
                    day="{{date('N',strtotime($booking->date))}}">
                      <option value=""></option>
                      @foreach($driverList as $driver)
                        <option @if($booking->outBoundDriverId == $driver->driverId) selected @endif value="{{$driver->driverId}}">{{$driver->forname." ".$driver->surname}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class='form-group  @if($booking->outBoundDriverType != 2) hidden @endif'>
                    <label for="date">Outbound Driver</label>
                    <select class="form-control select2 " name="outboundDriverType2">
                      <option value=""></option>
                      @foreach($liftStaffs as $lift)
                      <option value="{{$lift->staffId}}" @if($booking->outBoundDriverId ==$lift->staffId) selected @endif>{{$lift->forname}} {{$lift->surname}}</option>
                      @endforeach
                     </select>
                  </div>
                  <div class='form-group @if($booking->outBoundDriverType != 3) hidden @endif'>
                    <label for="date">Outbound Driver</label>
                    <select class="form-control select2 " name="outboundDriverType3">
                      <option value=""></option>
                      <option @if($booking->outBoundDriverId == 1) selected @endif value="1">Bus</option>
                      <option @if($booking->outBoundDriverId == 2) selected @endif value="2">Rail</option>
                      <option @if($booking->outBoundDriverId == 3) selected @endif value="3">Taxi</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">OutClub ID</label>
                    <select class="form-control select2" name="outClubId">
                      @if($booking->outBoundClubId)
                        @foreach($outTripClubs as $club)
                          <option @if($club->clubId == $booking->outBoundClubId) selected="selected" @endif value="{{$club->clubId}}">{{$club->driver->forname}} - {{$club->title}}</option>
                        @endforeach
                      @endif
                    </select>
                </div>
              </div>

              <div class='col-sm-3'>
                <div class='form-group'>
                    <label for="date">Pickup Location</label>
                    <input class="form-control" name="pickupLocation" readonly type="text" value="{{$booking->staff->pickupLocation}}"/>
                </div>
              </div>
              <div class='col-sm-3'>
                <div class='form-group'>
                    <label for="date">Pick Time</label>
                    <input class="form-control" name="outPickTime" type="time" value="{{$booking->outBoundPickupTime}}"/>
                    <p class="error">Pick Time is required</p>
                </div>
              </div>

            </div>
            <div class="row">

              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">Inbound Driver Type</label>
                    <select class="form-control select2" name="inboundDriverType">
                      <option value=""></option>
                      <option @if($booking->inBoundDriverType == 1) selected @endif value="1">Private Driver</option>
                      <option @if($booking->inBoundDriverType == 2) selected @endif value="2">Possible lift</option>
                      <option @if($booking->inBoundDriverType == 3) selected @endif value="3">Public Transport</option>
                      <option @if($booking->inBoundDriverType == 4) selected @endif value="4">Self Transport</option>
                    </select>
                </div>
              </div>

              <div class='inboundDiv @if(empty($booking->inBoundDriverType)||($booking->inBoundDriverType< 1) ) hidden @endif'>
                <div class='col-sm-2'>
                  <div class='form-group @if($booking->inBoundDriverType != 1) hidden @endif'>
                    <label for="date">Inbound Driver</label>
                    <select class="form-control select2 " name="inboundDriverType1" action="{{route('booking.get.driver.clubs')}}"
                    day="{{date('N',strtotime($booking->date))}}">
                      <option value=""></option>
                      @foreach($driverList as $driverIn)
                        <option @if($booking->inBoundDriverId == $driverIn->driverId) selected @endif value="{{$driverIn->driverId}}">
                          {{$driverIn->forname." ".$driverIn->surname}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class='form-group @if($booking->inBoundDriverType != 2) hidden @endif'>
                    <label for="date">Inbound Driver</label>
                    <select class="form-control select2 " name="inboundDriverType2">
                      @foreach($liftStaffs as $lift)
                      <option value="{{$lift->staffId}}" @if($booking->inBoundDriverId ==$lift->staffId) selected @endif>{{$lift->forname}} {{$lift->surname}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class='form-group @if($booking->inBoundDriverType != 3) hidden @endif'>
                    <label for="date">Inbound Driver</label>
                    <select class="form-control select2 " name="inboundDriverType3">
                      <option value=""></option>
                      <option @if($booking->inBoundDriverId == 1) selected @endif value="1">Bus</option>
                      <option @if($booking->inBoundDriverId == 2) selected @endif value="2">Rail</option>
                      <option @if($booking->inBoundDriverId == 3) selected @endif value="3">Taxi</option>
                    </select>
                  </div>



                </div>
              </div>
              <div class='col-sm-2'>
                <div class='form-group'>
                    <label for="date">InClub ID</label>
                    <select class="form-control select2" name="inClubId">
                      @if($booking->inBoundClubId)
                        @foreach($inTripClubs as $club)
                          <option @if($club->clubId == $booking->inBoundClubId) selected="selected" @endif  value="{{$club->clubId}}">{{$club->driver->forname}} - {{$club->title}}</option>
                        @endforeach
                      @endif
                    </select>
                </div>
              </div>
              <input class="form-control" name="inPickTime" type="hidden" value="{{$booking->inBoundPickupTime}}" />
            </div>
          </div>

          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Staff Rate</label>
                  <input class="form-control" name="staffRateOrgnal" readonly value="{{$staffShiftCost}}" />
              </div>
            </div>


            <div class='col-sm-2'>
              <div class='form-group selfDiv @if($booking->modeOfTransport=='2') hidden @endif'>
                  <label for="date">TA</label>
                  <input class="form-control" name="transportAllowence" type="number" step="0.01" value="{{(empty($booking->transportAllowence)?0:$booking->transportAllowence)}}" />
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group selfDiv @if($booking->modeOfTransport=='2') hidden @endif'>
                  <label for="date">Extra TA</label>
                  <input class="form-control" name="extraTA" id="extraTA" type="number" step="0.01" value="{{empty($booking->extraTA)?0:$booking->extraTA}}" />
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Bonus</label>
                  {{-- <input class="form-control" name="bonus" type="number" step="0.01"  value="{{(empty($booking->bonus)?0:$booking->bonus)}}" /> --}}
                  <select class="form-control select2" name="bonus">
                    @for($i=0;$i<=6;$i=$i+0.5)
                    <option @if($booking->bonus == $i) selected @endif value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                  <p class="error">Bonus is required</p>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Bonus Reason</label>
                  <select class="form-control select2" name="bonusReason">
                    <option value=""></option>
                    <option @if($booking->bonusReason == 1) selected @endif value="1">Last min call</option>
                    <option @if($booking->bonusReason == 2) selected @endif value="2">Short Shift-less than 4hr</option>
                    <option @if($booking->bonusReason == 3) selected @endif value="3">Staff cancellation</option>
                    <option @if($booking->bonusReason == 4) selected @endif value="4">Booking error</option>
                    <option @if($booking->bonusReason == 5) selected @endif value="5">Weather Condition</option>
                    <option @if($booking->bonusReason == 6) selected @endif value="6">Staying over time</option>
                    <option @if($booking->bonusReason == 7) selected @endif value="7">Other reason</option>
                  </select>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Bonus Authorized by</label>
                  <input class="form-control" name="bonusAuthorizedBy" type="text" value="{{Auth::user()->name}}" />
              </div>
            </div>

          </div>
          <div class='row'>

            <div class='col-sm-6'>
              <div class='form-group'>
                  <label for="date">Amount to Staff</label>
              <input class="form-control" name="totalAmount" readonly value="{{$booking->total}}" />
                  <p>( Staff rate x Shift hours ) + ( TA x Shift hours )+ ( Extra TA x Shift hours ) + ( Bonus x Shift hours )</p>
                  <p> <span class="redFont">This rate is subjected to tax and pension reduction</span></p>
              </div>
            </div>
          </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <div class="pull-right">
            <input type="submit" class="btn btn-primary" value="Save"/>
          </div>
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>

<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>

@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/bookings/allocate-staff.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
