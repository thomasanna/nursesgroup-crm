@extends('layouts.template')
@section('title','Further Shift SMS')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Send FS SMS</h4>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-left shftSmry">
      <ul class="smallHdr">
        <li><strong></strong></li>
      </ul>
    </div>
    <div class="pull-right">
      <a href="{{route('booking.current')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary" action="{{route('booking.fs.sms.data')}}" token="{{csrf_token()}}">
      <!-- form start -->

        <div class="box-body">
          <form action="{{route('booking.send.fs.sms')}}" method="post" >
            {!! csrf_field() !!}
          <div class='row'>
            <div class='col-sm-10 m-l-10'>
              <div class='form-group'>
                  <label for="date" class="m-t-10"></label>
                  <textarea rows="15" name="sms" class="form-control m-t-25 "></textarea>
              </div>
            </div>
            <div class='col-sm-1 m-t-25'>
              <div class='form-group'>
                  <label for="date">&nbsp;</label>
                  <input type="submit" class="btn btn-primary" value="Send">
              </div>
            </div>
          </div>
          </form>
        </div>
        <hr>
        <hr>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/pages/bookings/fsSms.js')}}"></script>
@endpush

