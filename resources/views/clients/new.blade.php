@extends('layouts.template')
@section('title','New Client')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Client</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('clients.home')}}" class="btn btn-warning">
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
      <form action="{{route('clients.save')}}" method="post" >
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
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Company Number</label>
                  <input class="form-control" name="companyNumber" type="text" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Registered Address</label>
                  <textarea class="form-control" name="registeredAddress" type="text" /></textarea>
                  <p class="error">Alternative Phone Number is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">LandLine Number</label>
                  <input class="form-control" name="landlineNumber" type="text" />
                  <p class="error">Alternative Phone Number is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Alternative Phone Number</label>
                  <input class="form-control" name="altPhoneNumber" type="text" />
                  <p class="error">Alternative Phone Number is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Business Address</label>
                  <textarea class="form-control" name="businessAddress" type="text" /></textarea>
                  <p class="error">Alternative Phone Number is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="typeOfCompany">
                    <option value=""></option>
                    <option value="1">Private</option>
                    <option value="2">Limited</option>
                    <option value="3">LLP</option>
                    <option value="4">Self</option>
                    <option value="5">Others</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Mobile Number</label>
                  <input class="form-control" name="mobileNumber" type="text" />
                  <p class="error">Address is required</p>
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
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="mobile">Person in Charge</label>
                  <input class="form-control" name="personInCharge" type="text" />
                  <p class="error">Person in Charge is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Number Of Branches</label>
                  <input class="form-control" name="numberOfBranches" type="text" />
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Fax</label>
                  <input class="form-control" name="fax" type="text" />
                  <p class="error">Fax is required</p>
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
<script src="{{asset('public/js/pages/clients/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
