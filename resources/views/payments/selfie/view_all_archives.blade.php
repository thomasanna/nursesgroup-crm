@extends('layouts.template')
@section('title','Archives')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Archives</h4> <h5>{{$payments[0]->booking->staff->forname}} {{$payments[0]->booking->staff->surname}} </h5>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:0px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
      <a href="{{route('payment.selfie.weeks.list')}}" class="btn btn-warning">
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
    <div class="box box-primary">
    @foreach($payments as $payment)
      <button class="accordion" id={{$payment->taxYear->taxYearId}} staffid={{$payments[0]->booking->staff->staffId}} single="{{route('payment.payee.archives.weeks')}}" token="{{ csrf_token() }}">
        {{$payment->taxYear->taxYearFrom}} - {{$payment->taxYear->taxYearTo}}
      </button>
      <div class="panel" staffId="{{$payments[0]->booking->staff->staffId}}" >
        @for($i=1;$i<4;$i++)
          <div class="weekDisplay{{$i}}" id="weekDisplay" weekNum="{{$i}}" yearId={{$payment->taxYear->taxYearId}} staffid={{$payments[0]->booking->staff->staffId}} single="{{route('payment.payee.archives.weeks.details')}}" token="{{ csrf_token() }}">
            <p>Week {{$i}}</p>
          </div>
        @endfor
      </div>
    @endforeach

@include('payments.selfie.weekly_details_popup')

@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/branches/new.js')}}"></script>
<script type="text/javascript">
    /* Accordion Start*/
     var acc = document.getElementsByClassName("accordion");
      var i;

      for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
          this.classList.toggle("active");
          var panel = this.nextElementSibling;
          if (panel.style.display === "block") {
            panel.style.display = "none";
          } else {
            panel.style.display = "block";
          }
        });
      }
    /* ends */
      $(document).on('click', '.accordion', function (e) {
        var id = $(this).attr('id');
        var staffId = $(this).attr('staffid');
        var singleUrl = $(this).attr('single');
        var token = $(this).attr('token');

        $.ajax({
          type: 'POST',
          async: false,
          url: singleUrl,
          data: {
            "_token": token,
            "yearId": id,
            "staffId": staffId
          },
          success: function (response) {
            console.log(response.data.timesheetId);
          }
        });
      });

      $(document).on('click', '#weekDisplay', function (e) {
        var yearId = $(this).attr('yearId');
        var weekNum = $(this).attr('weekNum');
        var staffId = $(this).attr('staffid');
        var singleUrl = $(this).attr('single');
        var token = $(this).attr('token');

        $.ajax({
          type: 'POST',
          async: false,
          url: singleUrl,
          data: {
            "_token": token,
            "yearId": yearId,
            "weekNum": weekNum,
            "staffId": staffId
          },
          complete: function () {
            $('#weeklyDetailsModalSelfie').modal('show');
          },
          success: function (response) {
            console.log(response.data.payments);
            $('#weeklyDetailsModalSelfie .weekNum').html("Week "+response.weekNum);

          }
        });
      });

  </script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
