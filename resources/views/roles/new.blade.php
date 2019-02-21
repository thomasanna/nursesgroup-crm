@extends('layouts.template')
@section('title','New Role')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Role</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('roles.home')}}" class="btn btn-warning">
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
      <form action="{{route('roles.save')}}" method="post" >
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
          </div>
          <div>
            <div class="row">
               <div class='col-sm-12'>
              <div class='form-group'>
                  <label for="date" >Permissions</label>
                  <div class="permsHldr">
                    @foreach($permissions as $permission)
                    <div class="itemRow">
                      <div class="perGrpName"><dt class="col-sm-1">{{ucfirst($permission['name'])}}</dt></div>
                      <div class="w80 prmsName">
                        <ul  class="list-inline">  
                          @foreach($permission['items'] as $item)
                          <li  class="list-inline-item ">

                            <div class="labelItm">{{ucfirst($item->permission_name)}}</div> 
                            <div class="prmItmName" > <div class='col-sm-4'><input type="checkbox" name="permissions_name[]" class="styled styled-primary" value="{{$item->id}}"></div></div>
                          </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                    @endforeach
                  </div>
              </div>
            </div>
          </div>
          </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="submit" class="btn btn-primary" value="Save"/>
          <!-- <button>Save</button> -->
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
