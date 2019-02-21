@extends('layouts.template')
@section('title','Edit Training')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Training</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('applicant.training.home',encrypt($training->applicantId))}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Filter Area -->
<!-- <div class="box box-default">
  <div class="box-body">

  </div>
</div> -->

<!-- Filter Area -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row col-md-8">
  <ul class="smallHdr">
    <li>{{$applicant->forname}} {{$applicant->surname}}</li>
    <li>{{$applicant->category->name}}</li>
    <li>{{$applicant->email}}</li>
    <li>{{$applicant->mobile}}</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('applicant.training.update')}}" method="post" accept="application/pdf, image/*" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="trainingId" value="{{encrypt($training->applicantTrainingId)}}">
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Course</label>
                  <select class="form-control select2" name="courseId">
                    <option></option>
                    @foreach($courses as $course)
                    <option value="{{$course->trainingCourseId}}"@if($training->courseId==$course->trainingCourseId) selected="selected" @endif>
                      {{$course->courseName}}</option>
                    @endforeach
                  </select>
                  <p class="error">Course is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Provider</label>
                  <input class="form-control" name="provider" type="text" value="{{$training->provider}}" />
                  <p class="error">Provider is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Issue Date</label>
                  <input class="form-control datepicker" name="issueDate" type="text" value="{{date('d-m-Y',strtotime($training->issueDate))}}" autocomplete="off" />
                  <p class="error">Issue Date is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Expiry Date</label>
                  <input class="form-control datepicker" name="expiryDate" type="text" value="{{date('d-m-Y',strtotime($training->expiryDate))}}" autocomplete="off" />
                  <p class="error">Expiry Date is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Status</label>
                  <select class="form-control select2" name="status">
                    <option></option>
                    <option value="1" @if($training->status==1) selected="selected" @endif>Allocated</option>
                    <option value="2" @if($training->status==2) selected="selected" @endif>In Progress</option>
                    <option value="3" @if($training->status==3) selected="selected" @endif>Completed</option>
                  </select>
                  <p class="error">Status is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Comment</label>
                  <textarea class="form-control" name="comment">{{$training->comment}}</textarea>
                  <p class="error">Comment Number is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Certificate</label>
                  <input class="form-control" name="documentFile" type="file" />
                  <p class="error">Certificate is required</p>
              </div>
            </div>
          </div>
          @if($training->documentFile)
          <div class='row'>
            <div class='col-sm-4 pull-right'>
              <div class='form-group'>
                <a href="{{asset('storage/app/applicant/applicant_trainings/'.$training->documentFile)}}" target="_blank" class="btn btn-warning">View Certificate</a>
              </div>
            </div>
          </div>
          @endif
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="submit" class="btn btn-primary" value="Save"/>
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/pages/applicants/trainings/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
