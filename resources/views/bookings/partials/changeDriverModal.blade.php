<div id="statusSwitch" class="modal fade" role="dialog"   single="{{route('booking.get.single')}}"  token="{{ csrf_token() }}">
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
                <label for="driverId">Driver</label>
                <select class="form-control select2" name="driverId">
                  
                </select>
                <input type="hidden" name="bookId">
            </div>
          </div>

          <div class='col-sm-12'>
            <div class='form-group'>
                <label for="clubId">ClubId</label>
                <select class="form-control select2" name="clubId">
                  
                </select>
            </div>
          </div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success updateTrip">Update</button>
      </div>
    </div>

  </div>
</div>