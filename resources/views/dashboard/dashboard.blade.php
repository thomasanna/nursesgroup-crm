@extends('layouts.template')
@section('title','Dashboard')
@section('page_title','Dashboard')
@section('content')
@if(session()->has('message'))
<div class="row" style="margin-bottom: 20px;"><span class="alert-msg">{{session()->get('message')}}</span></div>
@endif
<div class="row m-b-10">
   <!-- ./col -->
   <div class="col m6 s12 l3">
      <div class="card card-hover">
         <div class="p-10">
            <div class="d-flex no-block align-items-center">
               <div class="m-r-10 
                  @if($unfilled > 3) red-text 
                  @elseif($unfilled <= 3 && $unfilled > 0 ) orange-text 
                  @else green-text
                  @endif
                  text-accent-4">
                  <i class="fa fa fa-bar-chart material-icons display-5"></i>
               </div>
               <div>
                  <span>Bookings - Today </span>
                  <h4 class="font-medium m-b-0">{{$unfilled}}</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col m6 s12 l3">
      <div class="card card-hover">
         <div class="p-10">
            <div class="d-flex no-block align-items-center">
               <div class="m-r-10 blue-text text-accent-4">
                  <i class="fa fa-h-square material-icons display-5"></i>
               </div>
               <div>
                  <span>Units</span>
                  <h4 class="font-medium m-b-0">{{$units}}</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col m6 s12 l3">
      <div class="card card-hover">
         <div class="p-10">
            <div class="d-flex no-block align-items-center">
               <div class="m-r-10 blue-text text-accent-4">
                  <i class="fa fa-id-card material-icons display-5"></i>
               </div>
               <div>
                  <span>Active Applicants</span>
                  <h4 class="font-medium m-b-0">{{$activeApplicants}}</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col m6 s12 l3">
      <div class="card card-hover">
         <div class="p-10">
            <div class="d-flex no-block align-items-center">
               <div class="m-r-10 blue-text text-accent-4">
                  <i class="fa fa-trash termIcn material-icons display-5"></i>
               </div>
               <div>
                  <span>Terminated Applicants</span>
                  <h4 class="font-medium m-b-0">{{$terminatedApplicants}}</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col m6 s12 l3">
      <div class="card card-hover">
         <div class="p-10">
            <div class="d-flex no-block align-items-center">
               <div class="m-r-10 blue-text text-accent-4">
                  <i class="fa fa-user-md material-icons display-5"></i>
               </div>
               <div>
                  <span>Active Staffs</span>
                  <h4 class="font-medium m-b-0">{{$activeStaff}}</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col m6 s12 l3">
      <div class="card card-hover">
         <div class="p-10">
            <div class="d-flex no-block align-items-center">
               <div class="m-r-10 blue-text text-accent-4">
                  <i class="fa fa-ban material-icons display-5"></i>
               </div>
               <div>
                  <span>Inactive Staffs</span>
                  <h4 class="font-medium m-b-0">{{$inActiveStaff}}</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- /.row -->
<!-- Main row -->
<div class="row">
   @if(auth()->guard('admin')->user()->type==1)
   <section class="col-lg-6">
      <div class="row">
         <div class="col-sm-12 col-md-6">
            <div class="card card-hover">
               <div class="card-body">
                  <h5 class="card-title">HR</h5>
                  <h4 class="font-bold mt-3 mb-2">3,25,346</h4>
                  <h6 class="mb-0 card-subtitle">Total Earnings of the Month</h6>
               </div>
               <div class="text-center"></div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6">
            <div class="card card-hover">
               <div class="card-body">
                  <h5 class="card-title">Units</h5>
                  <h4 class="font-bold mt-3 mb-2">35,658</h4>
                  <h5 class="card-subtitle mb-0">Total Earnings of the Month</h5>
               </div>
               <div class="earningsbox"></div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6">
            <div class="card card-hover">
               <div class="card-body">
                  <h5 class="card-title">Bookings</h5>
                  <h4 class="font-bold mt-3 mb-2">3,25,346</h4>
                  <h6 class="mb-0 card-subtitle">Total Earnings of the Month</h6>
               </div>
               <div class="text-center"></div>
            </div>
         </div>
         <div class="col-sm-12 col-md-6">
            <div class="card card-hover">
               <div class="card-body">
                  <h5 class="card-title">Payment</h5>
                  <h4 class="font-bold mt-3 mb-2">35,658</h4>
                  <h5 class="card-subtitle mb-0">Total Earnings of the Month</h5>
               </div>
               <div class="earningsbox"></div>
            </div>
         </div>
         <div class="col-sm-12">
            <div class="order-widget card m-0">
               <div class="card-body">
                  <div class="row">
                     <div class="col-sm-12 col-md-8">
                        <h5 class="card-title">Order Status</h5>
                        <h6 class="mb-0 card-subtitle">Total Earnings of the Month</h6>
                        <div class="mt-3 row flex">
                           <div class="border-right col-4">
                              <i class="fa fa-circle text-cyan"></i>
                              <h3 class="mb-0 font-medium">5489</h3>
                              <span>Success</span>
                           </div>
                           <div class="border-right col-4">
                              <i class="fa fa-circle text-orange"></i>
                              <h3 class="mb-0 font-medium">954</h3>
                              <span>Pending</span>
                           </div>
                           <div class="col-4">
                              <i class="fa fa-circle text-info"></i>
                              <h3 class="mb-0 font-medium">736</h3>
                              <span>Failed</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-4">
                        <div id="visitor" class="mt-3"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   @endif
   <!-- /.Left col -->
   <!-- right col (We are only adding the ID to make the widgets sortable)-->
   <section class="@if(auth()->guard('admin')->user()->type==1) col-lg-6 @else col-lg-12 @endif" id="monthReportLine" action="{{route('graph.monthly.bookings')}}" token="{{ csrf_token() }}"></section>
   <!-- right col -->
</div>
@endsection
@push('scripts')
<script src="{{ asset('public/js/highcharts.js') }}?{{time()}}"></script>
<script src="{{ asset('public/js/pages/dashboard/dashboard.js') }}?{{time()}}"></script>
@endpush