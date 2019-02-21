@extends('layouts.template')
@section('title','Applicant Trainings')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Applicant Trainings</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="{{route('applicants.home')}}" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
          Back</a>
        <a href="{{route('applicant.training.new',encrypt($applicant->applicantId))}}" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New Training</a>
    </div>
    <div class="pull-right m-r-10 w13">
      <select class="form-control select2"
      name="switchProgress"
      action="{{route('applicants.change.applicant.progress')}}"
      token="{{ csrf_token() }}"
      applicant="{{encrypt($applicant->applicantId)}}">
        <option value="1" @if($applicant->trainingProgress==1) selected="selected" @endif>To Be Started</option>
        <option value="2" @if($applicant->trainingProgress==2) selected="selected" @endif>In Progress</option>
        <option value="3" @if($applicant->trainingProgress==3) selected="selected" @endif>Completed</option>
      </select>
    </div>
    <div class="pull-right m-r-10 m-t-7">
      <label for="">Progress</label>
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
<div class='row col-md-8'>
  <ul class="smallHdr">
    <li>{{$applicant->forname}} {{$applicant->surname}}</li>
    <li>{{$applicant->category->name}}</li>
    <li>{{$applicant->email}}</li>
    <li>{{$applicant->mobile}}</li>
  </ul>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">

      <div class="box-body table-responsive no-padding trainings">
        <table class="table table-striped table-bordered table-hover"
              fetch="{{route('applicant.training.data')}}"
              token="{{ csrf_token() }}"
              applicant="{{encrypt($applicant->applicantId)}}">
          <thead>
            <tr>
              <th>No</th>
              <th>Course</th>
              <th>Provider</th>
              <th>Expiry Date</th>
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
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/pages/applicants/trainings/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/pages/applicants/index.css') }}">
@endpush
