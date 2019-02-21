$(document).ready(function() {

  $("select.select2[name=switchProgress]").select2({
    allowClear: true
  });

  $(document).on('change', 'select.select2[name=switchProgress]', function() {
    var action = $(this).attr('action');
    var token = $(this).attr('token');
    var driver = $(this).attr('driver');
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
              "page": 1, // for Drivers
              "driver": driver,
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
  var driver = $(".table").attr('driver');
  console.log(driver);

  oTable = $('.table').DataTable({
    processing: false,
    serverSide: true,
    ajax: {
      url: dataUrl,
      type: 'post',
      data: {
        "driver": driver
      },
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [{
        data: 'DT_Row_Index',
        name: 'applicantTrainingId',
        'orderable': false
      },
      {
        data: 'number',
        name: 'number',
        orderable: false
      },
      {
        data: 'dateOfIssue',
        name: 'dateOfIssue',
        orderable: false
      },
      {
        data: 'dateOfExpiry',
        name: 'dateOfExpiry',
        orderable: false
      },
      {
        data: 'validFrom',
        name: 'validFrom',
        orderable: false
      },
      {
        data: 'validTo',
        name: 'validTo',
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