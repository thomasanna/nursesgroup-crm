<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <style type="text/css" media="all">
    table{width:100%;}
    .table thead tr{background: #8cea8f !important;}

    .table thead th {text-align: left;padding: 8px;}
    .table tbody{margin-bottom: 10px;}
    tbody tr td {padding: 8px;font-size: 12px;}
    .table-bordered {
      border: 1px solid #f4f4f4;
      width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }
    .w100{width: 100%;}
    .w50{width: 50%;}
    .pull-left{float: left;}
    .text-center{text-align: center;}
    .pull-right{float: right;}
    .m-t-170{margin-top: 170px;}
    .m-t-10{padding-top: 50px;}
    .name{font-size: 40px;margin-bottom: 10px;}
    .name .weekNmbr{font-size: 25px;}
    .text-right{text-align: right;}
    .fontClass {
  font-size: small !important;
}
.fontBold {
  font-weight: bold !important;
}
.bgColor {
  background-color: #808000 !important;
}
.f-w-700 {
  font-weight: 700 !important;
}
.lineTotalFont {
  font-size: 22px !important;
  font-weight: bold !important;
}
.orangeFont {
  color: #f39c12 !important;
}
.blueFont {
  color: #3c8dbc !important;
}
.fontSize {
  font-size: 18px !important;
}
.bgBlue {
  background-color: #3c8dbc !important;
  height:35px;
}
.bgOrange {
  height:35px;
  background-color: #f39c12 !important;
}
.centerInput {
    font-size: 15px;
    font-weight: bold;
}
.rightInput {
    text-align: right;
    font-size: 16px;
    font-weight: bold;
}
.col-md-7 {width:66.66666667% !important;}
.col-md-5 {width:33.33333333% !important;}
.m-r-50 { padding-left: 100px !important; }
.m-l-130 { margin-left: 130px !important; }
.fontRightSize {float:right;font-size: 13px !important;}
</style>
  </head>
  <body>
    <div class="row">
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
          <!-- form start -->
          <div class="box-body">
            <div class="row w100">
                
            <div class="w100">
              <div class="col-md-3">
                <img src="{{asset('public/images/Nurses_Group_Logo.jpg')}}" width="200">
              </div>
              <div class="col-md-6 text-right"><h2>INVOICE</h2></div>
              <div class="col-md-3"></div>
            </div>
            <div class="w100 pull-left" style="width: 50%;">
              <div class="m-t-170" style="margin-top: 30px !important;">To:</div>
              <div class="">
                <div class="niNmbr"><strong>{{$invoices[0]->booking->unit->name}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->booking->unit->address}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->booking->unit->email}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->clients->name}}</strong></div>
                <div class="phnMr"><strong>{{$invoices[0]->clients->businessAddress}}</strong></div>
              </div>
            </div>
            <div class='w50 pull-right' style="width: 50%;">
              <div class="col-md-10 fontRightSize">
                <div><h4><strong>Correspondence Address:</strong></h4></div>
                <div class="niNmbr ">Nurses Group, Yeovil Innovation Centre,</div>
                <div class="phnMr">Barracks Close, Yeovil, Somerset, BA22 8RN</div>
                <div class="phnMr"><strong>Phone: </strong> 01935315031</div>
                <div class="emlAdrss"><strong>Email: </strong> accounts@nursesgroup.co.uk</div>
                <div class="emlAdrss"><strong>Web: </strong>www.nursesgroup.co.uk</div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="box-body m-t-170">
          <div class="row">
            <table border="1" class="table table-bordered w97">
              <thead>
                <th class="text-center">Date</th>
                <th class="text-center">Work Place</th>
                <th class="text-center">Invoice Period</th>
                <th class="text-center">Invoice No</th>
                <th class="text-center">Grand Total</th>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">{{date('d-m-Y',strtotime($invoices[0]->invoiceDate))}}</td>
                  <td class="text-center">{{$invoices[0]->booking->unit->name}}, {{$invoices[0]->booking->unit->businessAddress}}</td>
                  <td class="text-center">{{$invoices[0]['periodDates']}}</td>
                  <td class="text-center">{{$invoices[0]->invoiceNumber}}</td>
                  <td class="text-center">£ {{number_format($invoices->sum('lineTotal'),2)}}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="row m-t-10">
            <table border="1" class="table table-bordered w97">
              <thead>
                <th>Work ID</th>
                <th>Staff</th>
                <th>Staff Category</th>
                <th class="text-center">Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Hours Worked</th>
                <th>Hourly Rate</th>
                @if($invoices[0]->enic!=0) <th>ENIC 13.8% of staff cost</th> @endif
                <th>Travel Expenses</th>
                <th>Line Total</th>
              </thead>
              <tbody>
                @foreach($invoices as $invoice)
                <tr>
                  <td>{{$invoice->booking->bookingId}}</td>
                  <td>{{$invoice->booking->staff->forname}} {{$invoice->booking->staff->surname}}</td>
                  <td>{{$invoice->booking->category->name}}</td>
                  <td>{{$invoice['DateDay']}}</td>
                  <td>{{date('H:i',strtotime($invoice->timesheet->startTime))}}</td>
                  <td>{{date('H:i',strtotime($invoice->timesheet->endTime))}}</td>
                  <td>{{number_format($invoice->timesheet->unitHours,2)}}</td>
                  <td class="text-right">£ {{number_format($invoice->hourlyRate,2)}}</td>
                  @if($invoice->enic!=0) <td class="text-right">£ {{number_format($invoice->enic,2)}}</td>@endif
                  <td class="text-right">£ {{number_format($invoice->ta,2)}}</td>
                  <td class="text-right">£ {{number_format($invoice->lineTotal,2)}}</td>
                </tr>
                @endforeach
                <tr>
                  <td @if($invoices[0]->enic!=0) colspan="10" @else colspan="9" @endif class="rightInput"><strong>Total</strong></td>
                  <td class="rightInput"><strong>£ {{number_format($invoices->sum('lineTotal'),2)}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>


          <div class="box-body" style="line-height: 26px;">
            Full payment due in 28 days upon receipt of the invoice
            <br>
            Please make all payments to: JSS HEALTHCARE LTD, Sort Code: 30-95-89, Account Number: 51526968 <br>
            Reference: Use Invoice Number
            <br>
            <div class="text-center centerInput"><strong>Thank you very much for choosing 
              <span class="orangeFont">Nurses</span><span class="blueFont">Group</span></strong>
            </div>
            <div class="col-sm-7 blueFont m-t-10" style="padding-top: 10px !important;">
            JSS Healthcare Ltd, Yeovil Innovation Centre,Barracks Close, Yeovil, Somerset, BA22 8RN, Reg No: 09846338
            </div>
            <div class="col-sm-5 text-right" style="padding-top: 10px !important;">
              <img src="{{asset('public/images/jss_logo.jpg')}}" width="100px"> 
              <span class="blueFont" style="">JSS Healthcare Ltd. </span>
            </div>
          </div>

          <div class="col-sm-12" style="line-height: 26px;">
            <div class="col-sm-7 pull-left bgOrange" style="width:66% !important;"></div>
            <div class="col-sm-5 pull-right bgBlue" style="width: 34% !important;"></div>
          </div>

      </div>
            <!-- <hr> -->

            <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>

  </body>
</html>
