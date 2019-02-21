@extends('layouts.template')
@section('title','Terminated Applicants')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Terminated Applicants</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
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
              fetch="{{route('applicants.data.terminated')}}"
              token="{{ csrf_token() }}">
          <thead>
            <tr>
              <th>No</th>
              <th>Forname</th>
              <th>Surname</th>
              <th>Category</th>
              <th>Mobile</th>
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
<script src="{{asset('public/js/pages/applicants/terminated/index.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/pages/applicants/index.css') }}">
@endpush
