@foreach($bookings as $booking)
  @if($booking->unitStatus!= 5 && $booking->unitStatus != 3)
  <tr>
    <td>{{$booking->bookingId}}</td>
    <td>{{$booking->unit->alias}}</td>
    <td>{{date('d-M-Y, D',strtotime($booking->date))}}</td>
    <td>{{$booking->shift->name}}</td>
    @if($booking->categoryId==1)
    <td><strong>{{$booking->category->name}}</strong></td>
    @else
    <td>{{$booking->category->name}}</td>
    @endif
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
      @elseif($booking->unitStatus==2)
        <strong>Cancelled </strong>on {{date('d-m-Y',strtotime($booking->cancelDate))}}
      @endif
    </td>
  </tr>
  @endif
@endforeach