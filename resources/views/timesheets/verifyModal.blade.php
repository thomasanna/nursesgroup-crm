<div id="verifyModal" class="modal fade" role="dialog"  action="{{route('timesheet.verify')}}" revert="{{route('timesheet.revert')}}"  single="{{route('timesheet.get.timesheet')}}" token="{{ csrf_token() }}">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">TimeSheet- Verify</h4>
      <div class="row col-md-8">
         <ul class=" shiftSmry logClass">
            <li class="bookId"></li> |
            <li class="date"></li> |
            <li class="categry"></li> |
            <li class="unit"></li> |
            <li class="shift"></li> |
            <li class="staff"></li>
          </ul>
      </div>
   </div>
   <div class="modal-body">
      <div class="row">
         <div class='col-sm-12 m-t-10'>
            <div class='form-group'>
               <div class='col-sm-3'>
                  <label for="" class="col-sm-5 col-form-label">Start</label>
                  <div class="col-sm-7 pull-right">
                     <input class="form-control" type="text" name="startTime" disabled="disabled" >
                     <input type="hidden" name="timesheetId">
                  </div>
               </div>
                  
               <div class='col-sm-2'>
                  <label class="col-sm-4 col-form-label" for="end">End</label>
                  <div class="col-sm-8 pull-right">
                     <input class="form-control" type="text" name="endTime" disabled="disabled">
                  </div>
               </div>
               <div class='col-sm-2'>
                  <label class="col-sm-4 col-form-label" for="break">Break</label>
                  <div class="col-sm-8 pull-right">
                     <input class="form-control" type="text" name="breakHours" disabled="disabled" >
                  </div>
               </div>
               <div class='col-sm-2'>
                  <label class="col-sm-6 col-form-label" for="unit_hrs">Unit Hrs</label>
                  <div class="col-sm-6 pull-right p0">
                     <input class="form-control p0" type="text" name="unitHours" disabled="disabled">
                  </div>
               </div>
               <div class='col-sm-3'>
                  <label class="col-sm-5 col-form-label" for="staff_hrs">Staff Hrs</label>
                  <div class="col-sm-7 pull-right">
                     <input class="form-control" type="text" name="staffHours" disabled="disabled" >
                  </div>
               </div>
            </div>
         </div>
         <div class='col-sm-12'>
            <br>
            <div class='form-group'>
               <div class='col-sm-3'>
                  <label class="col-sm-4 col-form-label" for="ts_No">TS No</label>
                  <div class="col-sm-8 pull-right">
                     <input class="form-control"type="text" name="ts_No" disabled="disabled">
                     <p class="error">TS No is required</p>
                  </div>
               </div>
               <div class='col-sm-6'>
                  <label class="col-sm-2 col-form-label" for="ts_Id">TS ID</label>
                  <div class="col-sm-10 pull-right">
                     <input class="form-control"type="text" name="ts_Id" readonly="readonly">
                  </div>
               </div>
               <div class='col-sm-1'>
                  <button type="button" class="btn btn-warning copyToClipBoard">Copy ID</button>
               </div>
               
               <div class='col-sm-1'>
                  <button type="button" class="btn btn-success sendSMS" action="{{route(('timesheet.verify.sms'))}}" token="{{ csrf_token() }}">Accept SMS</button>
               </div>
            </div>
         </div>
         <div class='col-sm-12'>
            <br>
            <div class='form-group'>
               <label for="comment">Comment:</label>
               <textarea class="form-control" rows="5" name="comments"></textarea>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger revertActn">Revert to CheckIn</button>
            <button class="btn btn-success verifyActn" value="submit">Verify</button>
         </div>
      </div>
   </div>
</div>
</div>
</div>