@extends('layouts.template')
@section('title','Edit Tax Year')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Tax Year-<strong>({{$user->taxYearFrom}}-{{$user->taxYearTo}})</strong> </h4>
    </div>
    <div class="pull-right">
      <a href="{{route('taxyears.home')}}" class="btn btn-warning">
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
      <!-- form start -->
      <form action="{{route('roles.update')}}" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="taxYearFrom">Tax Year From</label>
                  <input class="form-control" name="taxYearFrom" type="number" value="{{$user->taxYearFrom}}" />
                  <p class="error">Tax Year From<is required</p>
                  <input type="hidden" name="pkId" value="{{encrypt($user->taxYearId)}}">
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="taxYearTo">Tax Year To</label>
                  <input class="form-control" name="taxYearTo" type="number"  value="{{$user->taxYearTo}}" />
                  <p class="error">Tax Year To is required</p>
              </div>
            </div>
            
          </div>
          
             </div>
          </div>
        </div>
          
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="submit" class="btn btn-primary" value="Save"/>
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script src="{{asset('public/js/pages/taxyear/edit.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
@endpush
