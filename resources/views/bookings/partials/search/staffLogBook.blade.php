<div id="staffLogBook" class="modal fade" role="dialog"
action="{{route('booking.staff.log.entry')}}" get-url="{{route('booking.get.staff.log')}}" token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <div class="modal-header">
      <ul class="logClass">
        <li class="staffName">Adian Caventa</li> |
        <li class="catgry">HCA</li>
      </ul>
      <button type="button" class="close logClose" data-dismiss="modal">&times;</button>
      <div class="logEntry">
        <div class="col-md-12">

          <div class="form-group col-md-7">
            <label for="">Entry</label>
            <input type="email" class="form-control contentData" placeholder="Write about staff here...">
            <input type="hidden" class="form-control staffId">
          </div>
          <div class="form-group col-md-3">
            <label for="">Priority</label>
            <select class="form-control priority">
              <option value="3">Info</option>
              <option value="1">FollowUp</option>
              <option value="2">Urgent</option>
            </select>
          </div>
          <div class="form-group col-md-2">
            <button type="button" class="btn btn-primary newLogEntryAction">Log Now</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-content">
      <div class="modal-body"></div>
    </div>
  </div>
</div>
