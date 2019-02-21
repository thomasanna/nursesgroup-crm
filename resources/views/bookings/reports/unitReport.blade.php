@extends('layouts.template')
@section('title','Unit Report')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Unit Report <strong></strong></h4>
    </div>

    <div class="pull-right">
      <a href="{{route('booking.current')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
    <div class="pull-right m-r-10">
      <a href="{{route('booking.send.unit.report')}}" class="btn btn-success">
        <i class="fa  fa-envelope" aria-hidden="true"></i>
        Email</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">

      <div class="box-body">
        <table class="table">
          <thead>
            <tr>
              <th>#ID</th>
              <th>Unit</th>
              <th>Date</th>
              <th>Shift</th>
              <th>Category</th>
              <th>Shift Status</th>
              <th>Staff</th>
            </tr>
          </thead>
          <tbody action="{{route('booking.unit.report.data')}}" token="{{csrf_token()}}" class="injectHere">
            
          </tbody>
        </table>
      </div>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/pages/bookings/unitReport.js')}}"></script>
@endpush
