@extends('layouts.template')
@section('title','Transportation - Current Trips')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Current Trips</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
       <div class="pull-right">
          <a href="{{route('booking.current')}}" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
         Back</a>
      </div>




        <a href="javascript:void(0)" id="previewSMS" class="btn btn-success @if(!session()->has('checkTransport')||(count(session()->get('checkTransport'))==0)) hidden @endif">
          Preview SMS</a>
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
              fetch="{{route('transportation.current.data')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th style="width:3%;">Trip ID</th>
              <th style="width:8%;">Date</th>
              <th style="width:8%;">Driver</th>
              <th style="width:5%;">OutBound Trips</th>
              <th style="width:8%;">Staff Name</th>
              <th style="width:5%;">InBound Trips</th>
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

@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{asset('public/js/pages/transportation/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
@endpush
