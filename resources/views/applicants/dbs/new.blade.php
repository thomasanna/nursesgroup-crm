@extends('layouts.template')
@section('title','New DBS')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="pull-left">
        <h4 class="box-title">New DBS</h4>
    </div>
    <div class="pull-right">
      <a href="{{route('applicant.dbs.home',encrypt($applicant->applicantId))}}" class="btn btn-warning">
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
    <li>{{$applicant->forname}} {{$applicant->surname}}</li>
    <li>{{$applicant->category->name}}</li>
    <li>{{$applicant->email}}</li>
    <li>{{$applicant->mobile}}</li>
  </ul>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="{{route('applicant.dbs.save')}}" method="post" accept="application/pdf, image/*" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="applicantId" value="{{encrypt($applicant->applicantId)}}">
        <div class="box-body">
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="dbsType">Type</label>
                  <select class="form-control select2" name="dbsType">
                    <option></option>
                    <option value="1">New Application</option>
                    <option value="2">Valid DBS Available</option>
                    <option value="3">Update Service User</option>
                  </select>
                  <p class="error">Type is required</p>
              </div>
            </div>
          </div>
          <!-- APLHA -->
          <div class='row alpha hidden'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Application Number</label>
                  <input class="form-control" name="apctnNumber"  type="text"  />
                  <p class="error">Application Number is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Applied Date</label>
                  <input class="form-control" name="apctnAppliedDate"  type="text"  />
                  <p class="error">Applied Date is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Submitted By</label>
                  <input class="form-control" name="apctnSubmittedBy"  type="text"  />
                  <p class="error">Submitted By is required</p>
              </div>
            </div>
          </div>
          <div class='row alpha hidden'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Amount Paid</label>
                  <input class="form-control" name="apctnAmountPaid" type="text"  />
                  <p class="error">Amount Paid is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Paid By</label>
                  <select class="form-control select2" name="apctnPaidBy">
                    <option></option>
                    <option value="1">Nurses Group</option>
                    <option value="2">Staff</option>
                    <option value="3">Update Service</option>
                  </select>
                  <p class="error">Update Service is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>FollowUp Date</label>
                  <input class="form-control" name="apctnFollowUpDate" type="text"  />
                  <p class="error">Applied Date is required</p>
              </div>
            </div>
          </div>
          <div class="row alpha hidden">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Status</label>
                  <select class="form-control select2" name="apctnStatus">
                    <option></option>
                    <option value="1">Search In Progress</option>
                    <option value="2">Search on Hold</option>
                    <option value="3">Certificate Issued</option>
                  </select>
                  <p class="error">Status is required</p>
              </div>
            </div>
          </div>
          <!-- BETA -->
          <div class="row beta hidden">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">DBS Number</label>
                  <input class="form-control" name="validDbsNumber" type="text" />
                  <p class="error">DBS Number< is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Issue Date</label>
                  <input class="form-control" name="validIssueDate" type="text" />
                  <p class="error">Issue Date is required</p>
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Registrered Body</label>
                  <input class="form-control" name="validRegisteredBody" type="text"  />
                  <p class="error">Issued By is required</p>
              </div>
            </div>
          </div>
          <div class="row beta hidden">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Type</label>
                  <select class="form-control select2" name="validType">
                    <option></option>
                    <option value="1">Basic</option>
                    <option value="2">Enhanced</option>
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
          </div>
          <div class='row beta hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Police Records of Convictions,Cautions,Reprimands and Final Warnings</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validPoliceRecordsOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Information from the list held under Section 142 of the Education Act 2002</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validSection142Option">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Protection of Children Act List Information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validChildActListOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>DBS "Adults" barred list information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validVulnerableAdultOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row beta hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Other Relevant Information disclosed at the Chief Police Officer(s) discretion</label>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="validCpoRelevantOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <!-- GAMMA -->
          <div class='row gamma hidden'>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Update Certificate Number</label>
                  <input class="form-control" name="updateServiceNumber"  type="text"  />
                  <p class="error">Application Number is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Checked Date</label>
                  <input class="form-control" name="updateServiceCheckedDate"  type="text"  />
                  <p class="error">Checked Date is required</p>
              </div>
            </div>

            <div class='col-sm-4'>
              <div class='form-group'>
                  <label>Checked By</label>
                  <input class="form-control" name="updateServiceCheckedBy"  type="text"  />
                  <p class="error">Checked By is required</p>
              </div>
            </div>
          </div>
          <div class="row gamma hidden">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Status</label>
                  <select class="form-control select2" name="updateServiceStatus">
                    <option></option>
                    <option value="1">Verified</option>
                    <option value="2">Unable To Verify</option>
                  </select>
                  <p class="error">Status is required</p>
              </div>
            </div>
          </div>
          <div class='row gamma hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Police Records of Convictions,Cautions,Reprimands and Final Warnings</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServicePoliceRecordsOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Information from the list held under Section 142 of the Education Act 2002</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceSection142Option">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Protection of Children Act List Information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceChildActListOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>DBS "Adults" barred list information</label>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceVulnerableAdultOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
                  </select>
              </div>
            </div>
          </div>
          <div class='row gamma hidden'>
            <div class='col-sm-6'>
              <div class='form-group'>
                  <label>Other Relevant Information disclosed at the Chief Police Officer(s) discretion</label>
              </div>
            </div>

            <div class='col-sm-2'>
              <div class='form-group'>
                  <select class="form-control select2" name="updateServiceCpoRelevantOption">
                    <option></option>
                    <option value="1">NON RECORDED</option>
                    <option value="2">RECORDED</option>
                    <option value="3">NOT REQUESTED</option>
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
<script src="{{asset('public/js/pages/applicants/dbs/new.js')}}"></script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('public/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/css/jquery-ui.css')}}">
@endpush
