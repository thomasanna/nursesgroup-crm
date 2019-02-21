<ul class="timeline">
  @for($i=0;$i<count($logs);$i++)
  <li>
    @if($logs[$i]->priority ==1)<i class="fa fa-user bg-yellow"></i>@endif
    @if($logs[$i]->priority ==2)<i class="fa fa-user bg-red"></i>@endif
    @if($logs[$i]->priority ==3)<i class="fa fa-user bg-blue"></i>@endif
    <div class="timeline-item">
      <span class="time"><i class="fa fa-clock-o"></i> {{date('d M. Y H:i:s',strtotime($logs[$i]->created_at))}}</span>
      <h3 class="timeline-header no-border"><a href="javascript:void(0)">{{$logs[$i]->admin->name}}</a> {{$logs[$i]->content}}</h3>
    </div>
  </li>
  @endfor
</ul>
