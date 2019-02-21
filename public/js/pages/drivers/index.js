$(document).ready(function() {
  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');

  oTable = $('.table').DataTable({
    pageLength: 50,
    serverSide: true,
    ajax: {
      url: dataUrl,
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [{
        data: 'DT_Row_Index',
        name: 'applicantId',
        'orderable': false
      },
      {
        data: 'forname',
        name: 'forname',
        orderable: false
      },
      {
        data: 'surname',
        name: 'surname',
        orderable: false
      },
      {
        data: 'email',
        name: 'email',
        orderable: false
      },
      {
        data: 'mobile',
        name: 'mobile',
        orderable: false
      },
      {
        data: 'branch.name',
        name: 'branch.name',
        orderable: false
      },
      {
        data: 'actions',
        name: 'actions',
        orderable: false,
        searchable: false
      }
    ],
  });

});