@extends('layouts.template')
@section('title','New Unit')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Unit</h4>
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
      <form action="{{route('client_units.save')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Name</label>
                  <input class="form-control" name="name" type="text" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Alias</label>
                  <input class="form-control" name="alias" type="text" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="type">
                    <option value=""></option>
                    <option value="1">Nursing Home</option>
                    <option value="2">Care Home</option>
                    <option value="3">Residential</option>
                    <option value="4">Dialysis</option>
                    <option value="5">NHS</option>
                    <option value="6">Private</option>
                    <option value="7">Others</option>
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
                    <option value="{{$client->clientId}}">{{$client->name}}</option>
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
                    <option value="{{$branch->branchId}}">{{$branch->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Branch is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Business Address</label>
                  <textarea class="form-control" name="businessAddress" type="text" /></textarea>
                  <p class="error">Business Address is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="mobile">Post Code</label>
                  <input class="form-control" name="postCode" type="text" />
                  <p class="error">Post Code is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
              <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="username">User Name</label>
                   <input class="form-control" name="username" type="text"/>
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
                  <input class="form-control" name="fax" type="text" />
                  <p class="error">Fax is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Website</label>
                  <input class="form-control" name="website" type="text" />
                  <p class="error">Website is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Address</label>
                  <textarea class="form-control" name="address" type="text"></textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Name of Manager</label>
                  <input class="form-control" name="nameOfManager" type="text" />
                  <p class="error">TA is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Email</label>
                  <input class="form-control" name="email" type="text" />
                  <p class="error">Email is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Local Authority / Social Services</label>
                  <input class="form-control" name="localAuthoritySocialServices" type="text" />
                  <p class="error">Local Authority / Social Services is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Name of Deputy Manager</label>
                  <input class="form-control" name="nameOfDeputyManager" type="text" />
                  <p class="error">Name of Deputy Manager is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Name of Rota/HR Administrator</label>
                  <input class="form-control" name="nameOfRotaHRAdministrator" type="text" />
                  <p class="error">Name of Rota/HR Administrator is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Residence Capacity</label>
                  <input class="form-control" name="residenceCapacity" type="text" />
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
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">High</option>
                  </select>
                  <p class="error">Agency Usage Level - HCA is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Agency Usage Level - RGN</label>
                  <select class="form-control select2" name="agencyUsageLevelRGN">
                    <option value=""></option>
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">High</option>
                  </select>
                  <p class="error">Agency Usage Level - RGN is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Agency Usage Level - Others</label>
                  <select class="form-control select2" name="agencyUsageLevelOthers">
                    <option value=""></option>
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">High</option>
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
                    <option value="1">Weekly</option>
                    <option value="2">Monthly</option>
                  </select>
                  <p class="error">Invoice Frequency is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Payment term Agreed</label>
                  <input class="form-control" name="paymentTermAgreed" type="text" />
                  <p class="error">Payment term Agreed is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Latest CQC Report</label>
                  <input class="form-control" name="latestCQCReport" type="text" />
                  <p class="error">Latest CQC Report is required</p>
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
<script src="{{asset('public/js/pages/clients/units/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
