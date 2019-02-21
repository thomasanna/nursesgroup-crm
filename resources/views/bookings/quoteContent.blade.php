<thead>
  <tr>
    <th>#ID</th>
    <th>Unit</th>
    <th>Date</th>
    <th>Shift</th>
    <th>Category</th>
    <th>Shift Status</th>
    <th>Staff</th>
    @if(Auth::guard('admin')->user()->type==1)
    <th>Start Time</th>
    <th>End Time</th>
    <th>Hours Worked</th>
    <th>Hourly Rate</th>
    <th>Transport</th>
    <th>Line Total</th>
    @endif
  </tr>
</thead>
<tbody>
  @foreach($bookingList as $booking)
    <tr>
      <td>{{$booking->bookingId}}</td>
      <td>{{$booking->unit->alias}}</td>
      <td>{{date('d-M-Y, D',strtotime($booking->date))}}</td>
      <td>{{$booking->shift->name}}</td>
      <td>{{$booking->category->name}}</td>
      <td>
        @switch($booking->unitStatus)
          @case(1) {{'Temporary'}} @break
          @case(2) {{'Cancelled'}} @break
          @case(3) {{'Unable to Cover'}} @break
          @case(4) {{'Confirmed'}} @break
          @case(5) {{'Booking Error'}} @break
        @endswitch</td>
      <td>
        @if($booking->staffId != 0)
          {{$booking->staff->forname}} {{$booking->staff->surname}}
        @else
          Searching Now
        @endif
      </td>
      @if(Auth::guard('admin')->user()->type==1)
      <td>{{date('H:i',strtotime($booking->startTime))}}</td>
      <td>{{date('H:i',strtotime($booking->endTime))}}</td>
      <td>{{$booking->totalHoursUnit}}</td>
      <td>{{$booking->hourlyRate}}</td>
      <td>{{number_format(round($booking->ta,2),2)}}</td>
      <td>{{round($booking->lineTotal,2)}}</td>
      @endif
    </tr>
  @endforeach
</tbody>