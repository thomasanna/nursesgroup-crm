<!-- Modal -->
<div id="newBookingModal" class="modal fade" role="dialog" action="{{route('unit.area.booking.new.action')}}" token="{{ csrf_token() }}">
  <div class="modal-dialog" style="width:50%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Booking</h4>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="newBookingForm">
              <div class="box box-primary">
                 {!! csrf_field() !!}
                 <div class="box-body newBookingForm">
                    <div class="row shiftRow">
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label for="date">Date</label>
                                 <input class="form-control datepicker requestedDate" name="requestedDate" value="{{date('d-M-Y')}}" type="text" />
                             <p class="error">Date is required</p>
                          </div>
                       </div>
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label for="date">Shift</label>
                             <select class="form-control shiftSel2" name="shiftId">
                                <option value=""></option>
                                <!--         @foreach($shifts as $shift) -->
                                <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
                                <!--            @endforeach -->
                             </select>
                             <p class="error">Shift is required</p>
                          </div>
                       </div>
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label for="date">Staff Category</label>
                             <select class="form-control categorySel2" name="categoryId">
                                <option value=""></option>
                                @foreach($categories as $category)
                                <option value="{{$category->categoryId}}">{{$category->name}}</option>
                                @endforeach
                             </select>
                             <p class="error">Staff Category is required</p>
                          </div>
                       </div>
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label>Number of Booking</label>
                             <select class="form-control select2" name="numbers[]" id="selectNumber">
                                @for($i=1;$i < 11;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                             </select>
                          </div>
                        </div>
                      </div>
                    </div>
              </div>   
            </form>
            <div class="errmsg" style="color:red; display:none">Please fill all fields</div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
         <button type="button" class="btn btn-success add-new-booking">Save</button>
      </div>
    </div>

  </div>
</div>
