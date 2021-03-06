@extends('layouts.template')
@section('title','Active Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Active Staff</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <!-- <a href="" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New Staff</a> -->
    </div>

  </div>
</div>
<!-- Header Navigation -->
<!-- Filter Area -->
<!-- <div class="box box-default filter hidden">
  <div class="box-body">
  </div>
</div> -->
<!-- Filter Area -->
<!-- Main row -->
<div class="row">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover staffsActive"
              fetch="{{route('staffs.data.active')}}"
              search="{{$searchKeyword}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:5%;">No</th>
              <th style="width:10%;">Forname</th>
              <th style="width:10%;">Surname</th>
              <th style="width:5%;">Category</th>
              <th style="width:3%;">Email</th>
              <th style="width:5%;">Mobile</th>
              <th style="width:5%;">Branch</th>
              <th style="width:28%;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/pages/staffs/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/pages/staffs/index.css') }}">
@endpush
