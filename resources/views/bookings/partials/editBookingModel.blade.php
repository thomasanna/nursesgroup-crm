<div id="editBookingModal" class="modal fade" role="dialog" action="{{route('booking.update.edit')}}"  single="{{route('booking.get.single')}}"  token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit</h4>
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
      </div>
      <div class="modal-body">

        <input type="hidden" name="editbookId" id="editbookId">
        <div class="row">
          <div class='col-sm-6'>
            <div class='form-group'>
                <label for="date">Shift</label>
                <select class="form-control select2" name="shiftId">
                  @foreach($shifts as $shift)
                  <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
                  @endforeach
                </select>
            </div>
          </div>
          <div class='col-sm-6'>
            <div class='form-group'>
                <label for="date">Important Notes</label>
                <textarea name="importNotes" class="form-control" rows="4"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success updateEdit">Update</button>
      </div>
    </div>
  </div>
</div>
