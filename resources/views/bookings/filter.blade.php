<div class="row bgDarkBlue">
      <div class='col-sm-2'>
        <div class='form-group'>
            <label for="date">Date</label>
            <input class="form-control datepicker" id="searchDate" type="text" placeholder="Date" autocomplete="off" />
            <p class="error">Date is required</p>
        </div>
      </div>
      <div class='col-sm-1'>
        <div class='form-group'>
            <label for="date">Shift</label>
            <select class="form-control select2" id="searchShift">
              <option value=""></option>
              @foreach($shifts as $shift)
              <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
              @endforeach
            </select>
            <p class="error">Shift is required</p>
        </div>
      </div>
      
      <div class='col-sm-2'>
        <div class='form-group'>
            <label for="date">Unit</label>
            <select class="form-control select2" id="searchUnit">
              <option value=""></option>
              <option value="res">Except Reserved</option>
              @foreach($units as $unit)
              <option value="{{$unit->clientUnitId}}">{{ $unit->alias or $unit->name }}</option>
              @endforeach
            </select>
            <p class="error">Unit is required</p>
        </div>
      </div>
      <div class='col-sm-1'>
        <div class='form-group'>
            <label for="date"> Category</label>
            <select class="form-control select2" id="searchCategory">
              <option value=""></option>
              @foreach($categories as $category)
              <option value="{{$category->categoryId}}">{{$category->name}}</option>
              @endforeach
            </select>
            <p class="error">Category is required</p>
        </div>
      </div>
      

      <div class='col-sm-2'>
        <div class='form-group'>
            <label for="date">Staff</label>
            <select class="form-control select2" id="searchStaff">
              <option value=""></option>
              @foreach($staffs as $staff)
              <option value="{{$staff->staffId}}">{{$staff->forname." ".$staff->surname}}</option>
              @endforeach
            </select>
            <p class="error">Staff is required</p>
        </div>
      </div>
      <div class='col-sm-1'>
        <div class='form-group'>
            <label for="date">Unit Status</label>
            <select class="form-control select2" id="searchUnitStatus">
              <option value=""></option>
              <option value="4">Confirmed</option>
              <option value="1">Temporary</option>
              <option value="3">Unable to Cover</option>
              <option value="2">Cancelled</option>
              <option value="5">Booking Error</option>
            </select>
            <p class="error">Status is required</p>
        </div>
      </div>
      <div class='col-sm-1'>
        <div class='form-group'>
            <label for="date">Staff Status</label>
            <select class="form-control select2" id="searchStaffStatus">
              <option value=""></option>
              <option value="1">New</option>
              <option value="2">Informed</option>
              <option value="3">Confirmed</option>
              <option value="5">Dummy</option>
              <option value="4">Search Closed</option>
            </select>
            <p class="error">Status is required</p>
        </div>
      </div>
      <div class='col-sm-1'>
        <div class='form-group'>
            <label for="date">Transport</label>
            <select class="form-control select2" id="modeOfTransport">
              <option value=""></option>
              <option value="1">Self</option>
              <option value="2">Required</option>
            </select>
            <p class="error">Status is required</p>
        </div>
      </div>
      <div class='col-sm-1'>
        <div class='form-group'>
            <a href="javascript:void(0)" class="btn btn-warning" style="margin-top:25px;" id="searchReset">Reset</a>
        </div>
      </div>
  </div>
