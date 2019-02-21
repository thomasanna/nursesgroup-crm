<ul class="timeline">
  @for($i=0;$i<count($logs);$i++)
  <li><i class="fa fa-user bg-blue"></i>
    <div class="timeline-item">
      <span class="time"><i class="fa fa-clock-o"></i> {{date('d M. Y H:i:s',strtotime($logs[$i]->created_at))}}</span>
      <h3 class="timeline-header no-border @if($logs[$i]->type==2) redBg @endif"><a href="javascript:void(0)">{{$logs[$i]->admin->name}}</a> {!!$logs[$i]->content!!}</h3>
    </div>
  </li>
  @endfor
</ul>
