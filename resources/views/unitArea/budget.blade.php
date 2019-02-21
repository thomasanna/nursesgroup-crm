@extends('unitArea.layouts.template')
@section('title','Budget')
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-xs-12">
         <div class="box">
            <div class="box-body">
               <div class="row bgDarkBlue">
                  <div class='col-md-3'>
                     <div class='form-group'>
                        <label for="date">Month</label>
                             <select class="form-control monthSel" name="monthSel" action ="{{route('unit.area.budget.filter.month')}}" token="{{ csrf_token() }}">
                                <option value=""></option>
                                @for($i=0;$i< count($prevComing3Months);$i++)
                                <option value="{{$prevComing3Months[$i]}}">{{$prevComing3Months[$i]}}</option>
                                @endfor
                             </select>
                     </div>
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="graph1">
      <div class="col-md-6 bordrNice">
         <div class="row col-md-12">
            <div class="col-md-4">
               <div class="date">{{$monthArray[0]}}</div>
               <div class="m-t">
                  <span class="m-r-20">Number of HCA</span>
                  <span>:</span>
                  <span class="firstHcaCount">{{$countHcaRgnConfirmedCurrentMonth['hcaCount']}}</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20">Number of RGN</span>
                  <span>:</span>
                  <span class="firstRgnCount">{{$countHcaRgnConfirmedCurrentMonth['rgnCount']}}</span>
               </div>
            </div>
            <div class="col-md-8 pull-right">
               <div class="m-t">
                  <span class="m-r-20">Budget Amount</span>
                  <span>:</span>
                  @if($budget[0] == "")
                  <span class="pull-right"><button class="btn btn-sm cstBtn" data-toggle="modal" data-target="#setBudgetModal">Set Budget</button></span>
                  @else
                  <span class="pull-right amt">£ {{$budget[0]}}</span>
                  @endif
               </div>
               <div class="m-t">
                  <span class="m-r-20 p-l-20">Total Amount of HCA</span>
                  <span>:</span>
                  <span class="pull-right amt amt-sm hcaamt">£ 1200.00</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20 p-l-20">Total Amount of RGN</span>
                  <span>:</span>
                  <span class="pull-right amt amt-sm rgnamt">£ 800.00</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20">Total</span>
                  <span>:</span>
                  <span class="pull-right amt totalamt">£ 2000.00</span>
               </div>
            </div>
         </div>
         <div class="col-md-12" id="container" action="{{route('unit.area.graph.current.month.bookings')}}" token="{{ csrf_token() }}" style="min-width: 310px; height: 300px; margin: 0 auto">
         </div>
      </div>
      </div>
      <div class="graph2">
      <div class="col-md-6 bordrNice">
         <div class="row col-md-12">
            <div class="col-md-4">
               <div class="date">{{$monthArray[1]}}</div>
               <div class="m-t">
                  <span class="m-r-20">Number of HCA</span>
                  <span>:</span>
                  <span class="secondHcaCount">{{$countHcaRgnConfirmedNextMonth['hcaCount']}}</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20">Number of RGN</span>
                  <span>:</span>
                  <span class="secondRgnCount">{{$countHcaRgnConfirmedNextMonth['rgnCount']}}</span>
               </div>
            </div>
            <div class="col-md-8 pull-right">
               <div class="m-t">
                  <span class="m-r-20">Budget Amount</span>
                  <span>:</span>
                  @if($budget[1] == "")
                  <span class="pull-right"><button class="btn btn-sm cstBtn" data-toggle="modal" data-target="#setBudgetModal">Set Budget</button></span>
                  @else
                  <span class="pull-right amt">£ {{$budget[1]}}</span>
                  @endif
               </div>
               <div class="m-t">
                  <span class="m-r-20 p-l-20">Total Amount of HCA</span>
                  <span>:</span>
                  <span class="pull-right amt amt-sm hcaamt">£ 800.00</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20 p-l-20">Total Amount of RGN</span>
                  <span>:</span>
                  <span class="pull-right amt amt-sm rgnamt">£ 1200.00</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20">Total</span>
                  <span>:</span>
                  <span class="pull-right amt totalamt">£ 2000.00</span>
               </div>
            </div>
         </div>
         <div class="col-md-12" id="container1" action="{{route('unit.area.graph.next.month.bookings')}}" token="{{ csrf_token() }}" style="min-width: 310px; height: 300px; margin: 0 auto">
         </div>
      </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="content table-responsive table-full-width">
               <table class="table table-hover table-striped"
                  fetch="{{route('unit.area.budget.monthlybooking.data')}}"
                  token="{{ csrf_token() }}" checkUrl="{{route('unit.area.booking.check')}}">
                  <thead>
                     <th>ID</th>
                     <th>Date</th>
                     <th>Category</th>
                     <th>Staff</th>
                     <th>Shift</th>
                    <!--  <th>Actions</th> -->
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('modal')
@include('unitArea.setBudgetModal')
@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}"></script>
<script src="{{asset('public/js/select2.full.min.js')}}"></script>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script src="{{ asset('public/unitArea/assets/js/pages/budget/budget.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/unitArea/assets/css/coreadmin.css')}}">
@endpush
