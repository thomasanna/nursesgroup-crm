@extends('layouts.template')
@section('title','Completed Trips')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Completed Trips</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="javascript:void(0)" id="previewSMS" class="btn btn-success @if(!session()->has('checkTransport')||(count(session()->get('checkTransport'))==0)) hidden @endif">
          Preview SMS</a>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('transportation.completed.data')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:3%;">No</th>
              <th style="width:9%;">Driver</th>
              <th style="width:8%;">Staff</th>
              <th style="width:8%;">No of Trips </th>
              <th style="width:5%;">Amount</th>
              <th style="width:8%;">PAYE Week</th>
              <th style="width:5%;">Status</th>
              <th style="width:13%;">Actions</th>
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
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{asset('public/js/pages/transportation/completed/list.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
@endpush
