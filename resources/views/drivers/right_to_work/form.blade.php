@extends('layouts.template')
@section('title','Right To Work')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title pull-left">Right To Work</h4>
        @if(Session::has('message'))<span class="alert-msg" style="margin-left:30px">{{Session::get('message')}}</span>@endif
    </div>
    <div class="pull-right">
      <a href="{{route('drivers.home')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
    <div class="pull-right m-r-10 w13">
      <select class="form-control select2"
      name="switchProgress"
      action="{{route('drivers.change.progress')}}"
      token="{{ csrf_token() }}"
      driver="{{encrypt($driver->driverId)}}">
        <option value="1" @if($driver->rtwProgress==1) selected="selected" @endif>To Be Started</option>
        <option value="2" @if($driver->rtwProgress==2) selected="selected" @endif>In Progress</option>
        <option value="3" @if($driver->rtwProgress==3) selected="selected" @endif>Completed</option>
      </select>
    </div>
    <div class="pull-right m-r-10 m-t-7">
      <label for="">Progress</label>
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
    <li>{{$driver->forname}} {{$driver->surname}}</li>
    <li>{{$driver->email}}</li>
    <li>{{$driver->mobile}}</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('driver.right.to.work.update')}}" method="post" accept="application/pdf, image/*" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="driverRightToWorkId" value="{{encrypt($rtw->driverRightToWorkId)}}">
        <div class="box-body formRtW" visatype="{{$rtw->country->type}}">
          <div class="m-t-5 smlHead">Passport</div>
          <hr>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Nationality</label>
                  <select class="form-control select2" name="nationality">
                    <option></option>
                    @foreach($countries as $country)
                    <option value="{{$country->countryId}}" data-type="{{$country->type}}"
                       @if($rtw->nationality==$country->countryId) selected="selected" @endif>{{$country->name}}</option>
                    @endforeach
                  </select>
                  <p class="error">Forname is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Passport Number</label>
                  <input class="form-control" name="passportNumber" autocomplete="off" type="text" value="{{$rtw->passportNumber}}" />
                  <p class="error">Surname is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Place of Issue</label>
                  <input class="form-control" name="passportPlaceOfIssue" autocomplete="off" type="text" value="{{$rtw->passportPlaceOfIssue}}" />
                  <p class="error">Position is required</p>
              </div>
            </div>
          </div>

          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Date of Issue</label>
                  <input class="form-control" name="passportDateOfIssue" type="text" value="{{date('d-m-Y',strtotime($rtw->passportDateOfIssue))}}" />
                  <p class="error">Phone is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Expiry Date</label>
                  <input class="form-control" name="passportExpiryDate" autocomplete="off" type="text" value="{{date('d-m-Y',strtotime($rtw->passportExpiryDate))}}" />
                  <p class="error">Email is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Passport SoftCopy</label>
                  <input class="form-control" name="passportDocumentFile" type="file" />
                  <p class="error">Certificate is required</p>
              </div>
            </div>
            @if($rtw->passportDocumentFile)
            <div class='col-sm-2 pull-right'>
              <label>&nbsp;</label>
              <div class='form-group'>
                <a href="{{asset('storage/app/drivers/driver_rtw/passport/'.$rtw->passportDocumentFile)}}" target="_blank" class="btn btn-warning">View Passport</a>
              </div>
            </div>
            @endif
          </div>
          <h4 class="m-t-5 border @if($rtw->country->type==1) greenBorder @endif
             @if($rtw->country->type==2) redBorder @endif">Right to Work Based on the Nationality : <span>
               @if($rtw->country->type==1) UK or EU National - Right to work in the UK Without Visa @endif
               @if($rtw->country->type==2) Non UK or EU National – Need Valid visa or Work Permit to Work @endif
             </span>  </h4>
          <div class="m-t-5 smlHead visa @if($rtw->country->type==1) hidden @endif">Visa</div>
          <hr class="visa @if($rtw->country->type==1) hidden @endif">

          <div class='row visa @if($rtw->country->type==1) hidden @endif'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Type</label>
                  <select class="form-control select2" name="visaType">
                    <option></option>
                    <option value="1" @if($rtw->visaType==1) selected="selected" @endif>UK National</option>
                    <option value="2" @if($rtw->visaType==2) selected="selected" @endif>Permenent Residence</option>
                    <option value="3" @if($rtw->visaType==3) selected="selected" @endif>EU National</option>
                    <option value="4" @if($rtw->visaType==4) selected="selected" @endif>Student</option>
                    <option value="5" @if($rtw->visaType==5) selected="selected" @endif>Work Permit</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                  <label>Visa Number</label>
                  <input class="form-control" name="visaNumber" autocomplete="off" type="text" value="{{$rtw->visaNumber}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                  <label>Place of Issue</label>
                  <input class="form-control" name="visaPlaceOfIssue" autocomplete="off" type="text" value="{{$rtw->visaPlaceOfIssue}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
          </div>

          <div class='row visa @if($rtw->country->type==1) hidden @endif'>
            <div class="col-sm-4">
              <div class="form-group">
                  <label>Date of Issue</label>
                  <input class="form-control" name="visaDateOfIssue" autocomplete="off" type="text" value="{{date('d-m-Y',strtotime($rtw->visaDateOfIssue))}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                  <label>Date of Expiry</label>
                  <input class="form-control" name="visaExpiryDate" autocomplete="off" type="text" value="{{date('d-m-Y',strtotime($rtw->visaExpiryDate))}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Visa SoftCopy</label>
                  <input class="form-control" name="visaDocumentFile" type="file" />
                  <p class="error">Certificate is required</p>
              </div>
            </div>
            @if($rtw->visaDocumentFile)
            <div class='col-sm-2 pull-right'>
              <label>&nbsp;</label>
              <div class='form-group'>
                <a href="{{asset('storage/app/driver/driver_rtw/visa/'.$rtw->visaDocumentFile)}}" target="_blank" class="btn btn-warning">View Visa</a>
              </div>
            </div>
            @endif
          </div>

          <div class='row visa @if($rtw->country->type==1) hidden @endif'>
            <div class="col-sm-4">
              <div class="form-group">
                  <label>Comments</label>
                  <input class="form-control" name="visaComments" autocomplete="off" type="text" value="{{$rtw->visaComments}}" />
                  <p class="error">Comments is required</p>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                  <label>External Verification Required</label>
                  <select class="form-control select2" name="visaExternalVerificationRequired">
                    <option></option>
                    <option value="1" @if($rtw->visaExternalVerificationRequired==1) selected="selected" @endif>Yes</option>
                    <option value="2" @if($rtw->visaExternalVerificationRequired==2) selected="selected" @endif>No</option>
                  </select>
                  <p class="error">Address is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <label>&nbsp;</label>
              <label>Follow Up Date</label>
              <input class="form-control" name="visaFollowUpDate" autocomplete="off" type="text" value="{{$rtw->visaFollowUpDate}}" />
            </div>
          </div>

          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Medical Condition</label>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="disciplinaryProcedure">
                    <option></option>
                    <option value="1" @if($rtw->disciplinaryProcedure==1) selected="selected" @endif>Yes</option>
                    <option value="2" @if($rtw->disciplinaryProcedure==2) selected="selected" @endif>No</option>
                  </select>
              </div>
            </div>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <textarea name="medicalConditionComment" class="form-control" rows="2" cols="80">{{$rtw->medicalConditionComment}}</textarea>
              </div>
            </div>
          </div>

          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Disciplinary Procedure</label>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="disciplinaryProcedure">
                    <option></option>
                    <option value="1" @if($rtw->disciplinaryProcedure==1) selected="selected" @endif>Yes</option>
                    <option value="2" @if($rtw->disciplinaryProcedure==2) selected="selected" @endif>No</option>
                  </select>
              </div>
            </div>

            <div class='col-sm-6'>
              <div class='form-group'>
                  <textarea name="disciplinaryProcedureComment" class="form-control" rows="2" cols="80">{{$rtw->disciplinaryProcedureComment}}</textarea>
              </div>
            </div>
          </div>


          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Pending Investigation</label>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="pendingInvestigation">
                    <option></option>
                    <option value="1" @if($rtw->pendingInvestigation==1) selected="selected" @endif>Yes</option>
                    <option value="2" @if($rtw->pendingInvestigation==2) selected="selected" @endif>No</option>
                  </select>
              </div>
            </div>

            <div class='col-sm-6'>
              <div class='form-group'>
                  <textarea name="pendingInvestigationComment" class="form-control" rows="2" cols="80">{{$rtw->pendingInvestigationComment}}</textarea>
              </div>
            </div>
          </div>

          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Status</label>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <select class="form-control select2" name="status">
                    <option></option>
                    <option value="1" @if($rtw->status==1) selected="selected" @endif>Permitted to work without any further documents</option>
                    <option value="2" @if($rtw->status==2) selected="selected" @endif>Required documents – No Work</option>
                    <option value="3" @if($rtw->status==3) selected="selected" @endif>Permitted to Work - Limited hours work</option>
                  </select>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <label>Maximum Permitted Weekly Hours</label>
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <input class="form-control" name="maximumPermittedWeeklyHours" autocomplete="off" type="text" value="{{$rtw->maximumPermittedWeeklyHours}}"/>
              </div>
            </div>
          </div>

          <div class='row'>
            <div class="col-sm-4">
              <div class="form-group">
                  <label>Checked By</label>
                  <input class="form-control" name="checkedBy" autocomplete="off" type="text" value="{{$rtw->checkedBy}}" />
                  <p class="error">Address is required</p>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                  <label>Checked Date</label>
                  <input class="form-control" name="checkedDate" autocomplete="off" type="text"
                  value="@if($rtw->checkedDate) {{date('d-m-Y',strtotime($rtw->checkedDate))}} @endif"/>
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
<script src="{{asset('public/js/bootbox.min.js')}}"></script>
<script src="{{asset('public/js/pages/drivers/rtw/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
