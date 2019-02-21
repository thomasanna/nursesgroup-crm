<table class="table">
    <thead>
    <tr>
        <th>Date</th>
        <th>Shift</th>
        <th>Home</th>
        <th>Category</th>
        <th>Staff</th>
        <th>Duration</th>
        <th>Home(Hourly Rate)</th>
        <th>TA</th>
        <th>Line Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($bookingList as $booking)
        <tr>
        <td>{{date('d-M-Y, D',strtotime($booking->date))}}</td>
        <td>{{$booking->shift->name}}</td>
        <td>{{$booking->unit->name}}</td>
        <td>{{$booking->category->name}}</td>
        <td>{{$booking->staff->forname}}</td>
        <td></td>
        <td></td>
        <td>{{$booking->transportAllowence}}</td>
        <td></td>
        </tr>
    @endforeach
    </tbody>
</table>