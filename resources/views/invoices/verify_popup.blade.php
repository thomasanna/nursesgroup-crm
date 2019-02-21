<div id="verifyModal" class="modal fade" role="dialog" action="{{route('invoices.verify')}}" weekNum="{{intval(date('W'))}}" monthNum="{{intval(date('m'))}}" single="{{route('invoices.single')}}"  token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header newHeaderClass" style="background: #8cea8f !important">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="col-sm-8">
          <h4 class="modal-title">Unit payment verification</h4>
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
        <div class="col-sm-4 text-center weekDetails">test</div>
      
      </div>

      <div class="modal-body">

        <input type="hidden" name="verifyInvoiceId">
        <div class="row">
          <div class='col-sm-12 tmshtInfo'>
            <div class='col-sm-2'>
               <div class="col-sm-4" for="start">Start</div>
               <div class="col-sm-8">
                <p>:<span class="startTime f-w-700"></span></p>
               </div>
               <input type="hidden" name="paymentId">
            </div>
            <div class='col-sm-2'>
               <div class="col-sm-4"  for="end">End</div>
               <div class="col-sm-8">
               <p>:<span class="endTime f-w-700"></span></p>
               </div>
            </div>
            <div class='col-sm-2'>
               <div class="col-sm-4"  for="break">Break</div>
               <div class="col-sm-8">
               <p>:<span class="breakHours f-w-700"></span></p>
               </div>
            </div>
            <div class='col-sm-3'>
               <div class="col-sm-6"  for="unit_hrs">Unit Hrs</div>
               <div class="col-sm-4">
               <p>:<span class="unitHours f-w-700"></span></p>
               </div>
            </div>
            <div class='col-sm-3'>
               <div class="col-sm-6"  for="staff_hrs">Staff Hrs</div>
               <div class="col-sm-4">
               <p>:<span class="staffHours f-w-700"></span>
               </p>
               </div>
            </div>
          </div> 
        </div>
        <div class="row">
          
          <div class='col-sm-3 form-group m-t-25'>
             <div class="col-sm-5">Hourly Rate</div>
             <div class="col-sm-7">
                <input type="hidden" name="bookingId" class="bookingId">
                <input type="hidden" name="hidUnitHr" class="hidUnitHr">
              <input type="text" class="form-control f-w-700 hRate" name="hRate" readonly="readonly" >
             </div>
          </div>
          <div class='col-sm-3 form-group m-t-25'>
            <div class="col-sm-2 m-t-5">ENIC</div>
            <div class='col-sm-10 form-group'>
                <input type="text" class="form-control f-w-700 enic" name="enic" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-3 form-group m-t-25'>
            <div class="col-sm-2 m-t-5">TA</div>
            <div class='col-sm-10 form-group'>
                <input type="text" class="form-control f-w-700 ta" name="ta" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-3 form-group m-t-25'>
            <div class="col-sm-4 m-t-5">Line Total</div>
            <div class='col-sm-8 form-group'>
                <input type="text" class="form-control f-w-700 lineTotal lineTotalFont" name="lineTotal" readonly="readonly">
            </div>
          </div>
        </div>
        <div class="row">         
          <div class='col-sm-3'>
            <div class="col-sm-5 m-t-5">Unit Distance</div>
            <div class='col-sm-7 form-group'>
                <input type="text"  class="form-control f-w-700 distenceToWorkPlace" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-3'>
            <div class="col-sm-6 m-t-5">Invoice Frequency</div>
            <div class='col-sm-6 form-group'>
                <select class="form-control f-w-700 invceFrqncy" name="invceFrqncy">
                  <option value="1">Weekly</option>
                  <option value="2">Monthly</option>
                </select>
            </div>
          </div>
          <div class='col-sm-3'>
            <div class="col-sm-5 m-t-5">Payment Year</div>
            <div class='col-sm-7 form-group'>
              <select class="form-control f-w-700 paymentYear" name="paymentYear">
                @foreach($taxYears as $year)
                <option value="{{$year->taxYearId}}">
                  {{$year->taxYearFrom}} - {{$year->taxYearTo}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class='col-sm-3 frqncyWeek hidden'>
            <div class="col-sm-6 m-t-5">Payment Week</div>
            <div class='col-sm-6 form-group'>
                <select class="form-control f-w-700 weekNumbr" name="weekNumbr">
                  @for($i=1;$i<53;$i++)
                  <option value="{{$i}}">{{$i}}</option>
                  @endfor
                </select>
            </div>
          </div>
          <div class='col-sm-3 frqncyMnth hidden'>
            <div class="col-sm-6 m-t-5">Payment Month</div>
            <div class='col-sm-6 form-group'>
                <select class="form-control f-w-700 monthNumbr" name="monthNumbr">
                  @for($i=1;$i<13;$i++)
                  <option value="{{$i}}" @if(date('m') == $i) selected="selected" @endif>{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                  @endfor
                </select>
            </div>
          </div>         
        </div>
        <div class="row">
          <div class='col-sm-5 form-group m-t-25'>
            <div class="col-sm-2 m-t-5">TS Link</div>
            <div class='col-sm-10 form-group fontClass'>
                <input type="text" class="form-control f-w-700 tsLink" name="tsLink" readonly="readonly">
            </div>
          </div>
          <div class='col-sm-2 form-group m-t-5'>
            <div class='form-group'>
                <div>&nbsp;</div>
                <button type="button" class="btn btn-success copyToClipBoard">Copy Link</button>
            </div>
          </div>
          <div class='col-sm-5 form-group m-t-25'>
            <div class="col-sm-2 m-t-5">Comments</div>
            <div class='col-sm-10 form-group'>
                <textarea class="form-control f-w-700 remarks" name="remarks"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class='col-sm-4'>
          <div class='form-group pull-left fontClass'>
              <div>TS Chkd - <span class="tsCheckedBy"></span> / TS Verified - <span class="tsVrfdBy"></span></div>
          </div>
        </div>
        <button type="button" class="btn btn-info update-verify" method="0">Save For Later </button>
        <button type="button" class="btn btn-success update-verify" method="1">Proceed to Payment</button>
      </div>
    </div>

  </div>
</div>
