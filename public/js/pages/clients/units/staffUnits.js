$(document).ready(function(){
  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');

  oTable = $('.table').DataTable({
      processing: false,
      serverSide: true,
      pageLength: 50,
      ajax: {
          url: dataUrl,
          type: 'post',
          headers: {'X-CSRF-TOKEN': token}
      },
      columns: [
          {data: 'DT_Row_Index', name: 'clientUnitPhoneId','orderable': false},
          {data: 'alias', name: 'alias', orderable: false},
          {data: 'address', name: 'address', orderable: false},
          {data: 'client.name', name: 'client.name', orderable: false},
          {data: 'nameOfManager', name: 'nameOfManager', orderable: false},
          {data: 'email', name: 'email', orderable: false},
		      {data: 'status',  status: 'status', orderable: false},
          {data: 'actions', name: 'actions', orderable: false, searchable: false}
      ],
  });

});
