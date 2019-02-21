@extends('layouts.template')
@section('title','New Reference')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New Reference</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('staffs.references.home',encrypt($staff->staffId))}}" class="btn btn-warning">
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
    <div class="row alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row col-md-8">
  <ul class="smallHdr">
    <li>{{$staff->forname}} {{$staff->surname}}</li>
    <li>{{$staff->category->name}}</li>
    <li>{{$staff->email}}</li>
    <li>{{$staff->mobile}}</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('staffs.references.save')}}" method="post" accept="application/pdf, image/*" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="staffId" value="{{encrypt($staff->staffId)}}">
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">FullName</label>
                  <input class="form-control" name="fullName" type="text" />
                  <p class="error">FullName is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Position</label>
                  <input class="form-control" name="position" autocomplete="off" type="text" autocomplete="off" />
                  <p class="error">Position is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Phone</label>
                  <input class="form-control" name="phone" type="text" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
          </div>

          <div class='row'>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Email</label>
                  <input class="form-control" name="email" autocomplete="off" type="text" />
                  <p class="error">Email is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Website</label>
                  <input class="form-control" name="website" autocomplete="off" type="text" />
                  <p class="error">Website is required</p>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                  <label>Address</label>
                  <textarea class="form-control" name="address"></textarea>
                  <p class="error">Address is required</p>
              </div>
            </div>

          </div>

          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Mode of Reference</label>
                  <select class="form-control select2" name="modeOfReference">
                    <option></option>
                    <option value="1">Phone</option>
                    <option value="2">Email</option>
                    <option value="3">Letter</option>
                    <option value="4">Verbal</option>
                  </select>
                  <p class="error">Mode of Reference is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Sent Date</label>
                  <input class="form-control" name="sentDate" autocomplete="off" type="text" />
                  <p class="error">Sent Date is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Sent By</label>
                  <input class="form-control" name="sentBy" autocomplete="off" type="text"/>
                  <p class="error">Certificate Number is required</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Status</label>
                  <select class="form-control select2" name="status">
                    <option></option>
                    <option value="1">Sent</option>
                    <option value="2">1st FollowUp</option>
                    <option value="3">2nd FollowUp</option>
                    <option value="4">Inform Staff</option>
                    <option value="5">Rejected</option>
                    <option value="6">Success</option>
                  </select>
                  <p class="error">Mode of Reference is required</p>
              </div>
            </div>
            <div class='col-sm-3 followDiv'>
              <div class='form-group'>
                  <label>Follow Up Date</label>
                  <input class="form-control" name="followUpDate" type="text" value="{{ old('followUpDate') }}"/>
                  <p class="error">Follow Up Date is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Certificate</label>
                  <input class="form-control" name="documentFile" type="file" />
                  <p class="error">Certificate is required</p>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                  <label>Comments</label>
                  <textarea class="form-control" name="comment"> {{ old('position') }}</textarea>
                  <p class="error">Address is required</p>
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
<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('public/js/pages/staffs/references/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
