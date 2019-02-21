<div id="previewSmsSpot" class="modal fade" role="dialog"
action="{{route('booking.send.shift.confirm.sms')}}"
bookid = "{{encrypt($booking->bookingId)}}"
token="{{ csrf_token() }}">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Preview SMS</h4>
        <ul class="smallHdr">
          <li>
            @if($booking->isWeekend)
            <span class='redFont'>{{date('d-M-Y, D',strtotime($booking->date))}}</span>
            @else
            {{date('d-M-Y, D',strtotime($booking->date))}}
            @endif
          </li>
          <li>|&nbsp;{{empty($booking->unit->alias)?$booking->unit->name:$booking->unit->alias}}</li>
          <li>|&nbsp;
            @if($booking->categoryId ==1)
            <span class='redFont'>{{$booking->category->name}}</span>
            @elseif($booking->categoryId ==3)
            <span class='yellowFont'>{{$booking->category->name}}</span>
            @else
            {{$booking->category->name}}
            @endif
          </li>
          <li>|&nbsp;{{$booking->shift->name}}</li>
          <li>|&nbsp;{{date('H:i',strtotime($booking->startTime))}}</li>
          <li>|&nbsp;{{date('H:i',strtotime($booking->endTime))}}</li>
        </ul>
      </div>
      <div class="modal-body">

        <input type="hidden" name="editbookId" id="editbookId">
        <div class="row">
          <div class='col-sm-12'>
            <div class='form-group'>
                <label for="date">To</label>
                <p id="smsStaffs">{{$booking->staff->forname." ".$booking->staff->surname}}</p>
            </div>
          </div>
          <div class='col-sm-12'>
            <div class='form-group'>
                <label for="date">SMS</label>
                <textarea name="spotMessage" class="form-control" rows="8">SHIFT BOOKED, Hi , {{$booking->staff->forname." ".$booking->staff->surname}}, {{date('d-M-Y, D',strtotime($booking->date))}}, {{$booking->unit->name}}, {{$booking->shift->name}} ({{date('H:i',strtotime($booking->startTime))}} to {{date('H:i',strtotime($booking->endTime))}}), Thank you NSG
                </textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success previewSmsSpotAction">Send</button>
      </div>
    </div>
  </div>
</div>
