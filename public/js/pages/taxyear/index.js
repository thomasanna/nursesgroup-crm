$(document).ready(function(){
  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');

  oTable = $('.table').DataTable({
      processing: false,
      serverSide: true,
      ajax: {
          url: dataUrl,
          type: 'post',
          headers: {'X-CSRF-TOKEN': token}
      },
      columns: [
          {data: 'taxYearId', name: 'taxYearId','orderable': false},
          {data: 'taxYearFrom', name: 'taxYearFrom', orderable: false},
          {data: 'taxYearTo', name: 'taxYearTo', orderable: false},
           {data: 'actions', name: 'actions', orderable: false, searchable: false},
          
      ],
  });

  
  $(document).on('click','a.btn.btn-danger.btn-xs.mrs',function(){
    var action = $(this).attr('action');
    var token = $(this).attr('token');
    bootbox.confirm({
        message: "Are you sure want to delete it ?",
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
        callback: function (result) {
          if(result==true){
            $.ajax({
              type:'GET',
              async:false,
              url:action,
              data:{"_token": token},
              success:function(response){
                window.location = response.url;
              }
            });
          }
        }
      });
    });
  });