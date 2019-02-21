<style type="text/css">
.table tbody td{font-size: 14px;}
.table thead th{font-size: 11px;}
table{width:100%;}
.table thead tr{background: #8cea8f !important;}
.table thead th {text-align: left;padding: 8px;}
.table tbody{margin-bottom: 10px;}
tbody tr td {border-bottom: 1px solid #f4f4f4;padding: 8px;}
</style>
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
        <hr>
        <div class="box-body">
          <div class="row">
            <div class='col-sm-9'>
              <div class="col-md-3" style="float: right; margin-top: 10px;">
              <img src="{{asset('public/images/Nurses_Group_Logo.jpg')}}" width="200">
              <div class="" style="margin-top: -30px;">
                Yeovil Innovation Centre<br>
                Yeovil, BA22 8RN <br>
                Ph: 01935 350366 <br>
                Email: payroll@nursesgroup.co.uk
              </div>
            </div>
              <div class="staffName col-md-6">
                <div class="name">
                  <strong>{{$compTrips[0]->driver->forname}} {{$compTrips[0]->driver->surname}} </strong>
                  <span class="weekNmbr">Week {{$compTrips[0]->payeeWeek}}</span> | 
                  <span class="weekNmbr">Club {{$compTrips[0]->trip->clubId}}</span>  
                </div>
                <div class="adrress1 ">{{$compTrips[0]->driver->address}}</div>
                <div class="niNmbr ">NI No : {{$compTrips[0]->driver->niNumber}}</div>
                <div class="phnMr">Mob : {{$compTrips[0]->driver->mobile}}</div>
                <div class="emlAdrss">{{$compTrips[0]->driver->email}}</div>
                </div>
            </div>
          </div>
        </div>


        <div class="box-body" style="margin-top: 45px;">
                  <hr>
          <div class="row">
            <table border="1" class="table table-bordered">
              <thead>
                <th>Trip ID</th>
                <th>Club ID</th>
                <th>Date</th>
                <th>Staff</th>
                <th>PickUp</th>
                <th>Drop</th>
                <th>Total Miles</th>
                <th>Amount</th>
              </thead>
              <tbody>
                @foreach($compTrips as $trip)
                <tr>
                  <td>{{$trip->trip->tripId}}</td>
                  <td>{{$trip->trip->clubId}}</td>
                  <td>{{date('d-M-Y,D',strtotime($trip->trip->date))}}</td>
                  <td>{{$trip->trip->staff->forname}} {{$trip->trip->staff->surname}}</td>
                  @if($trip->trip->direction ==1)
                    <td>{{$trip->trip->staff->pickupLocation}}</td>
                  @else
                   <td>{{$trip->trip->booking->unit->address}} ({{$trip->trip->booking->unit->alias}})</td>
                  @endif
                  @if($trip->trip->direction ==1)
                    <td>{{$trip->trip->booking->unit->address}} ({{$trip->trip->booking->unit->alias}})</td>
                  @else
                   <td>{{$trip->trip->staff->pickupLocation}}</td>
                  @endif
                  <td>{{number_format($trip->trip->booking->distenceToWorkPlace,2)}}</td>
                  <td>{{number_format(($trip->driver->ratePerMile * $trip->trip->booking->distenceToWorkPlace *2),2)}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="7"><strong>Total</strong></td>
                  <td><strong>{{number_format(($compTrips->sum('trip.booking.distenceToWorkPlace')*2)*$compTrips[0]->driver->ratePerMile,2)}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>


        <div class="box-body" style="font-size: 11px;">
          Total amount shown above consist of weekly pay – ({{$compTrips->sum('weeklyPay')}}), Holiday pay({{$compTrips->sum('holidayPay')}}) and TA ({{$compTrips->sum('grossTa')}})
          and  subjected to Tax, NI and pension deduction. Separate payslip with detailed
          calculation will send out after submitting the report to HMRC.
          <br>
          <br>
          <img src="{{asset('public/images/jss_logo.jpg')}}" width="100px"> JSS Healthcare Ltd, Innovation Centre, Yeovil, BA22 8RN
        </div>
        <hr>
        <div class="box-body" style="font-size: 11px;">
          RA prepared by	( {{Auth::user()->name}} - {{date('Y-m-d H:i:s')}})
        </div>
        <hr>

        <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>