@extends('layouts.template')
@section('title','Staff Notes')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Staff Notes - <strong>#{{$staff->forname}} {{$staff->surname}}</strong></h4>
    </div>
    <div class="pull-right">
      <a href="{{route('staffs.home.all')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>

<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
        <div class="box-body">
          <div class='row'>
            <div class="col-md-10">
              <div class="col-md-3 h20">Name : <strong>{{$staff->forname}} {{$staff->surname}}</strong></div> 
              <div class="col-md-3 h20">Category : <strong>{{$staff->category->name}}</strong></div> 
              <div class="col-md-3 h20">Age : <strong>{{(date('Y') - date('Y',strtotime($staff->dateOfBirth)))}}</strong></div> 
              <div class="col-md-3 h20">Gender : <strong>@if($staff->gender==1) Male @else Female @endif</strong></div> 
            </div>
            <div class="col-md-2 imgHdr">
              <img src="{{asset('storage/app/staff/photo/'.$staff->photo)}}" alt="{{$staff->forname}} {{$staff->surname}}">
            </div>
            <div class="col-md-10 m-t-95 m-l-15">
              {!! $staff->quickNotes !!}
            </div>
            
          </div>
        </div>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/branches/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
