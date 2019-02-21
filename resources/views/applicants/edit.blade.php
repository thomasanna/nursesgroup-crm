@extends('layouts.template')
@section('title','Edit Applicant')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Applicant - <strong>{{$applicant->forname}} {{$applicant->surname}}</strong></h4>
    </div>
    <div class="pull-right">
      <a @if($page!=1) href="{{route('applicants.home')}}" @else href="{{route('applicants.home.terminated')}}" @endif class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
    @if($page!=1)
    <div class="pull-right m-r-10">
      <a href="{{route('applicants.move.terminated.applicant',encrypt($applicant->applicantId))}}" class="btn btn-primary">
        <i class="fa  fa-plus" aria-hidden="true"></i>
        Move to Terminated</a>
    </div>
    @endif
    @if($page!=1)
    <div class="pull-right m-r-10">
      <a href="hgshg" action="{{route('applicants.move.active.staff',encrypt($applicant->applicantId))}}" class="btn btn-success moveToActive">
        <i class="fa  fa-plus" aria-hidden="true"></i>
        Move to Active Staff</a>
    </div>
    @else
    <div class="pull-right m-r-10">
      <a href="{{route('applicants.move.active.applicant',encrypt($applicant->applicantId))}}" class="btn btn-success">
        <i class="fa  fa-plus" aria-hidden="true"></i>
        Move to Active Applicant</a>
    </div>
    @endif
    <div class="pull-right m-r-10 w13">
      <select class="form-control select2"
      name="switchProgress"
      action="{{route('applicants.change.applicant.progress')}}"
      token="{{ csrf_token() }}"
      applicant="{{encrypt($applicant->applicantId)}}">
        <option value="1" @if($applicant->personalProgress==1) selected="selected" @endif>To Be Started</option>
        <option value="2" @if($applicant->personalProgress==2) selected="selected" @endif>In Progress</option>
        <option value="3" @if($applicant->personalProgress==3) selected="selected" @endif>Completed</option>
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
      <form action="{{route('applicants.update')}}" method="post" enctype="multipart/form-data">
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
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Forname</label>
                  <input class="form-control" name="forname" value="{{$applicant->forname}}" type="text" />
                  <p class="error">Forname is required</p>
                  <input name="pkId" value="{{encrypt($applicant->applicantId)}}" type="hidden" />
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Surname</label>
                  <input class="form-control" name="surname" value="{{$applicant->surname}}" type="text" />
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
                      @if($category->categoryId==$applicant->categoryId) selected="selected" @endif>{{$category->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Category is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Birth</label>
                  <input class="form-control" name="dateOfBirth" type="text" value="{{date('d-m-Y',strtotime($applicant->dateOfBirth))}}" />
                  <p class="error">Date Of Birth is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Join</label>
                  <input class="form-control" name="joinedDate" type="text" value="{{date('d-m-Y',strtotime($applicant->joinedDate))}}" />
                  <p class="error">Date Of Join is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" value="{{$applicant->email}}" name="email" type="text" />
                  <p class="error">Email is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="mobile">Mobile</label>
                  <input class="form-control" value="{{$applicant->mobile}}" name="mobile" type="text" />
                  <p class="error">Mobile is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>WhatsApp Number</label>
                  <input class="form-control" value="{{$applicant->whatsappNumber}}" name="whatsappNumber" type="text" />
                  <p class="error">WhatsApp Number is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>LandLine Number</label>
                  <input class="form-control" name="lanLineNumber"  value="{{$applicant->lanLineNumber}}" type="text" />
                  <p class="error">LanLine Number is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Address</label>
                  <textarea class="form-control" name="address">{{$applicant->address}}</textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Post Code</label>
                  <input class="form-control" name="pincode" value="{{$applicant->pincode}}" type="text" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <label for="">Gender</label>
              <div class='form-group'>
                  <label for="male">Male</label>
                  <input type="radio" id="male" name="gender" @if($applicant->gender==1) checked="checked" @endif value="1">
                  <label for="female">Female</label>
                  <input type="radio" id="female" name="gender" @if($applicant->gender==2) checked="checked" @endif value="2">
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
                  <input class="form-control" name="niNumber" value="{{$applicant->niNumber}}" type="text" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>NMC PIN Number</label>
                  <input class="form-control" name="nmcPinNumber" type="text" value="{{$applicant->nmcPinNumber}}" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>NMC PIN Expiry Date</label>
                  <input class="form-control" name="nmcPinExpiryDate" type="text" value="{{date('d-m-Y',strtotime($applicant->nmcPinExpiryDate))}}" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>NMC PIN Re-Validation Date</label>
                  <input class="form-control" name="nmcPinReValidationDate" type="text" value="{{date('d-m-Y',strtotime($applicant->nmcPinExpiryDate))}}" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
            @if($applicant->photoExist==1)
            <div class='col-sm-2'>
              <div class='form-group pull-right'>
                  <img class="stfImg" src="{{asset('storage/app/applicant/photo/'.$applicant->photo)}}" alt="{{$applicant->forname}} {{$applicant->surname}}">
              </div>
            </div>
            @endif
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>NI Document</label>
                  <input class="form-control" name="niDocumentFile" type="file" />
                  <p class="error">NI Document is required</p>
              </div>
            </div>
            @if($applicant->niDocumentExist==1)
            <div class='col-sm-4'>
              <div class='form-group'>
                <label class="col-md-12">&nbsp;</label>
                <a href="{{asset('storage/app/applicant/applicant_ni/'.$applicant->niDocumentFile)}}" target="_blank" class="btn btn-primary">View Document</a>
              </div>
            </div>
            @endif
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Latest Tax Band</label>
                  <input class="form-control" name="latestTaxBand" value="{{$applicant->latestTaxBand}}" type="text" />
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
                      @if($applicant->modeOfTransport==$transport->transportId) selected="selected" @endif>{{$transport->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Mode Of Transport is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Pick up Location</label>
                  <input class="form-control" type="text" value="{{$applicant->pickupLocation}}" name="pickupLocation"/>
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
                    <option @if($branch->branchId==$applicant->branchId) selected="selected" @endif value="{{$branch->branchId}}">{{$branch->name}}</option>
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
                  <select class="form-control select2" name="zoneId"
                  unit="{{route('client_units.get.by.zone')}}"
                  token="{{ csrf_token() }}">
                    <option></option>
                    @foreach($zones as $zone)
                    <option @if($zone->id==$applicant->zoneId) selected="selected" @endif value="{{$zone->id}}">{{$zone->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Zone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Band</label>
                  <select class="form-control select2" name="bandId"
                  payrate="{{route('bands.get.applicant.payrate')}}"
                  token="{{ csrf_token() }}">
                    <option></option>
                    @foreach($bands as $band)
                    <option value="{{$band->bandId}}" @if($applicant->bandId==$band->bandId) selected="selected" @endif>{{$band->name}}</option>
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
                    <option value="1" @if($applicant->paymentMode==1) selected="selected" @endif>Self</option>
                    <option value="2" @if($applicant->paymentMode==2) selected="selected" @endif>Payee</option>
                  </select>
                  <p class="error">Payment Mode is required</p>
              </div>
            </div>
          </div>
          <div class='row @if($applicant->paymentMode==2) hidden @endif paymentSelf'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Name</label>
                  <input class="form-control" name="selfPaymentCompanyName" value="{{$applicant->selfPaymentCompanyName}}" type="text" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Number</label>
                  <input class="form-control" name="selfPaymentCompanyNumber" value="{{$applicant->selfPaymentCompanyNumber}}" type="text" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Registered Address</label>
                  <textarea class="form-control" name="selfPaymentCompanyRegAddress" type="text" />{{$applicant->selfPaymentCompanyRegAddress}}</textarea>
                  <p class="error">Alternative Phone Number is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class='col-sm-8'>
              <div class='form-group'>
                  <label for="date">Preferred Units</label>
                  <select class="form-control select2 multiple" multiple="multiple" name="unitId[]">
                    @foreach($units as $unit)
                    <option @if(in_array($unit->clientUnitId,$applicantPclients)) selected="selected" @endif value="{{$unit->clientUnitId}}">{{$unit->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Preferred Units is required</p>
              </div>
            </div>
          </div>
          <div class="m-t-5 smlHead">Next Of Kin</div>
          <hr>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>FullName</label>
                  <input class="form-control" name="nokFullName" type="text" value="{{$applicant->nokFullName}}" />
                  <p class="error">Forname is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Relationship</label>
                  <select class="form-control select2" name="nokRelationship">
                    <option></option>
                    <option value="1" @if($applicant->nokRelationship==1) selected="selected" @endif>Father</option>
                    <option value="2" @if($applicant->nokRelationship==2) selected="selected" @endif>Mother</option>
                    <option value="3" @if($applicant->nokRelationship==3) selected="selected" @endif>Partner</option>
                    <option value="4" @if($applicant->nokRelationship==4) selected="selected" @endif>Spouse</option>
                    <option value="5" @if($applicant->nokRelationship==5) selected="selected" @endif>Friend</option>
                    <option value="6" @if($applicant->nokRelationship==6) selected="selected" @endif>Siblings</option>
                    <option value="7" @if($applicant->nokRelationship==7) selected="selected" @endif>Nighbour</option>
                    <option value="8" @if($applicant->nokRelationship==8) selected="selected" @endif>Son</option>
                    <option value="9" @if($applicant->nokRelationship==9) selected="selected" @endif>Daughter</option>
                    <option value="10" @if($applicant->nokRelationship==10) selected="selected" @endif>Family Member</option>
                  </select>
                  <p class="error">Relationship is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Mobile</label>
                  <input class="form-control" name="nokMobile" type="text" value="{{$applicant->nokMobile}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" name="nokEmail" type="text" value="{{$applicant->nokEmail}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Address</label>
                  <textarea class="form-control" name="nokAddress">{{$applicant->nokAddress}}</textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Postcode</label>
                  <input class="form-control" name="nokPostCode" type="text" value="{{$applicant->nokPostCode}}" />
                  <p class="error">Surname is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Phone</label>
                  <input class="form-control" name="nokPhone" type="text" value="{{$applicant->nokPhone}}" />
                  <p class="error">Surname is required</p>
              </div>
            </div>
          </div>
          <div class="m-t-5 smlHead">PayRate</div>
          <hr>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Week Day</label>
                  <input class="form-control" value="{{$applicant->payRateWeekday}}" name="payRateWeekday" type="text" />
                  <p class="error">PayRate - Week Day is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - WeekNight </label>
                  <input class="form-control" value="{{$applicant->payRateWeekNight}}" name="payRateWeekNight" type="text" />
                  <p class="error">PayRate - Special BH Day is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - WeekendDay</label>
                  <input class="form-control" value="{{$applicant->payRateWeekendDay}}" name="payRateWeekendDay" type="text" />
                  <p class="error">PayRate - WeekNight is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Weekend Night</label>
                  <input class="form-control" value="{{$applicant->payRateWeekendNight}}" name="payRateWeekendNight" type="text" />
                  <p class="error">PayRate - Special BH Nightis required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Special BH Day</label>
                  <input class="form-control" value="{{$applicant->payRateSpecialBhday}}" name="payRateSpecialBhday" type="text" />
                  <p class="error">PayRate - WeekendDay is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - Special BH Night</label>
                  <input class="form-control" value="{{$applicant->payRateSpecialBhnight}}" name="payRateSpecialBhnight" type="text" />
                  <p class="error">PayRate - BH Day is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - BH Day</label>
                  <input class="form-control" value="{{$applicant->payRateBhday}}" name="payRateBhday" type="text" />
                  <p class="error">PayRate - WeekendNight is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>PayRate - BH Night</label>
                  <input class="form-control" value="{{$applicant->payRateBhnight}}" name="payRateBhnight" type="text" />
                  <p class="error">PayRate - BH Night is required</p>
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
                  <input class="form-control" value="{{$applicant->bankSortCodeA}}" name="bankSortCodeA" type="text" />
                  <p class="error">Sort Code is required</p>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" value="{{$applicant->bankSortCodeB}}" name="bankSortCodeB" type="text" />
                  <p class="error">Sort Code is required</p>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" value="{{$applicant->bankSortCodeC}}" name="bankSortCodeC" type="text" />
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
                  <input class="form-control" value="{{$applicant->bankAccountNumber}}" name="bankAccountNumber" type="text" />
                  <p class="error">Account Number is required</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class='col-sm-4'>
              <label for="">Status</label>
              <div class='form-group'>
                  <label for="active">Active</label>
                  <input type="radio" id="active" name="status" @if($applicant->status==1) checked="checked" @endif value="1">
                  <label for="inactive">Inactive</label>
                  <input type="radio" id="inactive" name="status" @if($applicant->status==0) checked="checked" @endif value="0">
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
<script src="{{asset('public/js/pages/applicants/edit_staff.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
