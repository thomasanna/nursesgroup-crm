@extends('layouts.template')
@section('title','Units')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Units</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="{{route('client_units.new')}}" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New Unit</a>
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
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('client_units.data')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:4%;">No</th>
              <th style="width:20%;">Name</th>
              <th style="width:10%;">Client</th>
              <th style="width:15%;">Name Of Manager</th>
              <th style="width:10%;">Phone</th>
              <th style="width:5%;">Status</th>
              <th style="width:5%;">Unit Log</th>
              <th style="width:20%;">Actions</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

@include('clients.units.unitLog')

@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/pages/clients/units/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/pages/clients/units/index.css') }}">
@endpush
