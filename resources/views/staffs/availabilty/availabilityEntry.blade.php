
@foreach($calenderData as $day=>$dayData)
<div class="col-lg-1 col-md-3 col-sm-4 col-xs-6 currDay">
    <!-- iCheck -->
    <div class="box @if($dayData['date']->isWeekend()) box-danger @else box-success @endif">
        <div class="box-header">
        <h3 class="box-title">{{$dayData['date']->format('d-M-Y')}}</h3>
        <h3 class="box-title">({{$dayData['date']->format('l')}})</h3>
        </div>
        <div class="box-body">
        <!-- Minimal style -->

        <!-- checkbox -->
        <div class="form-group">
            <label>
              E
            <input type="checkbox" class="minimal early @if($dayData['isPrevNight']) firstDisabled @endif" data-type="early"  data-day="{{$day}}"
                    @if($dayData['e']['c']) checked @endif @if($dayData['e']['d']) disabled @endif value="1">
            </label>
            <label>L
            <input type="checkbox" class="minimal late" data-type="late"  data-day="{{$day}}"
                    @if($dayData['l']['c']) checked @endif @if($dayData['l']['d']) disabled @endif value="1">
            </label>
            <label>N
            <input type="checkbox" class="minimal night @if($dayData['isNextEarly']) lastDisabled @endif" data-type="night"  data-day="{{$day}}"
                    @if($dayData['n']['c']) checked @endif @if($dayData['n']['d']) disabled @endif value="1">
            </label>
            <label>A           
            <input type="checkbox" class="minimal absent"  data-type="absent"  data-day="{{$day}}"
                    @if($dayData['a']['c']) checked @endif @if($dayData['a']['d']) disabled @endif value="1">
            </label>
            <label>B           
            <input type="checkbox" class="minimal both"  data-type="both"  data-day="{{$day}}"
                    @if($dayData['a']['c']) checked @endif @if($dayData['a']['d']) disabled @endif value="1">
            </label>
        </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>
@endforeach
