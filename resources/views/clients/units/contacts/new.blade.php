@extends('layouts.template')
@section('title','New Unit Contact')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Unit Contact</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('client_unit_contact.home',encrypt($unit->clientUnitId))}}" class="btn btn-warning">
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
      <form action="{{route('client_unit_contact.save')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Full Name</label>
                  <input class="form-control" name="fullName" type="text" />
                  <input type="hidden" name="clientUnitId" value="{{encrypt($unit->clientUnitId)}}">
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Position</label>
                  <input class="form-control" name="position" type="text" />
                  <p class="error">Position is required</p>
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
                  <label for="date">Email</label>
                  <input class="form-control" name="email" type="text" />
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Mobile</label>
                  <input class="form-control" name="mobile" type="text" />
                  <p class="error">Mobile is required</p>
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
