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
      <a href="{{route('payment.payee.archives')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
<!-- Header Navigation -->
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <div class="row accordion">
      @foreach($payments as $payment)
      <span class="itemFetchWeek" year="{{$payment->taxYear->taxYearId}}" 
      staff="{{$payments[0]->booking->staff->staffId}}" 
      single="{{route('payment.payee.archives.weeks')}}" 
      token="{{ csrf_token() }}">{{$payment->taxYear->taxYearFrom}} - {{$payment->taxYear->taxYearTo}}</span>
      <div class="weekInjctr"></div>
      @endforeach
    </div>
  </div>
</div>

<div class="modal fade" id="raModal" tabindex="-1" role="dialog" aria-hidden="true" token="{{ csrf_token() }}">
   <div class="modal-dialog modal-90p">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h3 class="modal-title">Remittence Advice</h3>
         </div>
         <div class="modal-body" >
         </div>
      </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('public/js/pages/payments/archives/view_all.js') }}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
