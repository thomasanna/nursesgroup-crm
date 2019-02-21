<!-- Modal -->
<div id="setBudgetModal" class="modal fade" role="dialog" action="{{route('unit.area.budget.set.action')}}" token="{{ csrf_token() }}">
  <div class="modal-dialog" style="width:50%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set Budget</h4>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="setBudgetForm">
              <div class="box box-primary">
                 {!! csrf_field() !!}
                 <div class="box-body newBookingForm">
                  @for($i=0;$i< 4;$i++)
                    <div class="row shiftRow">
                       <div class='col-sm-3'>
                          <div class='form-group'>
                             <label for="date">{{$monthArray[$i]}}</label>
                          </div>
                       </div>
                       <div class='col-sm-3'>
                          <div class='form-group'>
                                 <input class="form-control budget{{$i}}" name="budget{{$i}}" value="{{$budget[$i]}}" type="text" />
                                 <input class="form-control month{{$i}}" name="month{{$i}}" value="{{$monthArray[$i]}}" type="hidden" />
                          </div>
                       </div>
                    </div>
                  @endfor

                  </div>
               </div>
            </form>
            <div class="errmsg" style="color:red; display:none">Please fill all fields</div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
         <button type="button" class="btn btn-success add-month-budget">Save</button>
      </div>
    </div>

  </div>
</div>
