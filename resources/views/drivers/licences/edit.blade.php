@extends('layouts.template')
@section('title','Edit Licence')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Licence</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('driver.licences.home',encrypt($licence->driverId))}}" class="btn btn-warning">
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
<div class="row col-md-8">
  <ul class="smallHdr">
    <li>{{$licence->driver->forname}} {{$licence->driver->surname}}</li>
    <li>{{$licence->driver->email}}</li>
    <li>{{$licence->driver->mobile}}</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('driver.licences.update')}}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="driverLicenceId" value="{{encrypt($licence->driverLicenceId)}}">
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Licence Number</label>
                  <input class="form-control" name="number" type="text" value="{{$licence->number}}" />
                  <p class="error">Make is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Date Of Issue</label>
                  <input class="form-control" name="dateOfIssue" type="text" value="{{date('d-m-Y',strtotime($licence->dateOfIssue))}}" />
                  <p class="error">Model is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Date Of Expiry</label>
                  <input class="form-control" name="dateOfExpiry" type="text" value="{{date('d-m-Y',strtotime($licence->dateOfExpiry))}}" />
                  <p class="error">Reg Number is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Valid From</label>
                    <input class="form-control" name="validFrom" type="text" value="{{date('d-m-Y',strtotime($licence->validFrom))}}"/>
                  <p class="error">Color is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Valid To</label>
                  <input class="form-control" name="validTo" type="text" value="{{date('d-m-Y',strtotime($licence->number))}}" />
                  <p class="error">Make is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date">Issued By</label>
                  <input class="form-control" name="issuedBy" type="text" value="{{$licence->issuedBy}}" />
                  <p class="error">Make is required</p>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class="col-sm-2">
              <div class="form-group">
                  <label>Licence (PDF Only)</label>
                  <input class="form-control" name="softCopy" type="file">
                  <p class="error">Photo is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              @if($licence->softCopyExist==1)
                <div class='form-group'>
                  <label class="col-md-12">&nbsp;</label>
                  <a href="{{asset('storage/app/drivers/driver_licence/'.$licence->softCopy)}}" target="_blank" class="btn btn-primary">View Document</a>
                </div>
              @endif
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
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/pages/drivers/licences/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
