<div id="statusSwitch" class="modal fade" role="dialog" action="{{route('booking.change.status')}}"  single="{{route('booking.get.single')}}"  token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Status</h4>
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
          <div class='col-sm-6'>
            <div class='form-group'>
                <label for="unitStatus">Unit Status</label>
                <input type="hidden" name="unitId">
                <select class="form-control select2" name="unitStatus" id="unitStatus">
                  <option value="1">Temporary</option>
                  <option value="2">Cancelled</option>
                  <option value="3">Unable to Cover</option>
                  <option value="4">Confirmed</option>
                  <option value="5">Booking Error</option>
                </select>
                <input type="hidden" name="bookId">
                <input type="hidden" name="staffStatus">
                <p class="error">Unit is required</p>
            </div>
          </div>
        </div>

        <div class="hidden canceled">

          <div class="row">
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="unitStatus">Requested By</label>
                  <select class="form-control select2" name="cancelRequestedBy">

                  </select>
                  <p class="error">This field is required</p>
                  <input type="hidden" name="bookId">
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="unitStatus">Mode of Cancel</label>
                  <select class="form-control select2" name="modeOfCancelRequest">
                    <option value="">Select an Option</option>
                    <option value="1">Email</option>
                    <option value="2">Phone</option>
                    <option value="3">SMS</option>
                  </select>
                  <p class="error">This field is required</p>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Date</label>
                  <input type="text" class="form-control" name="cancelDate">
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                  <label for="date">Time</label>
                  <input type="text" class="form-control timepicker canceltime" value="" name="ticancelTimeme">
              </div>
            </div>
          </div>
          <div class="row">
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Handled By</label>
                  <input type="text" class="form-control" name="cancelAuthorizedBy" disabled="disabled" value="{{Auth::user()->name}}">
              </div>
            </div>
            <div class='col-sm-4'>
              <div class='form-group'>
                  <label for="date">Cancellation Charge (£)</label>
                  <input type="number" step="0.01"  class="form-control" name="cancelCharge" value="0.00">
              </div>
            </div>
          </div>

          <div class="row">
            <div class='col-sm-12'>
              <div class='form-group'>
                  <label for="unitStatus">Notes</label>
                  <textarea name="cancelExplainedReason" class="form-control" rows="4" cols="80"></textarea>
              </div>
            </div>
          </div>

        </div>

        <div class="row hidden unabledToCover">
          <div class='col-sm-12'>
            <div class='form-group'>
                <label for="unitStatus">Reason</label>
                <select class="form-control select2" name="canceledOrUTCreason">
                  <option value="">Select a Reason</option>
                  <option value="1">Short Notice</option>
                  <option value="2">Not Cost Effective</option>
                  <option value="3">No Trasnport</option>
                  <option value="4">No Staff</option>
                  <option value="5">Managment Request</option>
                </select>
                <p class="error">Unit is required</p>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success updateStatus">Update</button>
      </div>
    </div>

  </div>
</div>
