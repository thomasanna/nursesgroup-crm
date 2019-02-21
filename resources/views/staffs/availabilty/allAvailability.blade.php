@extends('layouts.template')
@section('title','Active Staff')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Staff Availabilty</h1>
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
<div class="box box-default filter">
  <div class="box-body">
  <div class="row bgDarkBlue">
      <div class="col-sm-2">
        <div class="form-group">
            <label for="date"> Category</label>
            <select class="form-control" id="searchCategory">
              <option value=""></option>
              @foreach($categories as $category)
              <option value="{{$category->categoryId}}">{{$category->name}}</option>
              @endforeach
            </select>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
            <label for="date">Staff</label>
            <select class="form-control" id="searchStaff">
              <option value=""></option>
              @foreach($staffs as $staff)
              <option value="{{$staff->staffId}}">{{$staff->forname}} {{$staff->surname}}</option>
              @endforeach
            </select>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
            <label for="date">Branch</label>
            <select class="form-control" id="searchBranch">
              <option value=""></option>
              @foreach($branches as $branch)
              <option value="{{$branch->branchId}}">{{$branch->name}}</option>
              @endforeach
            </select>
        </div>
      </div>
        <div class="col-sm-1 pull-right">
          <div class="form-group">
              <a href="javascript:void(0)" class="btn btn-warning" style="margin-top:25px;" id="searchReset">Reset</a>
          </div>
        </div>
    </div>
  </div>
</div>
<!-- Filter Area -->
<!-- Main row -->
<div class="row staffAll">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('staffs.available.data.all')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
            <th style="width:25%;">Name</th>
              <th style="width:20%;">Mobile</th>
              <th style="width:10%;">Category</th>
              <th style="width:15%;">Branch</th>
              <th style="width:15%;">SMS</th>
              <th style="width:25%;">Days Availability</th>
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


<!-- Modal -->
<div id="SendSmsModal" class="modal fade" role="dialog" action="{{route('staffs.send.sms')}}"  token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Sms</h4>
      </div>
      <div class="modal-body">

        <input type="hidden" name="staffId" id="staffId">

        <div class="row">
          <div class='col-sm-12'>
            <div class='form-group'>
                <label for="smsMessage">Message</label>
                <textarea type="text" id="smsMessage" class="form-control"></textarea>
            </div>
          </div>
        </div>

        </div>
      <div class="modal-footer">
        <button type="button" id="sendStaffSms" class="btn btn-success ">Send</button>
      </div>
    </div>

  </div>
</div>
@include('bookings.partials.search.staffLogBook')
@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/select2.full.min.js')}}"></script>
<script src="{{asset('public/js/pages/staffs/availability/all-avail.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/css/pages/staffs/index.css') }}">
@endpush
