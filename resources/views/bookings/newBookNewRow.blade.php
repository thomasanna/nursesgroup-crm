<div class="row">
  <div class='col-sm-2'>
    <div class='form-group'>
        <input class="form-control datepicker" name="date[]" type="text" placeholder="Select a date" autocomplete="off" />
        <p class="error">Type is required</p>
    </div>
  </div>
  <div class='col-sm-2'>
    <div class='form-group'>
        <select class="form-control select2" name="shiftId[]">
          <option value=""></option>
          @foreach($shifts as $shift)
          <option value="{{$shift->shiftId}}">{{$shift->name}}</option>
          @endforeach
        </select>
        <p class="error">Type is required</p>
    </div>
  </div>
  <div class='col-sm-2'>
    <div class='form-group'>
        <select class="form-control select2" name="categoryId[]">
          <option value=""></option>
          @foreach($categories as $category)
          <option value="{{$category->categoryId}}">{{$category->name}}</option>
          @endforeach
        </select>
        <p class="error">Type is required</p>
    </div>
  </div>
  <div class='col-sm-2'>
    <div class='form-group'>
        <select class="form-control select2" name="numbers[]">
          @for($i=1;$i < 11;$i++)
          <option value="{{$i}}">{{$i}}</option>
          @endfor
        </select>
        <p class="error">Email is required</p>
    </div>
  </div>
  <div class='col-sm-3'>
    <div class='form-group'>
        <textarea class="form-control" rows="1" name="importantNotes[]" type="text" /></textarea>
    </div>
  </div>
  <div class='col-sm-1'><a href="javascript:void(0)" class="btn btn-danger deleteRow">X</a></div>
</div>
