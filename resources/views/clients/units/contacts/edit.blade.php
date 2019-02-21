@extends('layouts.template')
@section('title','Edit Unit')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Contact <strong>#{{$unitContact->fullName}} - {{$unitContact->position}}</strong> </h4>
    </div>
    <div class="pull-right">
      <a href="{{route('client_unit_contact.home',encrypt($unitContact->unit->clientUnitId))}}" class="btn btn-warning">
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
      <form action="{{route('client_unit_contact.update')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Full Name</label>
                  <input class="form-control" name="fullName" type="text" value="{{$unitContact->fullName}}" />
                  <input type="hidden" name="clientUnitContactId" value="{{encrypt($unitContact->clientUnitPhoneId)}}">
                  <input type="hidden" name="clientUnitId" value="{{encrypt($unitContact->unit->clientUnitId)}}">
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Position</label>
                  <input class="form-control" name="position" type="text" value="{{$unitContact->position}}" />
                  <p class="error">Position is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Phone Number</label>
                  <input class="form-control" name="phone" type="text" value="{{$unitContact->phone}}" />
                  <p class="error">Phone Number is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Email</label>
                  <input class="form-control" name="email" type="text" value="{{$unitContact->email}}" />
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Mobile</label>
                  <input class="form-control" name="mobile" type="text" value="{{$unitContact->mobile}}" />
                  <p class="error">Mobile is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <label for="">Status</label>
              <div class='form-group'>
                  <label for="active">Active</label>
                  <input type="radio" id="active" name="status" @if($unitContact->status==1) checked="checked" @endif value="1">
                  <label for="inactive">Inactive</label>
                  <input type="radio" id="inactive" name="status" @if($unitContact->status==0) checked="checked" @endif value="0">
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
<script src="{{asset('public/js/pages/clients/units/edit.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
