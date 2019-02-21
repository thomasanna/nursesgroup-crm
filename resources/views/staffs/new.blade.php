@extends('layouts.template')
@section('title','New Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Staff</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('staffs.home')}}" class="btn btn-warning">
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
      <form action="{{route('staffs.save')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <label for="date">Title</label>
                  <select class="form-control select2" name="title">
                    <option></option>
                    <option value="mr" @if($applicant->title=="mr") selected="selected" @endif>Mr</option>
                    <option value="mrs" @if($applicant->title=="mrs") selected="selected" @endif>Mrs</option>
                    <option value="miss" @if($applicant->title=="miss") selected="selected" @endif>Miss</option>
                    <option value="ms" @if($applicant->title=="ms") selected="selected" @endif>Ms</option>
                    <option value="mx" @if($applicant->title=="mx") selected="selected" @endif>Mx</option>
                  </select>
                  <p class="error">title is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Forname</label>
                  <input class="form-control" name="forname" type="text" />
                  <p class="error">Forname is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Surname</label>
                  <input class="form-control" name="surname" type="text" />
                  <p class="error">Surname is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Category</label>
                  <select class="form-control select2" name="categoryId">
                    <option></option>
                    @foreach($categories as $category)
                    <option value="{{$category->categoryId}}">{{$category->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Category is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Birth</label>
                  <input class="form-control" name="dateOfBirth" type="text"/>
                  <p class="error">Date Of Birth is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Join</label>
                  <input class="form-control" name="joinedDate" type="text" />
                  <p class="error">Date Of Join is required</p>
              </div>
            </div>

          </div>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" name="email" type="text" />
                  <p class="error">Email is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="mobile">Mobile</label>
                  <input class="form-control regBg" name="mobile" type="text" />
                  <p class="error">Mobile is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>WhatsApp Number</label>
                  <input class="form-control" name="whatsappNumber" type="text" />
                  <p class="error">WhatsApp Number is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>LandLine Number</label>
                  <input class="form-control" name="lanLineNumber" type="text" />
                  <p class="error">LanLine Number is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Address</label>
                  <textarea class="form-control" name="address"></textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Post Code</label>
                  <input class="form-control" name="pincode" type="text" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <label for="">Gender</label>
              <div class='form-group'>
                  <label for="male">Male</label>
                  <input type="radio" id="male" name="gender" value="1" checked="checked"/>
                  <label for="female">Female</label>
                  <input type="radio" id="female" name="gender" value="2"/>
              </div>
            </div>
          </div>
          <hr>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Mode Of Transport</label>
                  <select class="form-control select2" name="modeOfTransport">
                    <option></option>
                    @foreach($transports as $transport)
                    <option value="{{$transport->transportId}}">{{$transport->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Mode Of Transport is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Pick up Location</label>
                  <input class="form-control" type="text" name="pickupLocation"/>
                  <p class="error">Pick up Location is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Home Branch</label>
                  <select class="form-control select2"
                        name="branchId"
                        zone="{{route('zones.get')}}"
                        token="{{ csrf_token() }}">
                    <option></option>
                    @foreach($branches as $branch)
                    <option value="{{$branch->branchId}}">{{$branch->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Branch is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Zone</label>
                  <select class="form-control select2" name="zoneId">
                  </select>
                  <p class="error">Zone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Band</label>
                  <select class="form-control select2" name="bandId">
                    <option></option>
                    <option value="1">Band 1</option>
                    <option value="2">Band 2</option>
                  </select>
                  <p class="error">Band is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Payment Mode</label>
                  <select class="form-control select2" name="paymentMode">
                    <option></option>
                    <option value="1">Self</option>
                    <option value="2">Payee</option>
                  </select>
                  <p class="error">Payment Mode is required</p>
              </div>
            </div>
          </div>
          <div class='row hidden paymentSelf'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Name</label>
                  <input class="form-control" name="selfPaymentCompanyName" type="text" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Number</label>
                  <input class="form-control" name="selfPaymentCompanyNumber"  type="text" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Registered Address</label>
                  <textarea class="form-control" name="selfPaymentCompanyRegAddress" type="text" /></textarea>
                  <p class="error">Alternative Phone Number is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class='col-sm-8'>
              <div class='form-group'>
                  <label for="date">Preferred Units</label>
                  <select class="form-control select2 multiple" multiple="multiple" name="units[]">
                    @foreach($units as $unit)
                    <option value="{{$unit->clientId}}">{{$unit->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Preferred Units is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Week Day</label>
                  <input class="form-control" name="payRateWeekday" type="text" />
                  <p class="error">PayRate - Week Day is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Special BH Day</label>
                  <input class="form-control" name="payRateWeekNight" type="text" />
                  <p class="error">PayRate - Special BH Day is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - WeekNight</label>
                  <input class="form-control" name="payRateWeekendDay" type="text" />
                  <p class="error">PayRate - WeekNight is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Special BH Night</label>
                  <input class="form-control" name="payRateWeekendNight" type="text" />
                  <p class="error">PayRate - Special BH Nightis required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - WeekendDay</label>
                  <input class="form-control" name="payRateSpecialBhday" type="text" />
                  <p class="error">PayRate - WeekendDay is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - BH Day</label>
                  <input class="form-control" name="payRateSpecialBhnight" type="text" />
                  <p class="error">PayRate - BH Day is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - WeekendNight</label>
                  <input class="form-control" name="payRateBhday" type="text" />
                  <p class="error">PayRate - WeekendNight is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - BH Night</label>
                  <input class="form-control" name="payRateBhnight" type="text" />
                  <p class="error">PayRate - BH Night is required</p>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="submit" class="btn btn-primary" value="Save"/>
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/staffs/new_staff.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
