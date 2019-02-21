@extends('layouts.template')
@section('title','Edit DBS')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">Edit DBS</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('staffs.dbs.home',encrypt($dbs->staffId))}}" class="btn btn-warning">
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
      <form action="{{route('staffs.dbs.update')}}" method="post" accept="application/pdf, image/*" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="staffId" value="{{encrypt($staff->staffId)}}">
        <input type="hidden" name="dbsId" value="{{encrypt($dbs->staffDbsId)}}">
        <input type="hidden" name="dbsType" value="{{$dbs->dbsType}}">
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="dbsType">Type</label>
                  <select class="form-control select2 dbsType" disabled="disabled">
                    <option></option>
                    <option value="1" @if($dbs->dbsType==1) selected="selected" @endif>New Application</option>
                    <option value="2" @if($dbs->dbsType==2) selected="selected" @endif>Valid DBS Available</option>
                    <option value="3" @if($dbs->dbsType==3) selected="selected" @endif>Update Service User</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>
          </div>
          <!-- APLHA -->
          <div class='row alpha @if($dbs->dbsType==2 || $dbs->dbsType==3) hidden @endif'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Application Number</label>
                  <input class="form-control" name="apctnNumber" value="{{$dbs->apctnNumber}}"  type="text"  />
                  <p class="error">Application Number is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Applied Date</label>
                  <input class="form-control" name="apctnAppliedDate" value="{{date('d-m-Y',strtotime($dbs->apctnAppliedDate))}}"  type="text"  />
                  <p class="error">Applied Date is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Submitted By</label>
                  <input class="form-control" name="apctnSubmittedBy" value="{{$dbs->apctnSubmittedBy}}"  type="text"  />
                  <p class="error">Submitted By is required</p>
              </div>
            </div>
          </div>
          <div class='row alpha @if($dbs->dbsType==2 || $dbs->dbsType==3) hidden @endif'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Amount Paid</label>
                  <input class="form-control" name="apctnAmountPaid" value="{{$dbs->apctnAmountPaid}}" type="text"  />
                  <p class="error">Amount Paid is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Paid By</label>
                  <select class="form-control select2" name="apctnPaidBy">
                    <option></option>
                    <option value="1" @if($dbs->apctnPaidBy ==1) selected="selected" @endif>Nurses Group</option>
                    <option value="2" @if($dbs->apctnPaidBy ==2) selected="selected" @endif>Staff</option>
                    <option value="3" @if($dbs->apctnPaidBy ==3) selected="selected" @endif>Update Service</option>
                  </select>
                  <p class="error">Update Service is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>FollowUp Date</label>
                  <input class="form-control" name="apctnFollowUpDate" value="{{date('d-m-Y',strtotime($dbs->apctnFollowUpDate))}}" type="text"  />
                  <p class="error">Applied Date is required</p>
              </div>
            </div>
          </div>
          <div class="row alpha @if($dbs->dbsType==2 || $dbs->dbsType==3) hidden @endif">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Status</label>
                  <select class="form-control select2" name="apctnStatus">
                    <option></option>
                    <option value="1" @if($dbs->apctnStatus ==1) selected="selected" @endif>Search In Progress</option>
                    <option value="2" @if($dbs->apctnStatus ==2) selected="selected" @endif>Search on Hold</option>
                    <option value="3" @if($dbs->apctnStatus ==3) selected="selected" @endif>Certificate Issued</option>
                  </select>
                  <p class="error">Status is required</p>
              </div>
            </div>
          </div>
          <!-- BETA -->
          <div class="row beta @if($dbs->dbsType==1 || $dbs->dbsType==3) hidden @endif">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">DBS Number</label>
                  <input class="form-control" name="validDbsNumber" value="{{$dbs->validDbsNumber}}" type="text" />
                  <p class="error">DBS Number< is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Issue Date</label>
                  <input class="form-control" name="validIssueDate" value="{{date('d-m-Y',strtotime($dbs->validIssueDate))}}" type="text" />
                  <p class="error">Issue Date is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Registrered Body</label>
                  <input class="form-control" name="validRegisteredBody" value="{{$dbs->validRegisteredBody}}" type="text"  />
                  <p class="error">Issued By is required</p>
              </div>
            </div>
          </div>
          <div class="row beta @if($dbs->dbsType==1 || $dbs->dbsType==3) hidden @endif">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="validType">
                    <option></option>
                    <option value="1" @if($dbs->validType==1) selected="selected" @endif>Basic</option>
                    <option value="2" @if($dbs->validType==2) selected="selected" @endif>Enhanced</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Certificate</label>
                  <input class="form-control" name="validCertificate" type="file" />
                  <p class="error">Certificate is required</p>
              </div>
            </div>
            @if($dbs->validCertificate)
            <div class="col-sm-4">
              <div class="form-group">
                <label class="col-md-12">&nbsp;</label>
                <a href="{{asset('storage/app/staff/staff_dbs/'.$dbs->validCertificate)}}" target="_blank" class="btn btn-success">View Certificate</a>
              </div>
            </div>
            @endif
          </div>
          <div class='row beta @if($dbs->dbsType==1 || $dbs->dbsType==3) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Police Records of Convictions,Cautions,Reprimands and Final Warnings</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validPoliceRecordsOption">
                    <option></option>
                    <option value="1" @if($dbs->validPoliceRecordsOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->validPoliceRecordsOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->validPoliceRecordsOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta @if($dbs->dbsType==1 || $dbs->dbsType==3) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Information from the list held under Section 142 of the Education Act 2002</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validSection142Option">
                    <option></option>
                    <option value="1" @if($dbs->validSection142Option==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->validSection142Option==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->validSection142Option==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta @if($dbs->dbsType==1 || $dbs->dbsType==3) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Protection of Children Act List Information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validChildActListOption">
                    <option></option>
                    <option value="1" @if($dbs->validChildActListOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->validChildActListOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->validChildActListOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta @if($dbs->dbsType==1 || $dbs->dbsType==3) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>DBS "Adults" barred list information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validVulnerableAdultOption">
                    <option></option>
                    <option value="1" @if($dbs->validVulnerableAdultOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->validVulnerableAdultOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->validVulnerableAdultOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta @if($dbs->dbsType==1 || $dbs->dbsType==3) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Other Relevant Information disclosed at the Chief Police Officer(s) discretion</label>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validCpoRelevantOption">
                    <option></option>
                    <option value="1" @if($dbs->validCpoRelevantOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->validCpoRelevantOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->validCpoRelevantOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <!-- GAMMA -->
          <div class='row gamma  @if($dbs->dbsType==1 || $dbs->dbsType==2) hidden @endif'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Update Certificate Number</label>
                  <input class="form-control" name="updateServiceNumber" value="{{$dbs->updateServiceNumber}}" type="text"  />
                  <p class="error">Application Number is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Checked Date</label>
                  <input class="form-control" name="updateServiceCheckedDate" value="{{date('d-m-Y',strtotime($dbs->updateServiceCheckedDate))}}" type="text"  />
                  <p class="error">Checked Date is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Checked By</label>
                  <input class="form-control" name="updateServiceCheckedBy" value="{{$dbs->updateServiceCheckedBy}}" type="text"  />
                  <p class="error">Checked By is required</p>
              </div>
            </div>
          </div>
          <div class="row gamma @if($dbs->dbsType==1 || $dbs->dbsType==2) hidden @endif">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Status</label>
                  <select class="form-control select2" name="updateServiceStatus">
                    <option></option>
                    <option value="1" @if($dbs->updateServiceStatus==1) selected="selected" @endif>Verified</option>
                    <option value="2" @if($dbs->updateServiceStatus==2) selected="selected" @endif>Unable To Verify</option>
                  </select>
                  <p class="error">Status is required</p>
              </div>
            </div>
          </div>
          <div class='row gamma  @if($dbs->dbsType==1 || $dbs->dbsType==2) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Police Records of Convictions,Cautions,Reprimands and Final Warnings</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServicePoliceRecordsOption">
                    <option></option>
                    <option value="1" @if($dbs->updateServicePoliceRecordsOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->updateServicePoliceRecordsOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->updateServicePoliceRecordsOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma  @if($dbs->dbsType==1 || $dbs->dbsType==2) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Information from the list held under Section 142 of the Education Act 2002</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceSection142Option">
                    <option></option>
                    <option value="1" @if($dbs->updateServiceSection142Option==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->updateServiceSection142Option==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->updateServiceSection142Option==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma @if($dbs->dbsType==1 || $dbs->dbsType==2) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Protection of Children Act List Information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceChildActListOption">
                    <option></option>
                    <option value="1" @if($dbs->updateServiceChildActListOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->updateServiceChildActListOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->updateServiceChildActListOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma @if($dbs->dbsType==1 || $dbs->dbsType==2) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>DBS "Adults" barred list information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceVulnerableAdultOption">
                    <option></option>
                    <option value="1" @if($dbs->updateServiceVulnerableAdultOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->updateServiceVulnerableAdultOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->updateServiceVulnerableAdultOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma @if($dbs->dbsType==1 || $dbs->dbsType==2) hidden @endif'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Other Relevant Information disclosed at the Chief Police Officer(s) discretion</label>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceCpoRelevantOption">
                    <option></option>
                    <option value="1" @if($dbs->updateServiceCpoRelevantOption==1) selected="selected" @endif>NON RECORDED</option>
                    <option value="2" @if($dbs->updateServiceCpoRelevantOption==2) selected="selected" @endif>RECORDED</option>
                    <option value="3" @if($dbs->updateServiceCpoRelevantOption==3) selected="selected" @endif>NOT REQUESTED</option>
                  </select>
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
<script src="{{asset('public/js/pages/staffs/dbs/edit.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
