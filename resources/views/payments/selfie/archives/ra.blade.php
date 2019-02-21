<style>

.table-bordered {
  border: 1px solid #f4f4f4;
  width: 98%;
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
              <div class="staffName col-md-6 p-l-0">{{$payments[0]->booking->staff->forname}} {{$payments[0]->booking->staff->surname}}</div>
              <div class="weekNmbr col-md-6">Week {{$payments[0]->paymentWeek}}</div>
              <div class="adrress1">{{$payments[0]->booking->staff->address}}</div>
              <div class="niNmbr">NI No : {{$payments[0]->booking->staff->niNumber}}</div>
              <div class="phnMr">Mob : {{$payments[0]->booking->staff->mobile}}</div>
              <div class="emlAdrss">{{$payments[0]->booking->staff->email}}</div>
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
                @foreach($payments as $payment)
                <tr>
                  <td>{{$payment->booking->bookingId}}</td>
                  <td>{{$payment->DateDay}}</td>
                  <td>{{$payment->booking->unit->alias}}</td>
                  <td>{{$payment->Desc}}</td>
                  <td>£ {{number_format($payment->shiftTotal,2)}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="4"><strong>Total</strong></td>
                  <td><strong>£ {{number_format($payments->sum('shiftTotal'),2)}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>


        <div class="box-body">
          Total amount shown above consist of weekly pay – ( ), Holiday pay( ) and TA ( )
          and  subjected to Tax, NI and pension deduction. Separate payslip with detailed
          calculation will send out after submitting the report to HMRC.
          <br>
        </div>
        <hr>
        <div class="box-body">
          <div class="row">
              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Payment Date</label>
                    <input class="form-control" disabled="disabled" type="text"
                    value="{{date('d-M-Y',strtotime($payments[0]->archive->paymentDate))}}" autocomplete="off">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="date">Bank</label>
                  <select name="bankId" class="form-control" disabled="disabled">
                    <option value="1" @if($payments[0]->archive->bankId == 1) selected="selected" @endif >Natwest</option>
                    <option value="2" @if($payments[0]->archive->bankId == 2) selected="selected" @else selected="selected" @endif>Santader</option>
                    <option value="3" @if($payments[0]->archive->bankId == 3) selected="selected" @endif >Cash</option>
                    <option value="4" @if($payments[0]->archive->bankId == 4) selected="selected" @endif >Cheque</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Transaction Number</label>
                    <input class="form-control" disabled="disabled" value="{{$payments[0]->archive->transactionNumber}}" type="text"  autocomplete="off">
                </div>
              </div>

              <div class="col-sm-2 m-l-10">
                <div class="form-group">
                    <label for="date">Handled By</label>
                    <input type="text" class="form-control" disabled="disabled" value="{{$payments[0]->archive->handled->name}}">
                </div>
              </div>
          </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
