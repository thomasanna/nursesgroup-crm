@extends('unitArea.layouts.template')
@section('title','Bookings')
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-xs-12">
         <div class="box">
            <div class="box-body">
               <div class="row bgDarkBlue">
                  <div class='col-sm-3'>
                     <div class='form-group'>
                        <label for="date">Date</label>
                        <input class="form-control daterangpicker" id="searchDate"  type="text" placeholder="Date" autocomplete="off" />
                     </div> 
                  </div>
                  <div class='col-sm-2'>
                     <div class='form-group'>
                        <label for="date"> Category</label>
                        <select class="form-control categorySel2" id="searchCategory">
                           <option value=""></option>
                           @foreach($categories as $category)
                           <option value="{{$category->categoryId}}">{{$category->name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class='col-sm-2'>
                     <div class='form-group'>
                        <label for="date">Shift</label>
                        <select class="form-control shiftSel2" id="searchShift">
                           <option value=""></option>
                           @foreach($shifts as $shift)
                           <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
                           @endforeach
                        </select>
                        <p class="error"></p>
                     </div>
                  </div>
                  <div class='col-sm-1'>
                     <div class='form-group '>
                        <a href="javascript:void(0)" class="btn btn-warning" style="margin-top:25px;" id="searchReset">Reset</a>
                     </div>
                  </div>
                  <div class="pull-right">
                     <div class='col-sm-1'>
                        <div class='form-group '>
                           <a href="javascript:void(0)" data-toggle="modal" data-target="#newBookingModal" class="btn btn-primary openBookingModal" style="margin-top:25px;" id="searchReset"><i class="fa fa-plus" aria-hidden="true"></i>New Booking</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="header">
               <h4 class="title">Bookings</h4>
            </div>
            <div class="content table-responsive table-full-width">
               <table class="table table-hover table-striped"
                  fetch="{{route('unit.area.booking.data')}}"
                  token="{{ csrf_token() }}" checkUrl="{{route('unit.area.booking.check')}}">
                  <thead>
                     <th>ID</th>
                     <th>Date</th>
                     <th>Category</th>
                     <th>Staff</th>
                     <th>Shift</th>
                     <th>Actions</th>
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('modal')
@include('unitArea.newBookingmodal')
@endsection
@push('scripts')
<script src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{asset('public/js/moment.min.js')}}"></script>

<script src="{{ asset('public/unitArea/assets/js/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/unitArea/assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{asset('public/js/select2.full.min.js')}}"></script>

<script src="{{ asset('public/unitArea/assets/js/pages/bookings/bookings.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/unitArea/assets/css/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('public/unitArea/assets/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('public/unitArea/assets/css/coreadmin.css')}}">
@endpush
