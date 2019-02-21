<style>

.table-bordered {
  border: 1px solid #f4f4f4;
  width: 100%;
    margin-left: 14px;
    border-spacing: 0;
    border-collapse: collapse;
}
</style>
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
        <hr>
        <div class="box-body">
          <div class="row">
            <div class='col-sm-6'>
              <div class="staffName col-md-8">
                <div class="name">
                  {{$invoices[0]->booking->staff->forname}} {{$invoices[0]->booking->staff->surname}} 
                  <span class="weekNmbr p-l-70">Week {{$invoices[0]->paymentWeek}}</span>  
                </div>
                <div class="adrress1 ">{{$invoices[0]->booking->staff->address}}</div>
                <div class="niNmbr ">NI No : {{$invoices[0]->booking->staff->niNumber}}</div>
                <div class="phnMr">Mob : {{$invoices[0]->booking->staff->mobile}}</div>
                <div class="emlAdrss">{{$invoices[0]->booking->staff->email}}</div>
              </div>
            </div>
            <div class="col-md-3">
              <img src="{{asset('public/images/Nurses_Group_Logo.jpg')}}" width="200">
            </div>
            <div class='col-sm-3'>
              Yeovil Innovation Centre<br>
              Yeovil, BA22 8RN <br>
              Ph: 01935 350366 <br>
              Email: payroll@nursesgroup.co.uk
            </div>
          </div>
        </div>
        <hr>
        <div class="box-body">
          <div class="row">
            <table border="1" class="table table-bordered w97">
              <thead>
                <th>Book ID</th>
                <th>Date</th>
                <th>Unit</th>
                <th>Description</th>
                <th>Line Total</th>
              </thead>
              <tbody>
                @foreach($invoices as $invoice)
                <tr>
                  <td>{{$invoice->booking->bookingId}}</td>
                  <td>{{$invoice->DateDay}}</td>
                  <td>{{$invoice->booking->unit->alias}}</td>
                  <td>{{$invoice->Desc}}</td>
                  <td>£ {{number_format($invoice->lineTotal,2)}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="4"><strong>Total</strong></td>
                  <td><strong>£ {{number_format($invoices->sum('lineTotal'),2)}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>


        <div class="box-body">
          Timesheets period run from Monday to Sunday every week. Please submit your timesheet to us by 12 noon Monday to be paid on the following Friday to payroll@nursesgroup.co.uk. The rate agreed above including Holiday pay, WTR, NI, Employee Pension, TA and all other benefits as agreed. Separate payslip with detailed calculation will be emailed you shortly.
          <br>
          Thank you very much for working with Nurses Group.
          <br>
        </div>
        <hr>

        <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
