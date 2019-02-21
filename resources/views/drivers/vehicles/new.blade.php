@extends('layouts.template')
@section('title','New Vehicle')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Vehicle</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('driver.vehicles.home',encrypt($driver->driverId))}}" class="btn btn-warning">
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
<div class="row col-md-8">
  <ul class="smallHdr">
    <li>{{$driver->forname}} {{$driver->surname}}</li>
    <li>{{$driver->email}}</li>
    <li>{{$driver->mobile}}</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('driver.vehicles.save')}}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="driverId" value="{{encrypt($driver->driverId)}}">
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Make</label>
                  <input class="form-control" name="make" type="text" />
                  <p class="error">Make is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Model</label>
                  <input class="form-control" name="model" type="text" autocomplete="off" />
                  <p class="error">Model is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Reg Number</label>
                  <input class="form-control" name="regNumber" type="text" autocomplete="off" />
                  <p class="error">Reg Number is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Color</label>
                    <input class="form-control" name="color" type="text" autocomplete="off" />
                  <p class="error">Color is required</p>
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
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/pages/applicants/trainings/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
