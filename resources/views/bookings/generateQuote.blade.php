@extends('layouts.template')
@section('title','Generate Quote')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Generate Quote</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('booking.current')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Filter Area -->
<!-- <div class="box box-default">
  <div class="box-body">

  </div>
</div> -->
<!-- Filter Area -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">

      <div class="box-body">
        <table class="table injectHere" action="{{route('booking.quote.preview.data')}}" token="{{csrf_token()}}">
          
        </table>
      </div>

      <div class="box-footer">
          <a href="{{route('booking.quote.generate')}}" class="btn btn-primary">Shift Report</a>
          <a href="{{route('booking.quote.generate')}}" class="btn btn-warning">Export To Excel</a>
          <a href="{{route('booking.payee.report')}}" class="btn btn-success">Payee Report</a>
          <a href="{{route('booking.daily.report')}}" class="btn btn-danger">Daily Report</a>
          <a href="{{route('booking.further.report')}}" class="btn btn-info">Further Shift Report</a>
      </div>

    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/pages/bookings/generate-quote.js')}}?{{ time() }}"></script>
@endpush
