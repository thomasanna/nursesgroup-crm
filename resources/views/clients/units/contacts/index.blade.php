@extends('layouts.template')
@section('title','Unit Contacts')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Unit Contacts <strong>#{{$unit->name}}</strong></h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="{{route('client_unit_contact.new',encrypt($unit->clientUnitId))}}" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New Contact</a>
    </div>
    @if(Request::segment(5)==1)
    <div class="pull-right m-r-10">
        <a href="{{route('client_units.staffs.home')}}" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
          Back</a>
    </div>
    @else
    <div class="pull-right m-r-10">
        <a href="{{route('client_units.home')}}" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
          Back</a>
    </div>
    @endif
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
  <div class="col-md-10 col-md-offset-1">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('client_unit_contact.data',encrypt($unit->clientUnitId))}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th>No</th>
              <th>Full Name</th>
              <th>Position</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Status</th>
              <th>Actions</th>
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
<script src="{{asset('public/js/pages/clients/units/contacts/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
@endpush
