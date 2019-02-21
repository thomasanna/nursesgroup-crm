@extends('layouts.template')
@section('title','Staff Availabilty')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Staff Availabilty "{{$staff->forname}} {{strtolower($staff->surname)}}"</h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <a href="{{ url('/') }}/staff-availabilty" class="btn btn-warning">
          <i class="fa  fa-chevron-left" aria-hidden="true"></i>
          Back</a>
    </div>

  </div>
</div>
  <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <form class="" action="index.html" method="post">
    <section id="staffs">
    </section>
    <section class="content days-list">
        <!-- SELECT2 EXAMPLE -->
        <div class="row seven-cols">
            <div class="row hidden" id="viewControl" data-prev="" data-next="" >
                <div class="col-md-12">
                    <div class="control-btn">
                        <a class="btn btn-primary btn-sm pull-left prevWeek" id="prevView"  title="Prev" >
                            <i class="fa  fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-primary btn-sm pull-right m-r-10 nextWeek" id="nextView" title="Next" >
                            <i class="fa  fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="data-staff ajax-body " id="entryView" 
                    data-token ="{{ csrf_token() }}"
                    data-view-uri ="{{ route('staffs.availabilty.view') }}"
                    data-save-uri ="{{ route('staffs.availabilty.post') }}" >
                </div>
            </div>
        </div>
      <!-- /.row -->

    </section>
    </form>
    <!-- /.content -->
  <!-- /.content-wrapper -->
<!-- ./wrapper -->
<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>

@push('css')
<link rel="stylesheet" href="{{ asset('public/css/icheck_all.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
<style media="screen">
@media (min-width: 1200px) {
     .seven-cols .col-md-1,
     .seven-cols .col-sm-1,
     .seven-cols .col-lg-1 {
     width: 14.285714285714285714285714285714%;
     *width: 14.285714285714285714285714285714%;
     }
     .seven-cols .form-group {text-align: center}
     .seven-cols .box-header h3 {
          text-align: center;
          float: left;
          width: 100%;
          font-size: 13px;
     }
}
#message-error {
     color: red;
     font-style: italic;
     font-weight: bold;
}
.control-btn {
     margin-bottom: 20px;
         float: left;
         width: 100%;
}
/* 14% = 100% (full-width row) divided by 7 */
</style>
@endpush
@endsection
@push('scripts')
<script src="{{ asset('public/js/icheck.min.js') }}"></script>
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}"></script>
<script src="{{ asset('public/js/pages/staffs/availability/index.js') }}"></script>
@endpush
