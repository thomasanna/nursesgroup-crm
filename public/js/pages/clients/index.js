$(document).ready(function() {
  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');

  oTable = $('.table').DataTable({
    processing: false,
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
        name: 'clientId',
        'orderable': false
      },
      {
        data: 'name',
        name: 'name',
        orderable: false
      },
      {
        data: 'personInCharge',
        name: 'personInCharge',
        orderable: false
      },
      {
        data: 'landlineNumber',
        name: 'landlineNumber',
        orderable: false
      },
      {
        data: 'email',
        email: 'email',
        orderable: false
      },
      {
        data: 'status',
        status: 'status',
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

  $(document).on('click', 'a.btn.btn-danger.btn-xs.mrs', function() {
    var action = $(this).attr('action');
    var token = $(this).attr('token');
    bootbox.confirm({
      message: "Are you sure want to delet it ?",
      buttons: {
        confirm: {
          label: 'Yes',
          className: 'btn-success'
        },
        cancel: {
          label: 'Cancel',
          className: 'btn-danger'
        }
      },
      callback: function(result) {
        if (result == true) {
          $.ajax({
            type: 'GET',
            async: false,
            url: action,
            data: {
              "_token": token
            },
            success: function(response) {
              window.location = response.url;
            }
          });
        }
      }
    });
  });
});
