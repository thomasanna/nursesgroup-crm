<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <style>
    table{width:100%;}
    .table thead tr{background: #8cea8f !important;}

    .table thead th {text-align: left;padding: 8px;}
    .table tbody{margin-bottom: 10px;font-size: 13px;}
    tbody tr td {padding: 8px;}
    .table-bordered {border: 1px solid #f4f4f4;width: 100%;border-spacing: 0;border-collapse: collapse;}
    .w100{width: 100%;}
    .w50{width: 50%;}
    .pull-left{float: left;}
    .pull-right{float: right !important;}
    .m-t-170{margin-top: 210px;}
    .m-t-10{padding-top: 50px;}
    .p-l-40{padding-left: 40px;}
    .name{padding-bottom: 15px;}
    .name .weekNmbr{font-size: 18px;}
    .text-right{text-align: right;}
    .staffName{margin-top: 30px;font-size: 40px;margin-bottom: 15px;}
    .f-10{font-size: 12px;}
    </style>
  </head>
  <body>
    <div class="row w100">
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary w100">
          <!-- form start -->
            <div class="box-body w100" style="height:250px !important;">
              <div class="row w100">
                <div class="w50 pull-left" >
                  <div class="col-md-8" style="height:200px !important;">
                    <div class="name">
                      <div class="staffName">{{$payments[0]->booking->staff->forname}} {{$payments[0]->booking->staff->surname}} </div>
                      <span class="weekNmbr">Week {{$payments[0]->paymentWeek}}</span>
                      <span class="weekNmbr p-l-40">No: {{$payments[0]->archive->raNumber}}</span>   
                      <span class="weekNmbr p-l-40">Date: {{date('d-M-Y',strtotime($payments[0]->archive->raDate))}}</span>   
                    </div>
                    <div class="adrress1 ">{{$payments[0]->booking->staff->address}}</div>
                    <div class="niNmbr ">NI No : {{$payments[0]->booking->staff->niNumber}}</div>
                    <div class="phnMr">Mob : {{$payments[0]->booking->staff->mobile}}</div>
                    <div class="emlAdrss">{{$payments[0]->booking->staff->email}}</div>
                  </div>
                </div>
                <div class="w50 pull-right text-right">
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
            </div>
            <div class="pull-left" style="height:auto !important;">
              <div class="row">
                <table border="1" class="table table-bordered w100">
                  <thead>
                    <th>Book ID</th>
                    <th>Date</th>
                    <th>Unit</th>
                    <th>Timesheet </th>
                    <th>Description</th>
                    <th class="text-right">Line Total</th>
                  </thead>
                  <tbody>
                    @foreach($payments as $payment)
                    <tr>
                      <td>{{$payment->booking->bookingId}}</td>
                      <td>{{$payment->DateDay}}</td>
                      <td>{{$payment->booking->unit->alias}}</td>
                      <td>{{$payment->timesheetNum}}</td>
                      <td>{{$payment->Desc}}</td>
                      <td class="text-right">£ {{number_format($payment->shiftTotal,2)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="5"><strong>Total</strong></td>
                      <td class="text-right"><strong>£ {{number_format($payments->sum('shiftTotal'),2)}}</strong></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="f-10 box-body" style="line-height: 26px;">
            Timesheets period run from Monday to Sunday every week. Please submit your timesheet to us by 12 noon Monday to be paid on the following Friday to payroll@nursesgroup.co.uk. The rate agreed above including Holiday pay, WTR, NI, Employee Pension, TA and all other benefits as agreed. Separate payslip with detailed calculation will be emailed you shortly.
              <br>
              <br>
              <span class="m-t-10">Thank you very much for working with Nurses Group.</span>

              <div class=" text-right" >
                <img src="{{asset('public/images/jss_logo.jpg')}}" width="100px" style="margin-top:17px;"> 
                <span class="blueFont" style="padding-top:-20px !important;">JSS Healthcare Ltd, Innovation Centre, Yeovil, BA22 8RN</span>
              </div>
            </div>

          
            <hr>

            <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>

  </body>
</html>
