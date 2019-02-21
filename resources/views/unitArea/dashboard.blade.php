@extends('unitArea.layouts.template')
@section('title','Dashboard')
@section('content')
<div class="container-fluid">


 <section class="col-lg-3" id="lastWeekChart" action="{{route('unit.area.graph.weekly.bookings')}}" token="{{ csrf_token() }}"></section>
 <section class="col-lg-12" id="monthReportLine"</section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('public/js/highcharts.js') }}"></script>
<script src="{{ asset('public/unitArea/assets/js/pages/dashboard/dashboard.js') }}"></script>
@endpush