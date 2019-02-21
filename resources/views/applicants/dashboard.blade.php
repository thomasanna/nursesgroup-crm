@extends('layouts.template')
@section('title','Dashboard')
@section('page_title','Dashboard')
@section('content')
<div class="row">
  <!-- ./col -->
  <!-- ./col -->
  <!-- ./col -->
  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$activeApplicants}}</h3>
        <p>Active Applicants</p>
      </div>
      <div class="icon">
        <i class="fa fa-book"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{$terminatedApplicants}}</h3>
        <p>Terminated Applicants</p>
      </div>
      <div class="icon">
        <i class="fa fa-book"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>{{$activeStaff}}</h3>
        <p>Active Staffs</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{$inActiveStaff}}</h3>
        <p>Inactive Staffs</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->
<!-- Main row -->
<div class="row">
  <!-- Left col -->
  <section class="col-lg-7 connectedSortable">
  </section>
  <!-- /.Left col -->
  <!-- right col (We are only adding the ID to make the widgets sortable)-->
  <section class="col-lg-5 connectedSortable">
  </section>
  <!-- right col -->
</div>
@endsection
