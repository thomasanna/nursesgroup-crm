@extends('layouts.template')
@section('title','Staff Availabilty')
@section('content')
<!-- Header Navigation -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left">
        <h1 class="box-title">Availability Report </h1>
        @if(session()->has('message'))<span class="alert-msg" style="margin-left:30px">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
        <!-- <a href="" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>
          New Staff</a> -->
    </div>
  </div>
</div>
<div class="box box-default">
  <div class="box-header with-border">
    <div class="pull-left col-md-5 p-l-0">
      <span class="col-md-4">
          <a href="{{route('staffs.availabilty.report',[2])}}"  class="btn btn-success pull-left m-r-10" >HCA</a>
          <a href="{{route('staffs.availabilty.report',[1])}}"  class="btn btn-warning pull-left" >RGN</a>
      </span>
    </div>
  </div>
</div>
  <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <div class="box">
        <div class="box-body  no-padding">
            <section class="m-t-10 table-responsive report-view" id="reportView">

                <table class="table able-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Name</td>
                            @foreach($dateArray as $row)
                                <th class="text-center
                                    @if($row['date']->isWeekend())
                                    weekend
                                    @elseif($row['date']->isMonday())
                                    monday
                                    @elseif($row['date']->isTuesday())
                                    tuesday
                                    @elseif($row['date']->isWednesday())
                                    wednesday
                                    @elseif($row['date']->isThursday())
                                    thursday
                                    @elseif($row['date']->isFriday())
                                    friday
                                    @endif">
                                    {{$row['date']->format('d/m')}}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $staffInfo)
                        <tr>
                            <td class="text-left"><strong>{{$staffInfo['staff']}}</strong></td>
                            @foreach($staffInfo['data'] as $dateInfo)
                                <td class="text-center
                                    @if($dateInfo['date']->isWeekend())
                                    weekend
                                    @elseif($dateInfo['date']->isMonday())
                                    monday
                                    @elseif($dateInfo['date']->isTuesday())
                                    tuesday
                                    @elseif($dateInfo['date']->isWednesday())
                                    wednesday
                                    @elseif($dateInfo['date']->isThursday())
                                    thursday
                                    @elseif($dateInfo['date']->isFriday())
                                    friday
                                    @endif">
                                    {!!$dateInfo['value']!!}
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </section>
        </div>
    </div>
    <!-- /.content -->
  <!-- /.content-wrapper -->
<!-- ./wrapper -->
<div class="modelPophldr"></div>
<div class="loading">
  <img src="{{asset('public/images/loading.gif')}}" alt="Loading">
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}">
@endpush
@push('scripts')
<script src="{{ asset('public/js/select2.min.js') }}"></script>
<script src="{{ asset('public/js/pages/availabilty/report.js') }}"></script>
@endpush
