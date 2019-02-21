<div id="unitLogBook" class="modal fade "  role="dialog"
action="{{route('client_units.log.entry')}}" get-url="{{route('client_units.get.unit.log')}}" token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <div class="modal-header">
      <ul class="logClass">
        <li class="name"></li>|
        <li class="phone"></li>
      </ul>
      <button type="button" class="close logClose" data-dismiss="modal">&times;</button>
      <div class="logEntry">
        <div class="col-md-12">

          <div class="form-group col-md-7">
            <label for="">Entry</label>
            <input type="text" class="form-control contentData" placeholder="Write about staff here...">
            <input type="hidden" class="form-control clientUnitId">
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
