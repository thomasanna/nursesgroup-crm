
@foreach($calenderData as $key=>$week)
<table class="table able-striped table-bordered table-hover">
    <thead>
        <tr>
            <th colspan="8" class="text-center @if($key==3) bg-purple @endif">
                {{$week['label']}}
            </th>
        </tr>
        <tr>
            <th class="text-center">{{$week['day1']['date']}}<br>Monday</th>
            <th class="text-center">{{$week['day2']['date']}}<br>Tuesday</th>
            <th class="text-center">{{$week['day3']['date']}}<br>Wednessday</th>
            <th class="text-center">{{$week['day4']['date']}}<br>Thursday</th>
            <th class="text-center">{{$week['day5']['date']}}<br>Friday</th>
            <th class="text-center">{{$week['day6']['date']}}<br>Saturday</th>
            <th class="text-center">{{$week['day7']['date']}}<br>Sunday</th>
            <th class="text-center {{$week['hour']['color']}}">H/W</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">{{$week['day1']['status']}}</td>
            <td class="text-center">{{$week['day2']['status']}}</td>
            <td class="text-center">{{$week['day3']['status']}}</td>
            <td class="text-center">{{$week['day4']['status']}}</td>
            <td class="text-center">{{$week['day5']['status']}}</td>
            <td class="text-center">{{$week['day6']['status']}}</td>
            <td class="text-center">{{$week['day7']['status']}}</td>
            <td class="text-center">{{$week['hour']['vale']}}</td>
        </tr>
    </tbody>
</table>
@endforeach