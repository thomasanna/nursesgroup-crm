<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Trip Id</th>
      <th scope="col">Direction</th>
      <th scope="col">Staff</th>
      <th scope="col">Pick Up</th>
      <th scope="col">Drop Off</th>
      <th scope="col">PickUp Time</th>
      <th scope="col">Order</th>
    </tr>
  </thead>
  <tbody>
    @foreach($trips as $trip)
    <tr>
      <th>{{$trip->tripId}}</th>
      <td>@if($trip->direction==1) OutBound @else InBound @endif</td>
      <td>{{$trip->staff->forname}} {{$trip->staff->surname}}</td>
      @if($trip->direction ==1)
        <td>{{$trip->staff->pickupLocation}}</td>
      @else
       <td>{{$trip->booking->unit->address}}</td>
      @endif

      @if($trip->direction ==1)
        <td>{{$trip->booking->unit->address}}</td>
      @else
       <td>{{$trip->staff->pickupLocation}}</td>
      @endif

      <td>{{$trip->pickupTime}}</td>
      <td>{{$trip->order}}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="box-body">
  <div class="row">
    <div class='col-sm-2'>
      <div class='form-group'>
          <label for="date">Total Miles</label>
          <input class="form-control" disabled="disabled"  type="text" value="{{$trips->sum('booking.distenceToWorkPlace')}}" />
      </div>
    </div>
    <div class='col-sm-2'>
      <div class='form-group'>
          <label for="date">Rate per Mile</label>
          <input class="form-control" disabled="disabled" type="text" value="{{$trips[0]->driver->ratePerMile}}" />
      </div>
    </div>
    <div class='col-sm-2'>
      <div class='form-group'>
          <label for="date">Amount</label>
          <input class="form-control" disabled="disabled" type="text" value="{{($trips[0]->driver->ratePerMile*$trips->sum('booking.distenceToWorkPlace'))*2}}" />
      </div>
    </div>
    <div class='col-sm-2'>
      <div class='form-group'>
          <label for="date">Aggreed Amount</label>
          <input class="form-control" name="aggreedAmount" disabled="disabled" type="text" value="{{number_format($trip->aggreedAmount,2)}}" />
      </div>
    </div>
  </div>
</div>

