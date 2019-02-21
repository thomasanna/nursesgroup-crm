@extends('unitArea.layouts.template')
@section('title','New Bookings')
@section('content')
<div class="container-fluid">
<div class="row">
   <span id="newBooking" action="{{route('unit.area.booking.new.action')}}" token="{{ csrf_token() }}" baseUrl="{{ url('/')}}"></span>
    <div class="errmsg" style="color:red; display:none">Please fill all fields</div>
    <form action="" method="post" id="newBookingForm">
              <div class="box box-primary">
                 {!! csrf_field() !!}
                 <div class="box-body newBookingForm">
                    <div class="row shiftRow">
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label for="date">Date</label>
                                 <input class="form-control datepicker requestedDate" name="requestedDate" value="{{date('d-M-Y')}}" type="text" />
                             <p class="error">Date is required</p>
                          </div>
                       </div>
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label for="date">Shift</label>
                             <select class="form-control shiftSel2" name="shiftId">
                                <option value=""></option>
                                <!--         @foreach($shifts as $shift) -->
                                <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
                                <!--            @endforeach -->
                             </select>
                             <p class="error">Shift is required</p>
                          </div>
                       </div>
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label for="date">Staff Category</label>
                             <select class="form-control categorySel2" name="categoryId">
                                <option value=""></option>
                                @foreach($categories as $category)
                                <option value="{{$category->categoryId}}">{{$category->name}}</option>
                                @endforeach
                             </select>
                             <p class="error">Staff Category is required</p>
                          </div>
                       </div>
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label>Number of Booking</label>
                             <select class="form-control select2" name="numbers[]" id="selectNumber">
                                @for($i=1;$i < 11;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                             </select>
                          </div>
                        </div>
                      </div>
                    </div>
                     <button type="button" class="btn btn-success add-new-booking">Save</button>
              </div>   
            </form>
</div>

</div>
@endsection

@push('scripts')
<script src="{{asset('public/js/moment.min.js')}}"></script>

<script src="{{ asset('public/unitArea/assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{asset('public/js/select2.full.min.js')}}"></script>
<script src="{{ asset('public/unitArea/assets/js/pages/bookings/newbooking.js') }}"></script>


@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/unitArea/assets/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('public/unitArea/assets/css/coreadmin.css')}}">
@endpush
