<div id="BookingLogBook" class="modal fade" role="dialog"
 action="{{route('booking.log.entry')}}" get-url="{{route('booking.get.booking.log')}}" token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <div class="modal-header">
      <ul class="logClass">
        <li class="staff">Adian Caventa</li> |
        <li class="date">HCA</li> |
        <li class="categry">HCA</li> |
        <li class="unit">HCA</li> |
        <li class="shift">HCA</li>
      </ul>
      <button type="button" class="close logClose" data-dismiss="modal">&times;</button>
      <div class="logEntry">
        <div class="col-md-12">

          <div class="form-group col-md-10">
            <label for="">Entry</label>
            <input type="text" class="form-control contentData" placeholder="Write about booking here...">
            <input type="hidden" class="form-control bookId">
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
