@extends('layouts.template')
@section('title','Edit Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Staff - <strong>{{$staff->forname}} {{$staff->surname}}</strong></h4>
    </div>
    <div class="pull-right">

      <a @if($staff->status==1) href="{{route('staffs.home.active',$searchKeyword)}}"
        @else  href="{{route('staffs.home.inactive',$searchKeyword)}}"
        @endif class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>

    <div class="pull-right m-r-10 w13">
      <select class="form-control select2"
      name="switchProgress"
      action="{{route('staffs.change.progress')}}"
      token="{{ csrf_token() }}"
      staff="{{encrypt($staff->staffId)}}">
        <option value="1" @if($staff->personalProgress==1) selected="selected" @endif>To Be Started</option>
        <option value="2" @if($staff->personalProgress==2) selected="selected" @endif>In Progress</option>
        <option value="3" @if($staff->personalProgress==3) selected="selected" @endif>Completed</option>
      </select>
    </div>
    <div class="pull-right m-r-10 m-t-7">
      <label for="">Progress</label>
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
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('staffs.update')}}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <label for="date">Title</label>
                  <select class="form-control select2" name="title">
                    <option></option>
                    <option value="mr" @if($staff->title=="mr") selected="selected" @endif>Mr</option>
                    <option value="mrs" @if($staff->title=="mrs") selected="selected" @endif>Mrs</option>
                    <option value="miss" @if($staff->title=="miss") selected="selected" @endif>Miss</option>
                    <option value="ms" @if($staff->title=="ms") selected="selected" @endif>Ms</option>
                    <option value="mx" @if($staff->title=="mx") selected="selected" @endif>Mx</option>
                  </select>
                  <p class="error">title is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Forname</label>
                  <input class="form-control" name="forname" value="{{$staff->forname}}" type="text" />
                  <p class="error">Forname is required</p>
                  <input name="pkId" value="{{encrypt($staff->staffId)}}" type="hidden" />
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Surname</label>
                  <input class="form-control" name="surname" value="{{$staff->surname}}" type="text" />
                  <p class="error">Surname is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Category</label>
                  <select class="form-control select2" name="categoryId">
                    <option></option>
                    @foreach($categories as $category)
                    <option value="{{$category->categoryId}}"
                      @if($category->categoryId==$staff->categoryId) selected="selected" @endif>{{$category->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Category is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Birth</label>
                  <input class="form-control" name="dateOfBirth" type="text"  value="{{date('d-m-Y',strtotime($staff->dateOfBirth))}}"/>
                  <p class="error">Date Of Birth is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Join</label>
                  <input class="form-control" name="joinedDate" type="text" value="{{date('d-m-Y',strtotime($staff->joinedDate))}}" />
                  <p class="error">Date Of Join is required</p>
              </div>
            </div>

          </div>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" value="{{$staff->email}}" name="email" type="text" />
                  <p class="error">Email is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="mobile">Mobile</label>
                  <input class="form-control regBg" value="{{$staff->mobile}}" name="mobile" type="text" />
                  <p class="error">Mobile is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>WhatsApp Number</label>
                  <input class="form-control" value="{{$staff->whatsappNumber}}" name="whatsappNumber" type="text" />
                  <p class="error">WhatsApp Number is required</p>
              </div>
            </div>

            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>LandLine Number</label>
                  <input class="form-control" name="lanLineNumber"  value="{{$staff->lanLineNumber}}" type="text" />
                  <p class="error">LanLine Number is required</p>
              </div>
            </div>
         </div>
           <div class='row'>
                   <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="username">User Name</label>
                   <input class="form-control" name="username" type="text" value="{{$staff->username}}" />
                  <p class="error">Username is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="password">Password</label>
                  <input class="form-control" name="password" type="text" />
                  <p class="error">Password is required</p>
                </div>
              </div>
          </div>
          </div>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Address</label>
                  <textarea class="form-control" name="address">{{$staff->address}}</textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <label>Post Code</label>
                  <input class="form-control regBg" name="pincode" value="{{$staff->pincode}}" type="text" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Latitude</label>
                  <input class="form-control" name="latitude" value="{{$staff->latitude}}" type="text" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Longitude</label>
                  <input class="form-control" name="longitude" value="{{$staff->longitude}}" type="text" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>

            <div class='col-sm-2'>
              <label for="">Gender</label>
              <div class='form-group'>
                  <label for="male">Male</label>
                  <input type="radio" id="male" name="gender" @if($staff->gender==1) checked="checked" @endif value="1">
                  <label for="female">Female</label>
                  <input type="radio" id="female" name="gender" @if($staff->gender==2) checked="checked" @endif value="2">
              </div>
            </div>
            <div class='col-sm-2'>
              <label for="">Is Permanent</label>
              <div class='form-group'>
                  <label for="no">No</label>
                  <input type="radio" id="no" name="isPermenent" @if($staff->isPermenent==0) checked="checked" @endif value="0">
                  <label for="yes">Yes</label>
                  <input type="radio" id="yes" name="isPermenent" @if($staff->isPermenent==1) checked="checked" @endif value="1">
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Photo</label>
                  <input class="form-control" name="photo" type="file" />
                  <p class="error">Photo is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>NI Number</label>
                  <input class="form-control" name="niNumber" value="{{$staff->niNumber}}" type="text" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>NMC PIN Number</label>
                  <input class="form-control" name="nmcPinNumber" type="text" value="{{$staff->nmcPinNumber}}" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>NMC PIN Expiry Date</label>
                  <input class="form-control" name="nmcPinExpiryDate" type="text" value="{{date('d-m-Y',strtotime($staff->nmcPinExpiryDate))}}" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>NMC PIN Re-Validation Date</label>
                  <input class="form-control" name="nmcPinReValidationDate" type="text" value="{{date('d-m-Y',strtotime($staff->nmcPinReValidationDate))}}" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group pull-right'>
                  <img class="stfImg" src="{{asset('storage/app/staff/photo/'.$staff->photo)}}" alt="jhgfjhg">
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>NI Document</label>
                  <input class="form-control" name="niDocumentFile" type="file" />
                  <p class="error">NI Document is required</p>
              </div>
            </div>
            @if($staff->niDocumentExist==1)
            <div class='col-sm-4'>
              <div class='form-group'>
                <label class="col-md-12">&nbsp;</label>
                <a href="{{asset('storage/app/staff/staff_ni/'.$staff->niDocumentFile)}}" target="_blank" class="btn btn-primary">View Document</a>
              </div>
            </div>
            @endif
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Latest Tax Band</label>
                  <input class="form-control" name="latestTaxBand" value="{{$staff->latestTaxBand}}" type="text" />
                  <p class="error">Latest Tax Band is required</p>
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
                    <option value="{{$transport->transportId}}"
                      @if($staff->modeOfTransport==$transport->transportId) selected="selected" @endif>{{$transport->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Mode Of Transport is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Pick up Location</label>
                  <input class="form-control regBg" type="text" value="{{$staff->pickupLocation}}" name="pickupLocation"/>
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
                    <option @if($branch->branchId==$staff->branchId) selected="selected" @endif value="{{$branch->branchId}}">{{$branch->name}}</option>
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
                    <option></option>
                    @foreach($zones as $zone)
                    <option @if($zone->id==$staff->zoneId) selected="selected" @endif value="{{$zone->id}}">{{$zone->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Zone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Band</label>
                  <select class="form-control select2" name="bandId">
                    <option></option>
                    @foreach($bands as $band)
                    <option value="{{$band->bandId}}" @if($staff->bandId==$band->bandId) selected="selected" @endif>{{$band->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Band is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Payment Mode</label>
                  <select class="form-control select2" name="paymentMode">
                    <option></option>
                    <option value="1" @if($staff->paymentMode==1) selected="selected" @endif>Self</option>
                    <option value="2" @if($staff->paymentMode==2) selected="selected" @endif>Payee</option>
                  </select>
                  <p class="error">Payment Mode is required</p>
              </div>
            </div>
          </div>
          <div class='row @if($staff->paymentMode==2) hidden @endif paymentSelf'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Name</label>
                  <input class="form-control" name="selfPaymentCompanyName" value="{{$staff->selfPaymentCompanyName}}" type="text" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Number</label>
                  <input class="form-control" name="selfPaymentCompanyNumber" value="{{$staff->selfPaymentCompanyNumber}}" type="text" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Registered Address</label>
                  <textarea class="form-control" name="selfPaymentCompanyRegAddress" type="text" />{{$staff->selfPaymentCompanyRegAddress}}</textarea>
                  <p class="error">Alternative Phone Number is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class='col-sm-5'>
              <div class='form-group'>
                  <label for="date">Preferred Units</label>
                  <select class="form-control select2 multiple" multiple="multiple" name="unitIds[]">
                    @foreach($units as $unit)
                    <option @if(in_array($unit->clientUnitId,$staffPclients)) selected="selected" @endif
                      value="{{$unit->clientUnitId}}">{{$unit->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Preferred Units is required</p>
              </div>
            </div>
            <div class='col-sm-7'>
              <div class='form-group'>
                  <label for="date"> Quick Notes</label>
                  <textarea name="quickNotes" class="form-control" cols="30" rows="10">{{$staff->quickNotes}}</textarea>
                  <p class="error">Preferred Units is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="m-t-5 smlHead">Next Of Kin</div>
          <hr>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>FullName</label>
                  <input class="form-control" name="nokFullName" type="text" value="{{$staff->nokFullName}}" />
                  <p class="error">Forname is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Relationship</label>
                  <select class="form-control select2" name="nokRelationship">
                    <option></option>
                    <option value="1" @if($staff->nokRelationship==1) selected="selected" @endif>Father</option>
                    <option value="2" @if($staff->nokRelationship==2) selected="selected" @endif>Mother</option>
                    <option value="3" @if($staff->nokRelationship==3) selected="selected" @endif>Partner</option>
                    <option value="4" @if($staff->nokRelationship==4) selected="selected" @endif>Spouse</option>
                    <option value="5" @if($staff->nokRelationship==5) selected="selected" @endif>Friend</option>
                    <option value="6" @if($staff->nokRelationship==6) selected="selected" @endif>Siblings</option>
                    <option value="7" @if($staff->nokRelationship==7) selected="selected" @endif>Nighbour</option>
                    <option value="8" @if($staff->nokRelationship==8) selected="selected" @endif>Son</option>
                    <option value="9" @if($staff->nokRelationship==9) selected="selected" @endif>Daughter</option>
                    <option value="10" @if($staff->nokRelationship==10) selected="selected" @endif>Family Member</option>
                  </select>
                  <p class="error">Relationship is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Mobile</label>
                  <input class="form-control" name="nokMobile" type="text" value="{{$staff->nokMobile}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" name="nokEmail" type="text" value="{{$staff->nokEmail}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Address</label>
                  <textarea class="form-control" name="nokAddress">{{$staff->nokAddress}}</textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Postcode</label>
                  <input class="form-control" name="nokPostCode" type="text" value="{{$staff->nokPostCode}}" />
                  <p class="error">Surname is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Phone</label>
                  <input class="form-control" name="nokPhone" type="text" value="{{$staff->nokPhone}}" />
                  <p class="error">Surname is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Week Day</label>
                  <input class="form-control" value="{{$staff->payRateWeekday}}" name="payRateWeekday" type="text" />
                  <p class="error">PayRate - Week Day is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - WeekNight </label>
                  <input class="form-control" value="{{$staff->payRateWeekNight}}" name="payRateWeekNight" type="text" />
                  <p class="error">PayRate - Special BH Day is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - WeekendDay</label>
                  <input class="form-control" value="{{$staff->payRateWeekendDay}}" name="payRateWeekendDay" type="text" />
                  <p class="error">PayRate - WeekNight is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Weekend Night</label>
                  <input class="form-control" value="{{$staff->payRateWeekendNight}}" name="payRateWeekendNight" type="text" />
                  <p class="error">PayRate - Special BH Nightis required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Special BH Day</label>
                  <input class="form-control" value="{{$staff->payRateSpecialBhday}}" name="payRateSpecialBhday" type="text" />
                  <p class="error">PayRate - WeekendDay is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Special BH Night</label>
                  <input class="form-control" value="{{$staff->payRateSpecialBhnight}}" name="payRateSpecialBhnight" type="text" />
                  <p class="error">PayRate - BH Day is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - BH Day</label>
                  <input class="form-control" value="{{$staff->payRateBhday}}" name="payRateBhday" type="text" />
                  <p class="error">PayRate - WeekendNight is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - BH Night</label>
                  <input class="form-control" value="{{$staff->payRateBhnight}}" name="payRateBhnight" type="text" />
                  <p class="error">PayRate - BH Night is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Performance Point</label>
                  <input class="form-control" value="{{$staff->performancePoint}}" name="performancePoint" type="text" />
                  <p class="error">Performance Point is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Sort Code</label>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" value="{{$staff->bankSortCodeA}}" name="bankSortCodeA" type="text" />
                  <p class="error">Sort Code is required</p>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" value="{{$staff->bankSortCodeB}}" name="bankSortCodeB" type="text" />
                  <p class="error">Sort Code is required</p>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" value="{{$staff->bankSortCodeC}}" name="bankSortCodeC" type="text" />
                  <p class="error">Sort Code is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Bank Account Number</label>
              </div>
            </div>
            <div class='col-sm-5'>
              <div class='form-group'>
                  <input class="form-control" value="{{$staff->bankAccountNumber}}" name="bankAccountNumber" type="text" />
                  <p class="error">Account Number is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class='col-sm-4'>
              <label for="">Status</label>
              <div class='form-group'>
                  <label for="active">Active</label>
                  <input type="radio" id="active" name="status" @if($staff->status==1) checked="checked" @endif value="1">
                  <label for="inactive">Inactive</label>
                  <input type="radio" id="inactive" name="status" @if($staff->status==0) checked="checked" @endif value="0">
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
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>

<script src="{{asset('public/js/wysihtml5-0.3.0.js')}}"></script>
<script src="{{asset('public/js/prettify.js')}}"></script>
<script src="{{asset('public/js/bootstrap-wysihtml5.js')}}"></script>

<script src="{{asset('public/js/pages/staffs/edit_staff.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('public/css/prettify.css')}}"></link>
<link rel="stylesheet" href="{{asset('public/css/bootstrap-wysihtml5.css')}}"></link>
@endpush
