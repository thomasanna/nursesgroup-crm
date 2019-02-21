@extends('layouts.template')
@section('title','Users')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Users</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="{{route('users.new')}}" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New User</a>
    </div>

  </div>
</div>
<!-- Header Navigation -->
<!-- Main row -->
<div class="row">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('users.data')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:4%;">No</th>
              <th style="width:10%;">Name</th>
              <th style="width:12%;">Email Address</th>
              <th style="width:6%;">Type</th>
              <th style="width:6%;">Status</th>
              <th style="width:8%;">Log</th>
               <th style="width:10%;">Created at</th>
              <th style="width:10%;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
@include('users.userLog')
@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/pages/users/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/pages/branches/index.css') }}">
@endpush
