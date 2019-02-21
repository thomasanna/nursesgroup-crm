<div id="checkInModal" class="modal fade" role="dialog"  action="{{route('timesheet.checkin')}}"  single="{{route('timesheet.get.timesheet')}}" token="{{ csrf_token() }}">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Time Sheet- Check in</h4>
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
               <div class='col-sm-2 p-r-0'>
                  <label class="col-sm-4 col-form-label" for="start">Start</label>
                  <div class="col-sm-8 pull-right">
                     <input class="form-control" type="text" name="startTime" id="startTime" >
                     <p class="error">Wrong format entered</p>
                     <input type="hidden" name="timesheetId">
                  </div>
               </div>
               <div class='col-sm-2 p-r-0'>
                  <label class="col-sm-4 col-form-label" for="end">End</label>
                  <div class="col-sm-8 pull-right">
                     <input class="form-control" class="form-control" type="text" name="endTime" id="endTime">
                     <p class="error">Wrong format entered</p>
                  </div>
               </div>
               <div class='col-sm-2 p-r-0'>
                  <label class="col-sm-4 col-form-label" for="break">Break</label>
                  <div class="col-sm-8 pull-right">
                     <input class="form-control" type="text" name="breakHours" id="breakHours" >
                  </div>
               </div>
               <div class='col-sm-3 p-r-0'>
                  <label class="col-sm-5 col-form-label" for="unit_hrs">Unit Hrs</label>
                  <div class="col-sm-7 pull-right">
                     <input class="form-control" type="text" name="unitHours" id="unitHours" >
                  </div>
               </div>
               <div class='col-sm-3'>
                  <label class="col-sm-5 col-form-label" for="staff_hrs">Staff Hrs</label>
                  <div class="col-sm-7 pull-right p0">
                     <input class="form-control p0" type="text" name="staffHours" id="staffHours" >
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
                     <input class="form-control"type="text" name="ts_No">
                     <p class="error">TS No is required</p>
                  </div>
               </div>
               <div class='col-sm-5'>
                  <label class="col-sm-3 col-form-label" for="ts_Id">TS ID</label>
                  <div class="col-sm-9 pull-right">
                     <input class="form-control"type="text" name="ts_Id" >
                  </div>
               </div>
               <div class='col-sm-1'>
                  <button type="button" class="btn btn-success genrteId">Generate ID</button> 
               </div>
               <div class='col-sm-1'>
                  <button type="button" class="btn btn-warning copyToClipBoard">Copy ID</button> 
               </div>
               <div class='col-sm-1'>
                  <button type="button" class="btn btn-danger pull-right rejectdSms" >Reject SMS</button>
               </div>
            </div>
         </div>
         <div class='col-sm-12'>
            <br>
            <div class='form-group'>
               <label for="comment">Comment:</label>
               <textarea class="form-control" rows="5" id="comments" name="comment"></textarea>
            </div>
         </div>
         <div class="modal-footer">
            <button type="" class="btn btn-success checkInAction " value="submit">CheckIn</button>
         </div>
      </div>
   </div>
</div>
</div>
</div>