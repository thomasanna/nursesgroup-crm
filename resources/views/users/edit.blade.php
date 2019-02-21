@extends('layouts.template')
@section('title','Edit User')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit User -<strong>{{$user->name}}</strong> </h4>
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
      <form action="{{route('users.update')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Name</label>
                  <input class="form-control" name="name" value="{{$user->name}}" type="text" />
                  <p class="error">Name is required</p>
                  <input type="hidden" name="pkId" value="{{encrypt($user->adminId)}}">
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">User Name</label>
                  <input class="form-control" name="alias" type="text" value="{{$user->username}}" />
                  <p class="error">User Name is required</p>
              </div>
            </div>
            
          </div>
            <div class='row'>
            
            <div class='col-sm-3'>
               <div class='form-group'>
                  <label for="date">Email Address</label>
                  <input class="form-control" name="email" value="{{$user->email}}" type="text" />
                  <p class="error">Email Address is required</p>
                  <p class="error">Please enter a valid email address</p>
              </div>
            </div>
         
          
            
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="type">
                    <option></option>
                    <option @if($user->type==1) selected="selected" @endif value="1">Admin</option>
                    <option @if($user->type==2) selected="selected" @endif value="2">Accountant</option>
                    <option @if($user->type==3) selected="selected" @endif value="3">HR</option>
                    <option @if($user->type==4) selected="selected" @endif value="4">Pay Roll</option>
                    <option @if($user->type==4) selected="selected" @endif value="4">Client Admin</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
            <label for="">Status</label>
              <div class='form-group'>
                  <label for="active">Active</label>
                  <input type="radio" id="active" name="status" @if($user->status==1) checked="checked" @endif value="1">
                  <label for="inactive">Inactive</label>
                  <input type="radio" id="inactive" name="status" @if($user->status==0) checked="checked" @endif value="0">
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-8'>
              <div class='form-group'>
                  <label for="date">Assign Roles </label>
                  <select class="form-control select2 multiple" multiple="multiple" name="roles[]">
                   @foreach($roles as $role)
                    <option value="{{$role->id}}" @if($user->roles->contains('id',$role->id)) selected="selected" @endif> {{$role->name}}</option>
                    @endForeach
                   </select>
                  <p class="error">Role is required</p>
              </div>
            </div>
          </div>
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
<script src="{{asset('public/js/pages/users/edit.js')}}"></script>

<!-- <script src="{{asset('public/js/pages/branches/new.js')}}"></script> -->
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
