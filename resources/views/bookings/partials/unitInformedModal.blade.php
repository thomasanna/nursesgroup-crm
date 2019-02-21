<div id="unitInformedModal" class="modal fade" role="dialog" action="{{route('booking.save.unit.inform.log')}}"
single="{{route('booking.get.single')}}"  token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Inform Unit</h4>
        <ul class="smallHdr">
          <ul class="smallHdr">
          <li class="bookingDate"><span class=""></span></li>
          <li>|&nbsp;</li>
          <li class="unitName"></li>
          <li>|&nbsp;</li>
          <li class="categoryName"><span class=""></span></li>
          <li>|&nbsp;</li>
          <li class="shiftName"></li>
          <li>|&nbsp;</li>
          <li class="startTime"></li>
          <li>|&nbsp;</li>
          <li class="endTime"></li>
        </ul>
        </ul>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class='col-sm-3'>
            <div class='form-group'>
                <label for="unitStatus">Informed To</label>
                <select class="form-control select2" name="informedTo">

                </select>
                <p class="error">Select an option</p>
                <input type="hidden" name="bookId">
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
                <label for="unitStatus">Mode of Inform</label>
                <select class="form-control select2" name="modeOfInform">
                  <option value="">Select an Option</option>
                  <option value="1">Email</option>
                  <option value="2">Phone</option>
                  <option value="3">SMS</option>
                </select>
                <p class="error">Select an option</p>
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
                <label for="date">Date</label>
                <input type="text" class="form-control" name="informedDate">
                <p class="error">Date is required</p>
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
                <label for="date">Time</label>
                <input type="text" class="form-control timepicker canceltime" value="" name="time">
                <p class="error">Time is required</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class='col-sm-12'>
            <div class='form-group'>
                <label for="unitStatus">Notes</label>
                <textarea name="notes" class="form-control" rows="4" cols="80"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success saveUnitInform">Save</button>
      </div>
    </div>

  </div>
</div>
