<div id="approveModal" class="modal fade" role="dialog" action="{{route('invoices.approve')}}"
                        single="{{route('invoices.single')}}"  token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Unit payment Approval</h4>
        <ul class="smallHdr">
          <li class="bookId"></li>
          <li>|&nbsp;</li>
          <li class="bookingDate"><span class=""></span></li>
          <li>|&nbsp;</li>
          <li class="unitName"></li>
          <li>|&nbsp;</li>
          <li class="categoryName"><span class=""></span></li>
          <li>|&nbsp;</li>
          <li class="shiftName"></li>
          <li>|&nbsp;</li>
          <li class="staffName"></li>
        </ul>
      </div>
      <div class="modal-body">

        <input type="hidden" name="approveInvoiceId">
        <div class="row">
          <div class='col-sm-12 tmshtInfo'>
            <div class='col-sm-2'>
               <label class="col-sm-4" for="start">Start</label>
               <div class="col-sm-8">
                <p>:<span class="startTime"></span></p>
               </div>
               <input type="hidden" name="paymentId">
            </div>
            <div class='col-sm-2'>
               <label class="col-sm-4"  for="end">End</label>
               <div class="col-sm-8">
               <p>:<span class="endTime"></span></p>
               </div>
            </div>
            <div class='col-sm-2'>
               <label class="col-sm-4"  for="break">Break</label>
               <div class="col-sm-8">
               <p>:<span class="breakHours"></span></p>
               </div>
            </div>
            <div class='col-sm-3'>
               <label class="col-sm-6"  for="unit_hrs">Unit Hrs</label>
               <div class="col-sm-4">
               <p>:<span class="unitHours"></span></p>
               </div>
            </div>
            <div class='col-sm-3'>
               <label class="col-sm-6"  for="staff_hrs">Staff Hrs</label>
               <div class="col-sm-4">
               <p>:<span class="staffHours"></span>
               </p>
               </div>
            </div>
          </div> 
        </div>
        <div class="row">
          <div class='col-sm-3'>
            <div class='form-group'>
                <label>Hourly Rate</label>
                <input type="hidden" name="hidUnitHr" class="hidUnitHr">
                <input type="hidden" name="bookingId" class="bookingId">
                <input type="text" class="form-control hRate" name="hRate" readonly="readonly" >
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
                <label>ENIC</label>
                <input type="text" class="form-control enic" name="enic" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
                <label>TA</label>
                <input type="text" class="form-control ta" name="ta" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
                <label>Line Total</label>
                <input type="text" class="form-control lineTotal" name="lineTotal" readonly="readonly">
            </div>
          </div>
        </div>
        <div class="row">         
          <div class='col-sm-3'>
            <div class='form-group'>
                <label>Unit Distance</label>
                <input type="text"  class="form-control distenceToWorkPlace" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
                <label>Invoice Frequency</label>
                <select class="form-control invceFrqncy" readonly="readonly">
                  <option value="1">Weekly</option>
                  <option value="2">Monthly</option>
                </select>
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
              <label>Payment Year</label>
              <select class="form-control paymentYear" readonly="readonly">
                @foreach($taxYears as $year)
                <option value="{{$year->taxYearId}}">
                  {{$year->taxYearFrom}} - {{$year->taxYearTo}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class='col-sm-3 frqncyWeek hidden'>
            <div class='form-group'>
                <label>Payment Week</label>
                <select class="form-control weekNumbr" readonly="readonly">
                  @for($i=1;$i<53;$i++)
                  <option value="{{$i}}">{{$i}}</option>
                  @endfor
                </select>
            </div>
          </div>
          <div class='col-sm-3 frqncyMnth hidden'>
            <div class='form-group'>
                <label>Payment Month</label>
                <select class="form-control monthNumbr" readonly="readonly">
                  @for($i=1;$i<13;$i++)
                  <option value="{{$i}}" @if(date('m') == $i) selected="selected" @endif>{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                  @endfor
                </select>
            </div>
          </div>         
        </div>
        <div class="row">
          <div class='col-sm-7'>
            <div class='form-group'>
                <label>TS Link</label>
                <input type="text" class="form-control tsLink" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-5 m-t-25'>
            <div class='form-group'>
                <label>&nbsp;</label>
                <button type="button" class="btn btn-success copyToClipBoard">Copy Link</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class='col-sm-4'>
          <div class='form-group pull-left'>
              <label>TS Chkd - <span class="tsCheckedBy"></span> / TS Verified - <span class="tsVrfdBy"></span></label>
          </div>
        </div>
        <button type="button" class="btn btn-warning update-approve" method="3">Revert to Verifier</button>
        <button type="button" class="btn btn-info update-approve" method="1">Save for Later </button>
        <button type="button" class="btn btn-success update-approve" method="0">Proceed to Payment</button>
      </div>
    </div>

  </div>
</div>
