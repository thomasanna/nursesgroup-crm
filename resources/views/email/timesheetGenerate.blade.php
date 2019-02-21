<h3>TimeSheet Generated {{date('d-m-Y H:i A')}}</h3>

<ul>
  @foreach($bookId as $id)
  <li>#{{$id}}</li>
  @endforeach
</ul>
