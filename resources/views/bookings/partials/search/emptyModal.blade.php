<div id="emptyModal" class="modal fade" role="dialog" 
 get-availabilty-url="{{route('booking.get.staff.avaiblity')}}"
 get-history-url="{{route('booking.get.staff.history')}}" token="{{csrf_token()}}">
  <div class="modal-dialog">
    <div class="modal-header">
      <ul class="logClass">
        <li class="staffName"></li> |
        <li class="catgry"></li>
      </ul>
      <button type="button" class="close logClose" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-content">
      <div class="modal-body"></div>
    </div>
  </div>
</div>
