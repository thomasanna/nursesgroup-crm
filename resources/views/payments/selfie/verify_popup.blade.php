<div class="modal fade" id="supModalverfy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" action="{{route('payment.selfie.verify')}}" single="{{route('payment.data.single')}}" weekNum="{{$thisWeek->weekNumber}}" token="{{ csrf_token() }}">
   <div class="modal-dialog modal-950">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="exampleModalLabel">Selfie Verification</h3>
            
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
                     <p>:<span class="staffHours" id="staffHours"></span>
                     </p>
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
                        <p>:<span class="notes"></span></p>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Remarks</label>
                     <div class="col-sm-6">
                        <textarea class="form-control" rows="4" name="remarks"></textarea>
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
                        <input class="form-control text-right" id="hRate" type="text" name="hRate" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">TA</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right ta" id="ta" type="text" name="ta">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Additional TA</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right extraTA" id="extraTA" type="text" name="extraTA" >
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Bonus</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right bonus" id="bonus" type="text" name="bonus">
                        <input class="form-control hidStaffHrs" type="hidden" id="hidStaffHrs" name="hidStaffHrs">
                        <input class="form-control hidUnitHrs" type="hidden" name="hidUnitHrs">
                        <input class="form-control bookingId" type="hidden" name="bookingId">
                     </div>
                  </div>

                  <div class="form-group row">
                     <div class="col-sm-6">
                        <input class="form-control" type="text" name="otherPay" id="otherPay">
                     </div>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right otherPayAmount" type="text" name="otherPayAmount" id="">
                     </div>
                  </div>

                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Rate / Hr</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right ratePerHrs" name="ratePerHr" type="text" readonly="readonly">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Shift Total</label>
                     <div class="col-sm-3 pull-right">
                        <input class="form-control text-right shiftTotal" name="shiftTotal" type="text" readonly="readonly">
                     </div>
                  </div>

                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Payment Year</label>
                     <div class="col-sm-6">
                        <select class="form-control" id="sel1" name="paymentYear">
                          @foreach($taxYears as $year)
                            <option value="{{$year->taxYearId}}">{{$year->taxYearFrom}} - {{$year->taxYearTo}}</option>
                          @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="" class="col-sm-6 col-form-label">Select Week</label>
                     <div class="col-sm-6">
                        <select class="form-control" id="sel2" name="paymentWeek">
                          @foreach($taxWeeks as $taxWeek)
                           <option value="{{$taxWeek->weekNumber}}">Week {{$taxWeek->weekNumber}}</option>
                          @endforeach
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row m-l-10">
              <h4 class="tsLink">TS1- <span class="checkdByName"></span> TS2- <span class="vrfiedByName"></span></h4>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-info update-verify" method="1">Save For Later </button>
               <button type="button" class="btn btn-success update-verify" method="0">Send For Approval</button>
            </div>
         </div>
      </div>
   </div>
</div>
