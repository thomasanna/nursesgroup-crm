 <div class="col-md-6 bordrNice">
         <div class="row col-md-12">
            <div class="col-md-4">
               <div class="date">{{$month}}</div>
               <div class="m-t">
                  <span class="m-r-20">Number of HCA</span>
                  <span>:</span>
                  <span class="firstHcaCount">{{array_sum($firstMonthBookHCAConfirmed)}}</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20">Number of RGN</span>
                  <span>:</span>
                  <span class="firstRgnCount">{{array_sum($firstMonthBookRGNConfirmed)}}</span>
               </div>
            </div>
            <div class="col-md-8 pull-right">
               <div class="m-t">
                  <span class="m-r-20">Budget Amount</span>
                  <span>:</span>
                  @if($firstMonthBudgetAmount == "")
                  <span class="pull-right"><button class="btn btn-sm cstBtn" data-toggle="modal" data-target="#setBudgetModal">Set Budget</button></span>
                  @else
                  <span class="pull-right amt">£ {{$firstMonthBudgetAmount}}</span>
                  @endif

               </div>
               <div class="m-t">
                  <span class="m-r-20 p-l-20">Total Amount of HCA</span>
                  <span>:</span>
                  <span class="pull-right amt amt-sm secondHcaCount hcaamt">£ 1200.00</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20 p-l-20">Total Amount of RGN</span>
                  <span>:</span>
                  <span class="pull-right amt amt-sm secondRgnCount rgnamt">£ 800.00</span>
               </div>
               <div class="m-t">
                  <span class="m-r-20">Total</span>
                  <span>:</span>
                  <span class="pull-right amt totalamt">£ 2000.00</span>
               </div>
            </div>
         </div>
         <div class="col-md-12" id="container" action="{{route('unit.area.graph.current.month.bookings')}}" token="{{ csrf_token() }}" style="min-width: 310px; height: 300px; margin: 0 auto">
         </div>
      </div>
