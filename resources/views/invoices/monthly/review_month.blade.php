@extends('layouts.template')
@section('title','Review Months')
@section('content')
<!-- Header Navigation -->
<div class="box box-default m-t-10">
  <div class="box-body">
    <div class="col-sm-2"><h3 class="m-0"><span class="fontBold">{{$invoices[0]->booking->unit->alias}} </span></h3></div>
    @if(session()->has('message'))<div class="p4 col-sm-2 text-right alert-msg">{{session()->get('message')}}</span></div>@endif
    <div class="pull-left col-sm-5">
        <h3 class="box-title text-center m-0">Review Invoice |  Month {{$monthName}} | {{$taxDefaultYear->taxYearFrom}} - {{$taxDefaultYear->taxYearTo}}</h3>
    </div>

    <div class="col-sm-1 text-right pull-right">
      <a href="{{route('invoices.monthly.list')}}" class="btn btn-warning">
        <i class="fa  fa-chevron-left" aria-hidden="true"></i>
        Back</a>
    </div>
  </div>
</div>
<!-- Main row -->
<div class="row">
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <!-- form start -->
      <form action="" method="post" >
        {!! csrf_field() !!}
        <div class="box-body">
          @foreach($invoices as $invoice)
          <div class='row reviewPayment'>
            <div class='col-sm-1 wdth5 m-l-10'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Book Id</label> @endif
                  <input class="form-control" name="name" type="text" value="{{$invoice->booking->bookingId}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-1 wdth10'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Date</label>@endif
                  <input class="form-control" name="name" type="text" value="{{date('d-m-Y, D',strtotime($invoice->booking->date))}}" readonly="readonly"  />

              </div>
            </div>
            <div class='col-sm-1 wdth10'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Staff</label> @endif
                  <input class="form-control" name="phone" type="text" value="{{$invoice->booking->staff->forname}} {{$invoice->booking->staff->surname}}" readonly="readonly"  />
              </div>
            </div>
            <div class='col-sm-1 wdth5'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Category</label> @endif
                  <input class="form-control" type="text" value="{{$invoice->booking->category->name}}" readonly="readonly"/>
              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Shift</label> @endif
                  <input class="form-control" name="phone" type="text" value="{{$invoice->booking->shift->name}}" readonly="readonly" />

              </div>
            </div>
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Start Time</label>@endif
                  <input class="form-control" name="phone" type="text" value="{{date('H:i',strtotime($invoice->timesheet->startTime))}}" readonly="readonly" />

              </div>
            </div>
            <div class='col-sm-1 wdth5'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">End Time</label> @endif
                  <input class="form-control" name="phone" type="text" value="{{date('H:i',strtotime($invoice->timesheet->endTime))}}" readonly="readonly" />

              </div>
            </div>

            <div class='col-sm-1 wdth5'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Duration</label> @endif
                  <input class="form-control" name="phone" type="text" value="{{number_format($invoice->timesheet->unitHours,2)}}" readonly="readonly" />

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Hourly Rate</label> @endif
                  <input class="form-control rightInput" name="phone" type="text" value="£ {{$invoice->booking->unit->hourlyRate}}" readonly="readonly" />

              </div>
            </div>
            @if($invoices[0]->enic!=0)
            <div class='col-sm-1 wdth5'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">ENIC</label>@endif
                  <input class="form-control rightInput" name="phone" type="text" value="£ {{number_format($invoice->enic,2)}}" readonly="readonly" />

              </div>
            </div>
            @endif
            <div class='col-sm-1 wdth6'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Total TA</label> @endif
                  <input class="form-control rightInput" name="phone" type="text" value="£ {{number_format($invoice->ta,2)}}" readonly="readonly" />

              </div>
            </div>
            <div class='col-sm-1 wdth7'>
              <div class='form-group'>
                  @if($loop->iteration==1)<label for="date">Line Total</label>@endif
                  <input class="form-control rightInput" name="phone" type="text" value="£ {{number_format($invoice->totalLine,2)}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-2 @if ($loop->first) alignSpcl1 @endif'>
              <div class="col-sm-2 wd50px">
                @if($invoice->archived == 0)
                <a href="{{route('invoices.month.move.to.archives',$invoice->invoiceId)}}" class="btn btn-primary">Pay</a>
                @else
                <a href="javascript:void(0)" class="btn btn-success" disabled="disabled">Pay</a>
                @endif
              </div>
              @if($invoice->archived == 0)
              <div class="col-sm-4 wd97px">
                <a href="{{route('invoices.month.move.to.next.month',$invoice->invoiceId)}}" class="btn btn-primary">Next Month</a>
              </div>
              @endif
              <div class="col-sm-4 wd66px">
                <a href="{{route('invoices.month.revert',$invoice->invoiceId)}}" class="btn btn-danger">Revert</a>
              </div>
              <div class="col-sm-2">
                <a class="btn btn-info" data-toggle="tooltip" title="{{$invoice->remarks}}">Comments</a>
              </div>

            </div>
          </div>
          @endforeach
        </div>
        <hr>
        <div class="box-body">
          <div class="row">
            <div class='col-sm-1'>
              <div class='form-group'>
                  <label for="date">No Of RGN </label>
                  <input class="form-control" type="text" value="{{$invoices->where('archived',1)->where('booking.categoryId',1)->count()}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <label for="date">No Of HCA </label>
                  <input class="form-control" type="text" value="{{$invoices->where('archived',1)->where('booking.categoryId',2)->count()}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-1'>
              <div class='form-group'>
                  <label for="date">No Of SHCA </label>
                  <input class="form-control" type="text" value="{{$invoices->where('archived',1)->where('booking.categoryId',3)->count()}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-2 wdth8'>
              <div class='form-group'>
                  <label for="date">Sum of RGN</label>
                  <input class="form-control rightInput" type="text" value="£ {{number_format($invoices->where('archived',1)->where('booking.categoryId',1)->sum('totalLine'),2)}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-2 wdth8'>
              <div class='form-group'>
                  <label for="date">Sum of HCA</label>
                  <input class="form-control rightInput" type="text" value="£ {{number_format($invoices->where('archived',1)->where('booking.categoryId',2)->sum('totalLine'),2)}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-2 wdth8'>
              <div class='form-group'>
                  <label for="date">Sum of SHCA</label>
                  <input class="form-control rightInput" type="text" value="£ {{number_format($invoices->where('archived',1)->where('booking.categoryId',3)->sum('totalLine'),2)}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-1 wdth12'>
              <div class='form-group'>
                  <label for="date">Total TA</label>
                  <input class="form-control rightInput" type="text" value="£ {{number_format($invoices->where('archived',1)->sum('ta'),2)}}" readonly="readonly" />
              </div>
            </div>
            <div class='col-sm-1 wdth12'>
              <div class='form-group'>
                  <label for="date">Total</label>
                  <input class="form-control rightInput" type="text" value="£ {{number_format($invoices->where('archived',1)->sum('totalLine'),2)}}" readonly="readonly" />
              </div>
            </div>

          </div>
        </div>

        <hr>
        <div class="box-body">
          <div class="row">

            <div class='col-sm-2'>
              <div class='form-group'>
                  <label for="date"></label>
              </div>
            </div>

            <div class='col-sm-7'>
              <div class='form-group'>
                  <label for="date"></label>

              </div>
            </div>
            <div class='col-sm-3 p-t-25'>
              <div class='form-group'>
                  <label for="date">&nbsp;</label>
                  @if($invoices->sum('archived') == count($invoices))
                  <a href="{{route('invoices.month.invoice',[$month,$invoice->booking->unitId])}}"
                   class="btn btn-warning pull-right m-l-10">View Invoice</a>
                  @else
                  <a  href="javascript:void(0)" disabled="disabled" class="btn btn-success pull-right m-l-10">Generate Invoice</a>
                  @endif

              </div>
            </div>


          </div>
        </div>
        <!-- /.box-body -->

      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
@endsection
