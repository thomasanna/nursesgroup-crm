@extends('layouts.template')
@section('title','New Driver')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Driver - {{$driver->forname}} {{$driver->surname}}</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('drivers.home')}}" class="btn btn-warning">
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
      <form action="{{route('drivers.update')}}" method="post" enctype="multipart/form-data" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Forname</label>
                  <input class="form-control" name="forname" type="text" value="{{$driver->forname}}" />
                  <input name="pkId" value="{{encrypt($driver->driverId)}}" type="hidden" />
                  <p class="error">Forname is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Surname</label>
                  <input class="form-control" name="surname" type="text" value="{{$driver->forname}}" />
                  <p class="error">Surname is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Birth</label>
                  <input class="form-control" name="dateOfBirth" type="text" value="{{date('d-m-Y',strtotime($driver->dateOfBirth))}}" />
                  <p class="error">Date Of Birth is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Join</label>
                  <input class="form-control" name="joinedDate" type="text" value="{{date('d-m-Y',strtotime($driver->joinedDate))}}" />
                  <p class="error">Date Of Join is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <label for="">Gender</label>
              <div class='form-group'>
                  <label for="male">Male</label>
                  <input type="radio" id="male" name="gender" value="1" @if($driver->gender==1) checked="checked" @endif/>
                  <label for="female">Female</label>
                  <input type="radio" id="female" name="gender" value="2" @if($driver->gender==2) checked="checked" @endif/>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" name="email" type="text" value="{{$driver->email}}" />
                  <p class="error">Email is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="mobile">Mobile</label>
                  <input class="form-control" name="mobile" type="text" value="{{$driver->mobile}}" />
                  <p class="error">Mobile is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>WhatsApp Number</label>
                  <input class="form-control" name="whatsappNumber" type="text" value="{{$driver->whatsappNumber}}" />
                  <p class="error">WhatsApp Number is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>LanLine Number</label>
                  <input class="form-control" name="lanLineNumber" type="text" value="{{$driver->lanLineNumber}}" />
                  <p class="error">LanLine Number is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Address</label>
                  <textarea class="form-control" name="address"> {{$driver->address}}</textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Post Code</label>
                  <input class="form-control" name="pincode" type="text" value="{{$driver->pincode}}" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Latest Tax Band</label>
                  <input class="form-control" name="latestTaxBand" type="text" value="{{$driver->latestTaxBand}}" />
                  <p class="error">Latest Tax Band is required</p>
              </div>
            </div>

            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>NI Number</label>
                  <input class="form-control" name="niNumber" type="text" value="{{$driver->niNumber}}" />
                  <p class="error">NI Number is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Photo</label>
                  <input class="form-control" name="photo" type="file" />
                  <p class="error">Photo is required</p>
              </div>
              @if($driver->photoExist==1)
              <div class='form-group pull-left'>
                  <img class="stfImg" src="{{asset('storage/app/drivers/photo/'.$driver->photo)}}" alt="{{$driver->forname}} {{$driver->surname}}">
              </div>
              @endif
            </div>

            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>NI Document</label>
                  <input class="form-control" name="niDocumentFile" type="file" />
                  <p class="error">NI Document is required</p>
              </div>

              <div class='form-group'>
                  <label for="date">Home Branch</label>
                  <select class="form-control select2"
                        name="branchId"
                        zone="{{route('zones.get')}}"
                        token="{{ csrf_token() }}">
                    <option></option>
                    @foreach($branches as $branch)
                    <option value="{{$branch->branchId}}" @if($branch->branchId==$driver->branchId) selected="selected" @endif>{{$branch->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Branch is required</p>
              </div>

              <div class='form-group'>
                  <label for="date">Zone</label>
                  <select class="form-control select2" name="zoneId"
                  unit="{{route('client_units.get.by.zone')}}"
                  token="{{ csrf_token() }}">
                  @foreach($zones as $zone)
                  <option @if($zone->id==$driver->zoneId) selected="selected" @endif value="{{$zone->id}}">{{$zone->name}}</option>
                  @endforeach
                  </select>
                  <p class="error">Zone is required</p>
              </div>

              <div class='form-group'>
                  <label>Payment Mode</label>
                  <select class="form-control select2" name="paymentMode">
                    <option></option>
                    <option value="1" @if($driver->paymentMode==1) selected="selected" @endif>Self</option>
                    <option value="2" @if($driver->paymentMode==2) selected="selected" @endif>Payee</option>
                  </select>
                  <p class="error">Payment Mode is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              @if($driver->niDocumentExist==1)
                <div class='form-group'>
                  <label class="col-md-12">&nbsp;</label>
                  <a href="{{asset('storage/app/drivers/driver_ni/'.$driver->niDocumentFile)}}" target="_blank" class="btn btn-primary">View Document</a>
                </div>
              @endif
            </div>


          </div>
          <hr>
          <div class='row @if($driver->paymentMode==2) hidden @endif paymentSelf'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Name</label>
                  <input class="form-control" name="selfPaymentCompanyName" type="text" value="{{$driver->selfPaymentCompanyName}}" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Number</label>
                  <input class="form-control" name="selfPaymentCompanyNumber" type="text" value="{{$driver->selfPaymentCompanyNumber}}" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Registered Address</label>
                  <textarea class="form-control" name="selfPaymentCompanyRegAddress" type="text" /> {{$driver->selfPaymentCompanyRegAddress}}</textarea>
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
                    <option @if(in_array($unit->clientUnitId,$driverPUnits)) selected="selected" @endif value="{{$unit->clientUnitId}}">{{$unit->name}}</option>
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
                  <input class="form-control" name="nokFullName" type="text" value="{{$driver->nokFullName}}"/>
                  <p class="error">FullName is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Relationship</label>
                  <select class="form-control select2" name="nokRelationship">
                    <option></option>
                    <option value="1" @if($driver->nokRelationship==1) selected="selected" @endif>Father</option>
                    <option value="2" @if($driver->nokRelationship==2) selected="selected" @endif>Mother</option>
                    <option value="3" @if($driver->nokRelationship==3) selected="selected" @endif>Partner</option>
                    <option value="4" @if($driver->nokRelationship==4) selected="selected" @endif>Spouse</option>
                    <option value="5" @if($driver->nokRelationship==5) selected="selected" @endif>Friend</option>
                    <option value="6" @if($driver->nokRelationship==6) selected="selected" @endif>Siblings</option>
                    <option value="7" @if($driver->nokRelationship==7) selected="selected" @endif>Nighbour</option>
                    <option value="8" @if($driver->nokRelationship==8) selected="selected" @endif>Son</option>
                    <option value="9" @if($driver->nokRelationship==9) selected="selected" @endif>Daughter</option>
                    <option value="10" @if($driver->nokRelationship==10) selected="selected" @endif>Family Member</option>
                  </select>
                  <p class="error">Relationship is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Mobile</label>
                  <input class="form-control" name="nokMobile" type="text" value="{{$driver->nokMobile}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" name="nokEmail" type="text" value="{{$driver->nokEmail}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Address</label>
                  <textarea class="form-control" name="nokAddress"> {{$driver->nokAddress}}</textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Postcode</label>
                  <input class="form-control" name="nokPostCode" type="text" value="{{$driver->nokPostCode}}" />
                  <p class="error">Postcode is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Phone</label>
                  <input class="form-control" name="nokPhone" type="text" value="{{$driver->nokPhone}}" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
          </div>
          <div class="m-t-5 smlHead">PayRate</div>
          <hr>
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Rate Per Mile</label>
                  <input class="form-control" name="ratePerMile" type="text" value="{{$driver->ratePerMile}}" />
                  <p class="error">PayRate - Week Day is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Extra Staff Rate </label>
                  <input class="form-control" name="extraStaffRate" type="text" value="{{$driver->extraStaffRate}}" />
                  <p class="error">PayRate - Special BH Day is required</p>
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
                  <input class="form-control" name="bankSortCodeA" type="text" value="{{$driver->bankSortCodeA}}" />
                  <p class="error">Sort Code is required</p>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" name="bankSortCodeB" type="text" value="{{$driver->bankSortCodeB}}" />
                  <p class="error">Sort Code is required</p>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" name="bankSortCodeC" type="text" value="{{$driver->bankSortCodeC}}" />
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
                  <input class="form-control" name="bankAccountNumber" type="text" value="{{$driver->bankAccountNumber}}" />
                  <p class="error">Account Number is required</p>
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
<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/pages/drivers/edit.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
