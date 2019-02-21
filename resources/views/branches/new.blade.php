@extends('layouts.template')
@section('title','New Branch')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Branch</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('branches.home')}}" class="btn btn-warning">
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
      <form action="{{route('branches.save')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-2'>
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
                  <p class="error">Alias is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Address</label>
                  <textarea class="form-control" name="address"></textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Phone Number</label>
                  <input class="form-control" name="phone" type="text" />
                  <p class="error">Phone Number is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Mobile Number</label>
                  <input class="form-control" name="mobile" type="text" />
                  <p class="error">Mobile Number is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Email Address</label>
                  <input class="form-control" name="email" type="text" />
                  <p class="error">Email Address is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="type">
                    <option></option>
                    <option value="1">HQ</option>
                    <option value="2">Administrator</option>
                    <option value="3">Branch</option>
                    <option value="4">Satellite Center</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Person In Charge</label>
                  <input class="form-control" name="personInCharge" type="text" />
                  <p class="error">Person In Charge is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">OutBound Mail Sender Conf</label>
                  <input class="form-control" name="mail_sender" type="text" />
                  <p class="error">OutBound Mail Sender is required</p>
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
<script src="{{asset('public/js/pages/branches/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
