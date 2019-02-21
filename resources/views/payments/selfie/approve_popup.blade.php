<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true" action="{{route('payment.selfie.approve')}}" single="{{route('payment.data.single')}}" weekNum="{{$thisWeek->weekNumber}}" token="{{ csrf_token() }}">
   <div class="modal-dialog modal-950">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="exampleModalLabel">Selfie Approval</h3>

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
         <div class="modal-body" >
            <div class='form-group'>
               <div class='col-sm-12 tmshtInfo'>
                  <div class='col-sm-2'>
                     <label class="col-sm-4" for="start">Start</label>
                     <div class="col-sm-8">
                      <p>:<span class="startTime" id="startTime">dt</span></p>
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
                     <p>:<span class="staffHours"></span></p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 grayBg">
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Distance to Unit</label>
                     <div class="col-sm-6">
                        <p>:<span class="distenceToWorkPlace"></span></p>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Mode of Transport</label>
                     <div class="col-sm-6">
                        <p>:<span class="modeOfTransport"></span></p>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Additional Staffs</label>
                     <div class="col-sm-6">
                        <p>:<span class="addtnalStaffs"></span></p>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Bonus by</label>
                     <div class="col-sm-6">
                        <p>:<span class="bonusAuthorizedBy"></span></p>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Notes</label>
                     <div class="col-sm-6">
                        <p>:<span class="notes verfyComment"></span></p>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Historical Rate/Hr</label>
                     <div class="col-sm-6">
                        <input class="form-control histrclRate" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Remarks</label>
                     <div class="col-sm-6">
                        <textarea class="form-control remarks" rows="4" name="remarks"></textarea>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">TS Link</label>
                     <div class="col-sm-6">
                        <input class="form-control"type="text" name="ts_Id" >
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label"></label>
                     <div class="col-sm-6">
                        <button type="button" class="btn btn-warning copyToClipBoard">Copy ID</button>
                     </div>
                  </div>

               </div>
               <div class="col-md-6 blueBg">
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Hourly Rate</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right appHRate" type="text" name="hRate" id="appHRate" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">TA</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right appTa" id="appTa" type="text" name="ta" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Additional TA</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right appExtraTA" id="appExtraTA" type="text" name="extraTA" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Bonus</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right appBonus" id="appBonus" type="text" name="bonus" readonly="readonly">
                        <input class="form-control apphidStaffHrs" type="hidden" id="apphidStaffHrs" name="hidStaffHrs">
                        <input class="form-control bookingId" type="hidden" name="bookingId">
                     </div>
                  </div>

                  <div class="form-group row">
                     <div class="col-sm-6">
                        <input class="form-control otherPay" type="text" name="otherPay" readonly="readonly">
                     </div>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right otherPayAmount" id="otherPayAmount" type="text"  name="otherPayAmount" readonly="readonly">
                     </div>
                  </div>

                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Rate / Hr</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right ratePerHr" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Shift Total</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right appShiftTotal" id="appShiftTotal" type="text" readonly="readonly">
                     </div>
                  </div>

                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Payment Year</label>
                     <div class="col-sm-6">
                        <select class="form-control paymentYear" id="sel1" name="paymentYear">
                          @foreach($taxYears as $year)
                            <option value="{{$year->taxYearId}}">{{$year->taxYearFrom}} - {{$year->taxYearTo}}</option>
                          @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Select Week</label>
                     <div class="col-sm-6">
                        <select class="form-control paymentWeek" id="sel2" name="paymentWeek">
                          @foreach($taxWeeks as $taxWeek)
                           <option value="{{$taxWeek->weekNumber}}">Week {{$taxWeek->weekNumber}}</option>
                          @endforeach
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row m-t-5">
               <div class="col-md-6 grayBg h290">
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Total HR</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right totlHr" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Total TA</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right totTa" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Total Additional TA</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right totlExtraTa" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Total Bonus</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right totalBns" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Total Other Pay</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right totalOtherPayAmount" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Total</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right total" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="Field" class="col-sm-6 col-form-label">FH Rate</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right fhRate" type="text" readonly="readonly">
                     </div>
                  </div>
               </div>
               <div class="col-md-6 blueBg h290">
                  <div class="form-group row">
                     <label for="Field" class="col-sm-6 col-form-label">Gross Pay</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right grossPay" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="Field" class="col-sm-6 col-form-label p-l-40">Weekly Pay</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right weeklyPay" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="Field" class="col-sm-6 col-form-label p-l-40">Holiday Pay</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right hldyPay" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="Field" class="col-sm-6 col-form-label">Gross TA</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right grossTa" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Total Other Pay</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right totalOtherPayAmount" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Shift Grand Total</label>
                     <div class="col-sm-6">
                        <input class="form-control text-right shiftGrandTotal" type="text" readonly="readonly">
                     </div>
                  </div>

               </div>
            </div>
            <div class="row m-l-10">
              <h4 class="tsLink">TS1- <span class="checkdByName"></span> TS2- <span class="vrfiedByName"></span>
                  <span class="approvedByName"></span>
                  </h4>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-warning update-approve" method="3">Revert to Verifier</button>
               <button type="button" class="btn btn-info update-approve" method="1">Save for Later </button>
               <button type="button" class="btn btn-success update-approve" method="0">Proceed to Payment</button>
            </div>
         </div>
      </div>
   </div>
</div>
