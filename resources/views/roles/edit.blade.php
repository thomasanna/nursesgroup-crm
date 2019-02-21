@extends('layouts.template')
@section('title','Edit Role')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
   <div class="box-body">
      <div class="pull-left">
         <h4 class="box-title">Edit Role -<strong>{{$role->name}}</strong> </h4>
      </div>
      <div class="pull-right">
         <a href="{{route('roles.home')}}" class="btn btn-warning">
         <i class="fa  fa-chevron-left" aria-hidden="true"></i>
         Back</a>
      </div>
   </div>
</div>
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
         <form action="{{route('roles.update')}}" method="post" >
            {!! csrf_field() !!}
            <div class="box-body">
               <div class='row'>
                  <div class='col-sm-3'>
            <div class='form-group'>
               <label for="name">Name</label>
               <input class="form-control" name="name" value="{{$role->name}}" type="text" />
               <p class="error">Name is required</p>
               <input type="hidden" name="pkId" value="{{encrypt($role->id)}}">
            </div>
         </div>
         
      </div>
   
</form>

   <div class='col-sm-12'>
      <div class='form-group'>
         <label for="date">Permissions</label><br><br>
         <div class="permsHldr">
                    @foreach($permissions as $permission)
                    <div class="itemRow">
                      <div class="perGrpName"><dt class="col-sm-1">{{ucfirst($permission['name'])}}</dt></div>
                      <div class="w80 prmsName">
                        <ul  class="list-inline">  
                          @foreach($permission['items'] as $item)
                          <li  class="list-inline-item ">
                            <div class="labelItm">{{ucfirst($item->permission_name)}}</div> 
                            <div class="prmItmName" > <div class='col-sm-4'><input type="checkbox" name="permissions_name[]" class="styled styled-primary" value="{{$item->id}}"  @if($role->hasPermissionTo($item->id)) checked="checked" @endif></div></div>
                          </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                    @endforeach
                  </div>
               </div>

      <div class="box-footer">
         <input type="submit" class="btn btn-primary" value="Save"/>
      </div>
      </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/roles/edit.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush