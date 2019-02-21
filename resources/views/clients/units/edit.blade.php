@extends('layouts.template')
@section('title','Edit Unit')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Unit <strong>#{{$unit->name}}</strong> </h4>
    </div>
    <div class="pull-right">
      <a href="{{route('client_units.home')}}" class="btn btn-warning">
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
      <form action="{{route('client_units.update')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Name</label>
                  <input class="form-control" name="name" type="text" value="{{$unit->name}}" />
                  <p class="error">Name is required</p>
                  <input name="pkId" type="hidden" value="{{encrypt($unit->clientUnitId)}}" />
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Alias</label>
                  <input class="form-control" name="alias" type="text" value="{{$unit->alias}}" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="type">
                    <option value=""></option>
                    <option value="1" @if($unit->type==1) selected="selected" @endif>Nursing Home</option>
                    <option value="2" @if($unit->type==2) selected="selected" @endif>Care Home</option>
                    <option value="3" @if($unit->type==3) selected="selected" @endif>Residential</option>
                    <option value="4" @if($unit->type==4) selected="selected" @endif>Dialysis</option>
                    <option value="5" @if($unit->type==5) selected="selected" @endif>NHS</option>
                    <option value="6" @if($unit->type==6) selected="selected" @endif>Private</option>
                    <option value="7" @if($unit->type==7) selected="selected" @endif>Others</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Client</label>
                  <select class="form-control select2" name="clientId">
                    <option value=""></option>
                    @foreach($clients as $client)
                    <option value="{{$client->clientId}}"
                      @if($unit->clientId==$client->clientId) selected="selected" @endif>{{$client->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Client is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Branch</label>
                  <select class="form-control select2" name="branchId"
                  token="{{ csrf_token() }}"
                  zones="{{route('client_units.get.units.by.branch')}}">
                    <option value=""></option>
                    @foreach($branches as $branch)
                    <option value="{{$branch->branchId}}"
                      @if($unit->branchId==$branch->branchId) selected="selected" @endif>{{$branch->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Branch is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Business Address</label>
                  <textarea class="form-control" name="businessAddress" type="text" />{{$unit->businessAddress}}</textarea>
                  <p class="error">Business Address is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="mobile">Post Code</label>
                  <input class="form-control" name="postCode" type="text" value="{{$unit->postCode}}" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
              <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="username">User Name</label>
                   <input class="form-control" name="username" type="text" value="{{$unit->username}}"/>
                  <p class="error">Username is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="password">Password</label>
                  <input class="form-control" name="password" type="text" />
                  <p class="error">Password is required</p>
                </div>
              </div>
          </div>
          <hr>
          <div class="row">
            <div class='col-sm-8'>
              <div class='form-group'>
                  <label for="date">Staff Catchment Zones </label>
                  <select class="form-control select2 multiple" multiple="multiple" name="zoneId[]">
                    @foreach($zones as $zone)
                    <option value="{{$zone->id}}" @if(in_array($zone->id,$unitZones)) selected="selected" @endif>{{$zone->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Zone is required</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Fax</label>
                  <input class="form-control" name="fax" type="text" value="{{$unit->fax}}" />
                  <p class="error">Fax is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Website</label>
                  <input class="form-control" name="website" type="text" value="{{$unit->website}}" />
                  <p class="error">Website is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Address</label>
                  <textarea class="form-control" name="address" type="text">{{$unit->address}}</textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Name of Manager</label>
                  <input class="form-control" name="nameOfManager" type="text" value="{{$unit->nameOfManager}}" />
                  <p class="error">TA is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" name="email" type="text" value="{{$unit->email}}" />
                  <p class="error">Email is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Local Authority / Social Services</label>
                  <input class="form-control" name="localAuthoritySocialServices" type="text" value="{{$unit->localAuthoritySocialServices}}" />
                  <p class="error">Local Authority / Social Services is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Name of Deputy Manager</label>
                  <input class="form-control" name="nameOfDeputyManager" type="text" value="{{$unit->nameOfDeputyManager}}" />
                  <p class="error">Name of Deputy Manager is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Name of Rota/HR Administrator</label>
                  <input class="form-control" name="nameOfRotaHRAdministrator" type="text" value="{{$unit->nameOfRotaHRAdministrator}}" />
                  <p class="error">Name of Rota/HR Administrator is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Residence Capacity</label>
                  <input class="form-control" name="residenceCapacity" type="text" value="{{$unit->residenceCapacity}}" />
                  <p class="error">Residence Capacity is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Agency Usage Level - HCA</label>
                  <select class="form-control select2" name="agencyUsageLevelHCA">
                    <option value=""></option>
                    <option value="1" @if($unit->agencyUsageLevelHCA==1) selected="selected" @endif>Low</option>
                    <option value="2" @if($unit->agencyUsageLevelHCA==2) selected="selected" @endif>Medium</option>
                    <option value="3" @if($unit->agencyUsageLevelHCA==3) selected="selected" @endif>High</option>
                  </select>
                  <p class="error">Agency Usage Level - HCA is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Agency Usage Level - RGN</label>
                  <select class="form-control select2" name="agencyUsageLevelRGN">
                    <option value=""></option>
                    <option value="1" @if($unit->agencyUsageLevelRGN==1) selected="selected" @endif>Low</option>
                    <option value="2" @if($unit->agencyUsageLevelRGN==2) selected="selected" @endif>Medium</option>
                    <option value="3" @if($unit->agencyUsageLevelRGN==3) selected="selected" @endif>High</option>
                  </select>
                  <p class="error">Agency Usage Level - RGN is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Agency Usage Level - Others</label>
                  <select class="form-control select2" name="agencyUsageLevelOthers">
                    <option value=""></option>
                    <option value="1" @if($unit->agencyUsageLevelOthers==1) selected="selected" @endif>Low</option>
                    <option value="2" @if($unit->agencyUsageLevelOthers==2) selected="selected" @endif>Medium</option>
                    <option value="3" @if($unit->agencyUsageLevelOthers==3) selected="selected" @endif>High</option>
                  </select>
                  <p class="error">Agency Usage Level - Others is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Invoice Frequency</label>
                  <select class="form-control select2" name="invoiceFrequency">
                    <option value=""></option>
                    <option value="1" @if($unit->invoiceFrequency==1) selected="selected" @endif>Weekly</option>
                    <option value="2" @if($unit->invoiceFrequency==2) selected="selected" @endif>Monthly</option>
                  </select>
                  <p class="error">Invoice Frequency is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Payment term Agreed</label>
                  <input class="form-control" name="paymentTermAgreed" type="text" value="{{$unit->paymentTermAgreed}}" />
                  <p class="error">Payment term Agreed is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Latest CQC Report</label>
                  <input class="form-control" name="latestCQCReport" type="text" value="{{$unit->latestCQCReport}}" />
                  <p class="error">Latest CQC Report is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <label for="">Status</label>
              <div class='form-group'>
                  <label for="active">Active</label>
                  <input type="radio" id="active" name="status" @if($unit->status==1) checked="checked" @endif value="1">
                  <label for="inactive">Inactive</label>
                  <input type="radio" id="inactive" name="status" @if($unit->status==0) checked="checked" @endif value="0">
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
<script src="{{asset('public/js/pages/clients/units/edit.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
