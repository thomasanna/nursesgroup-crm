@extends('layouts.template')
@section('title','Edit Reference')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit Reference</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('staffs.references.home',encrypt($reference->staffId))}}" class="btn btn-warning">
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
<!-- Main row -->
<div class="row col-md-8">
  <ul class="smallHdr">
    <li>{{$staff->forname}} {{$staff->surname}}</li>
    <li>{{$staff->category->name}}</li>
    <li>{{$staff->email}}</li>
    <li>{{$staff->mobile}}</li>
  </ul>
</div>
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('staffs.references.update')}}" method="post" accept="application/pdf, image/*" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="referenceId" value="{{encrypt($reference->staffReferenceId)}}">
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">FullName</label>
                  <input class="form-control" name="fullName" type="text" value="{{$reference->fullName}}" />
                  <p class="error">FullName is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Position</label>
                  <input class="form-control" name="position" type="text" value="{{$reference->position}}"/>
                  <p class="error">Position is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Phone</label>
                  <input class="form-control" name="phone" type="text" value="{{$reference->phone}}" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
          </div>

          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Email</label>
                  <input class="form-control" name="email" type="text" value="{{$reference->email}}" />
                  <p class="error">Email is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Website</label>
                  <input class="form-control" name="website" type="text" value="{{$reference->website}}" />
                  <p class="error">Website is required</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                  <label>Address</label>
                  <textarea class="form-control" name="address">{{$reference->address}}</textarea>
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
                    <option value="1" @if($reference->modeOfReference==1) selected="selected" @endif>Phone</option>
                    <option value="2" @if($reference->modeOfReference==2) selected="selected" @endif>Email</option>
                    <option value="3" @if($reference->modeOfReference==3) selected="selected" @endif>Letter</option>
                    <option value="4" @if($reference->modeOfReference==4) selected="selected" @endif>Verbal</option>
                  </select>
                  <p class="error">Mode of Reference is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Reference Sent Date</label>
                  <input class="form-control" name="sentDate" type="text" value="{{date('d-m-Y',strtotime($reference->sentDate))}}" />
                  <p class="error">Sent Date is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Sent By</label>
                  <input class="form-control" name="sentBy" type="text" value="{{$reference->sentBy}}"/>
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
                    <option value="1" @if($reference->status==1) selected="selected" @endif>Sent</option>
                    <option value="2" @if($reference->status==2) selected="selected" @endif>1st FollowUp</option>
                    <option value="3" @if($reference->status==3) selected="selected" @endif>2nd FollowUp</option>
                    <option value="4" @if($reference->status==4) selected="selected" @endif>Inform Staff</option>
                    <option value="5" @if($reference->status==5) selected="selected" @endif>Rejected</option>
                    <option value="6" @if($reference->status==6) selected="selected" @endif>Success</option>
                  </select>
                  <p class="error">Mode of Reference is required</p>
              </div>
            </div>
            @if($reference->status==6)
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label>Follow Up Date</label>
                  <input class="form-control" name="followUpDate" type="text"
                  value="@if($reference->followUpDate){{ date('d-m-Y',strtotime($reference->followUpDate)) }}@endif"/>
                  <p class="error">Follow Up Date is required</p>
              </div>
            </div>
            @endif
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
                  <textarea class="form-control" name="comment">{{$reference->comment}}</textarea>
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
