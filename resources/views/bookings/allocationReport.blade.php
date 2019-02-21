@extends('layouts.template')
@section('title','Staff Availabilty')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Allocation Report</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <!-- <a href="" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New Staff</a> -->
    </div>

  </div>
</div>
  <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->

    <div class="box">
        <div class="box-body  no-padding">
            <!-- Main content -->
            <section id="staffs" class="m-t-10 m-l-10">
                <div class="row">
                    <div class="col-xs-4">
                        <select class="staffs form-control select2" name="staffs" id="staffs">
                            <option value="">Choose Staff</option>
                            @foreach($staffs as $staff)
                            <option value="{{$staff->staffId}}">{{$staff->forname." ".$staff->surname}}</option>
                            @endforeach
                        </select>
                        <div id="message-error" style="display: block;color:#00f;text-align:right;">Please Choose Staff</div>
                    </div>
                </div>
            </section>
            <section class="m-t-10 table-responsive report-view" id="reportView"
                    data-token ="{{ csrf_token() }}"
                    data-view-uri ="{{ route('booking.allocation.report.view') }}">
            </section>
        </div> 
    </div>
    <!-- /.content -->
  <!-- /.content-wrapper -->
<!-- ./wrapper -->
<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
@endpush
@push('scripts')
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}"></script>
<script src="{{ asset('public/js/pages/bookings/report.js') }}"></script>
@endpush
