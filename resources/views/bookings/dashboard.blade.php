@extends('layouts.template')
@section('title','Dashboard')
@section('page_title','Dashboard')
@section('content')
<div class="row">
  <!-- ./col -->
    <div class="col-md-3">
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Bookings - Confirmed (Today)</span>
          <span class="info-box-number">{{$todayConfirmed}}</span>
          <div class="progress">
            <div class="progress-bar" style="width: 50%"></div>
          </div>
          <span class="progress-description">50% Increase in 30 Days</span>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Bookings (This Week)</span>
          <span class="info-box-number">{{'23'}}</span>
          <div class="progress">
            <div class="progress-bar" style="width: 50%"></div>
          </div>
          <span class="progress-description">50% Increase in 30 Days</span>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="info-box bg-red">
        <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Bookings (This Month)</span>
          <span class="info-box-number">{{'23'}}</span>
          <div class="progress">
            <div class="progress-bar" style="width: 50%"></div>
          </div>
          <span class="progress-description">50% Increase in 30 Days</span>
        </div>
      </div>
    </div>
</div>

<div class="row">
  <!-- ./col -->
  <!-- ./col -->
  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{'23'}}</h3>
        <p>Cancelled (This Week)</p>
      </div>
      <div class="icon">
        <i class="fa fa-book"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{'23'}}</h3>
        <p>Cancelled (This Month)</p>
      </div>
      <div class="icon">
        <i class="fa fa-book"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{'87'}}</h3>
        <p>Uncovered (This Week)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{'45'}}</h3>
        <p>Uncovered (This Month)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{'45'}}</h3>
        <p>Booking Error (This Week)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{'45'}}</h3>
        <p>Booking Error (This Month)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- ./col -->
  <!-- ./col -->
  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{'12'}}</h3>
        <p>Unfilled RGN ( 7 Days)</p>
      </div>
      <div class="icon">
        <i class="fa fa-book"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{'32'}}</h3>
        <p>Unfilled RGN ( 30 Days)</p>
      </div>
      <div class="icon">
        <i class="fa fa-book"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{'08'}}</h3>
        <p>Unfilled HCA ( 7 Days)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{'45'}}</h3>
        <p>Unfilled HCA ( 30 Day)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{'12'}}</h3>
        <p>Unfilled SHCA (7 Days)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-2 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{'02'}}</h3>
        <p>Unfilled SHCA ( 30 Days)</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-md"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- ./col -->
    <div class="col-md-4">
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Average Time To Fill RGN (This Month)</span>
          <span class="info-box-number">{{'8.3 Min'}}</span>
          <div class="progress">
            <div class="progress-bar" style="width: 50%"></div>
          </div>
          <span class="progress-description">50% Increase in 30 Days</span>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Average Time To Fill HCA (This Month)</span>
          <span class="info-box-number">{{'3.2 Min'}}</span>
          <div class="progress">
            <div class="progress-bar" style="width: 50%"></div>
          </div>
          <span class="progress-description">50% Increase in 30 Days</span>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-box bg-red">
        <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Average Time To Fill SHCA (This Month)</span>
          <span class="info-box-number">{{'10.4 Min'}}</span>
          <div class="progress">
            <div class="progress-bar" style="width: 50%"></div>
          </div>
          <span class="progress-description">50% Increase in 30 Days</span>
        </div>
      </div>
    </div>
</div>
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
