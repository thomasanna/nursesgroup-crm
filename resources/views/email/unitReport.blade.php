
<html>
    <head>
      <style>
        .mbtm{margin-bottom: 20px;}
        table{width:100%;}
        .table thead tr{background: #8cea8f !important;}

        .table thead th {text-align: left;padding: 8px;}
        .table tbody{margin-bottom: 10px;}
        tbody tr td {border-bottom: 1px solid #f4f4f4;padding: 8px;}
      </style>
    </head>
    <body>
      <div class="mbtm">Dear {{$contact->fullName}},<br></div>
      <div class="mbtm">I have enclosed a list of staff details of the followings shifts that you have required through us.<br><br>
      If any queries please do not hesitate to contact us.<br></div>

      <div class="mbtm">Thank you for your continued support</div>
      <div class="mbtm">
        <div class="onlineMark"><strong>{{Auth::guard('admin')->user()->name}}</strong></div>
        <div class="onlineMark"><strong>Client Manager - {{$contact->unit->name}}</strong></div>
      </div>

      <div class="row mbtm">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">

            <div class="box-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>#ID</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Shift</th>
                    <th>Category</th>
                    <th>Shift Status</th>
                    <th>Staff</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($bookings as $booking)
                  @if($booking->unitStatus!= 5 && $booking->unitStatus!= 3)
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
                          @elseif($booking->unitStatus==2)
                            <strong>Cancelled </strong>on {{date('d-m-Y',strtotime($booking->cancelDate))}}
                          @endif
                        </td>
                    </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>
      <div class="mbtm">
        <div class="onlineMark"><strong>Nurses Group</strong></div>
        <div class="onlineMark">Yeovil Innovation Centre</div>
        <div class="onlineMark">Barracks Close, Yeovil, UK BA22 8RN</div>
        <div class="onlineMark">Phone: <strong>01935 315031</strong></div>
        <div class="onlineMark">Email: <a href="mailto:office@nursesgroup.co.uk">office@nursesgroup.co.uk</a></div>
        <div class="onlineMark">Web: <a href="www.nursesgroup.co.uk">www.nursesgroup.co.uk</a></div>
        <div class="onlineMark"><img src="{{asset('public/images/logo-mail.png')}}"/> </div>
      </div>
    </body>
</html>
