@extends('layouts.template')
@section('title','New Unit Payments')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Payment Configuration</h4>
    </div>
    <div class="pull-left m-t-5">
      @if(session()->has('message'))<span class="alert-msg m-l-10">{{session()->get('message')}}</span>@endif
    </div>
    <div class="pull-right">
      <a href="{{route('client_units.home')}}" class="btn btn-warning">
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
    @foreach($categories as $category)
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title" style="color:#{{$category['colorCode']}}">{{$category['name']}}</h3>
      </div>
      <!-- form start -->
        @foreach($categoryTypes as $cType)
        <div class="box-body">
          <form action="{{route('client_unit_payments.save')}}" method="post" >
            {!! csrf_field() !!}
            <input type="hidden" name="clientUnitId" value="{{$clientUnitId}}">
            <input type="hidden" name="categoryId" value="{{encrypt($category['categoryId'])}}">
            <input type="hidden" name="rateType" value="{{encrypt($cType['id'])}}">

            <div class="row">
              <div class="col-sm-1">
                <div class="form-group">
                    <label>&nbsp;<br>&nbsp;</label>
                    <input class="form-control" style="background:{{$cType['colorCode']}};color:#fff;"
                    disabled="disabled" type="text" value="{{$cType['name']}}">
                </div>
              </div>

              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Monday</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="dayMonday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['dayMonday']))
                        value="{{$category['type'][$cType['id']]['rates']['dayMonday']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="nightMonday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['nightMonday']))
                        value="{{$category['type'][$cType['id']]['rates']['nightMonday']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Tuesday</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="dayTuesday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['dayTuesday']))
                        value="{{$category['type'][$cType['id']]['rates']['dayTuesday']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="nightTuesday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['nightTuesday']))
                        value="{{$category['type'][$cType['id']]['rates']['nightTuesday']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Wednesday</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="dayWednesday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['dayWednesday']))
                        value="{{$category['type'][$cType['id']]['rates']['dayWednesday']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="nightWednesday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['nightWednesday']))
                        value="{{$category['type'][$cType['id']]['rates']['nightWednesday']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Thursday</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="dayThursday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['dayThursday']))
                        value="{{$category['type'][$cType['id']]['rates']['dayThursday']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="nightThursday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['nightThursday']))
                        value="{{$category['type'][$cType['id']]['rates']['nightThursday']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Friday</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="dayFriday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['dayFriday']))
                        value="{{$category['type'][$cType['id']]['rates']['dayFriday']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="nightFriday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['nightFriday']))
                        value="{{$category['type'][$cType['id']]['rates']['nightFriday']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Saturday</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="daySaturday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['daySaturday']))
                        value="{{$category['type'][$cType['id']]['rates']['daySaturday']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="nightSaturday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['nightSaturday']))
                        value="{{$category['type'][$cType['id']]['rates']['nightSaturday']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Sunday</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="daySunday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['daySunday']))
                        value="{{$category['type'][$cType['id']]['rates']['daySunday']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="nightSunday" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['nightSunday']))
                        value="{{$category['type'][$cType['id']]['rates']['nightSunday']}}"
                      @endif>
                  </div>
                </div>
              </div>
            </div>
            <!-- Close of Row -->
            <div class="row">
              <div class="col-sm-1">
                <div class="form-group">
                    <label>&nbsp;<br>&nbsp;</label>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">BH</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="bhDay" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['bhDay']))
                        value="{{$category['type'][$cType['id']]['rates']['bhDay']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="splBhDay" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['splBhDay']))
                        value="{{$category['type'][$cType['id']]['rates']['splBhDay']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC12">
                <div class="text-center b1Solid">Special BH</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Day</label>
                      <input class="form-control" name="splBhNight" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['splBhNight']))
                        value="{{$category['type'][$cType['id']]['rates']['splBhNight']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>Night</label>
                      <input class="form-control" name="bhNight" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['bhNight']))
                        value="{{$category['type'][$cType['id']]['rates']['bhNight']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-2 wC15">
                <div class="text-center b1Solid">TA</div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>TA per Mile</label>
                      <input class="form-control" name="taPerMile" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['taPerMile']))
                        value="{{$category['type'][$cType['id']]['rates']['taPerMile']}}"
                      @endif>
                  </div>
                </div>
                <div class="col-sm-6 p0">
                  <div class="form-group">
                      <label>No of Miles</label>
                      <input class="form-control" name="taNoOfMiles" type="text"
                      @if(isset($category['type'][$cType['id']]['rates']['taNoOfMiles']))
                        value="{{$category['type'][$cType['id']]['rates']['taNoOfMiles']}}"
                      @endif>
                  </div>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="text-center">&nbsp;</div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <input type="submit" value="Save" class="btn btn-primary form-control">
                </div>
              </div>
            </div>
          </form>
        </div>
        <hr>
        @endforeach
        <!-- /.box-body -->
    </div>
    @endforeach
    <!-- /.box -->
  </div>
</div>
@endsection
