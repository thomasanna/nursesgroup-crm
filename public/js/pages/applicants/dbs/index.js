$(document).ready(function() {

  $("select.select2[name=switchProgress]").select2({
    allowClear: true
  });

  $(document).on('change', 'select.select2[name=switchProgress]', function() {
    var action = $(this).attr('action');
    var token = $(this).attr('token');
    var applicant = $(this).attr('applicant');
    var progress = $(this).val();

    bootbox.confirm({
      message: "Are you sure want to change the progress ?",
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
            type: 'POST',
            async: false,
            url: action,
            data: {
              "page": 3, // for reference
              "applicant": applicant,
              "progress": progress,
              "_token": token
            },
            success: function(response) {
              location.reload();
            }
          });
        }
      }
    });
  });


  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');
  var applicant = $(".table").attr('applicant');

  oTable = $('.table').DataTable({
    processing: false,
    serverSide: true,
    ajax: {
      url: dataUrl,
      type: 'post',
      data: {
        "applicant": applicant
      },
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [{
        data: 'DT_Row_Index',
        name: 'applicantDbsId',
        'orderable': false
      },
      {
        data: 'dbsType',
        name: 'dbsType',
        orderable: false
      },
      {
        data: 'number',
        name: 'validDbsNumber',
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
