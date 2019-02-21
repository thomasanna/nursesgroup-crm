<div id="timesheetLogBook" class="modal fade "  role="dialog"
action="{{route('timesheet.log.entry')}}" get-url="{{route('booking.get.booking.log')}}" token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <div class="modal-header">
      <ul class="logClass">
         <li class="bookingId"><li >| </li>
        
          <li class="name"></li><li >| </li>
           <li class="category"><li >| </li>
             <li class="shift"><li >| </li>
              <li class="unit">
      </ul>
      <button type="button" class="close logClose" data-dismiss="modal">&times;</button>
      <!-- <div class="logEntry">
        <div class="col-md-12">

          <div class="form-group col-md-7">
            <label for="">Entry</label>
            <input type="text" class="form-control contentData" placeholder="Write about staff here...">
            <input type="hidden" class="form-control timesheetId">
          </div>

          <div class="form-group col-md-2">
            <button type="button" class="btn btn-primary newLogEntryAction">Log Now</button>
          </div>
        </div>
      </div> -->
    </div>
    <div class="modal-content">
      <div class="modal-body"></div>
    </div>
  </div>
</div>
