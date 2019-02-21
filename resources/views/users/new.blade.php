@extends('layouts.template')
@section('title','New User')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New User</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('users.home')}}" class="btn btn-warning">
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
      <form action="{{route('users.save')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Name</label>
                  <input class="form-control" name="name" type="text" />
                  <p class="error">Name is required</p>
              </div>
            </div>
            
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">User Name</label>
                   <input class="form-control" name="username" type="text" />
                  <p class="error">Username is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Password</label>
                  <input class="form-control" name="password" type="PASSWORD" />
                  <p class="error">Password is required</p>
                </div>
              </div>

                <!--    <div class='col-sm-3'>
              <div class='form-group'>
                   <div class='form-group'>
                  <label for="date">Secret PIN</label>
                  <input class="form-control" name="secret_pin" type="PASSWORD" />
                  <p class="error">Secret pin is required</p>
              </div>
              </div>
            </div> -->
          </div>
             </div>
          <div class='row'>
            
         <div class="col-md-12">
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Email Address</label>
                  <input class="form-control" name="email" type="text" />
                  <p class="error">Email Address is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="type">
                    <option></option>
                    <option value="1">Admin</option>
                    <option value="2">Accountant</option>
                    <option value="3">HR</option>
                    <option value="4">Pay Roll</option>
                     <option value="4">Client Admin</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>

            <div class='col-sm-8'>
              <div class='form-group'>
                  <label for="date">Assign Roles </label>
                   <select class="form-control select2 multiple" multiple="multiple" name="roles[]">
                   @foreach($roles as $role)
                    <option value="{{$role->id}}"> {{$role->name}}</option>
                    @endForeach
                   </select>
                  <p class="error">Role is required</p>
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
<script src="{{asset('public/js/pages/users/index.js')}}"></script>
<script src="{{asset('public/js/pages/users/edit.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
