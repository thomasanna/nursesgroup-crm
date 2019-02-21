$(document).ready(function(){
  var dataUrl = $(".roleTable").attr('fetch');
  var token = $(".roleTable").attr('token');

  oTable = $('.roleTable').DataTable({
      processing: false,
      serverSide: true,
      ajax: {
          url: dataUrl,
          type: 'post',
          headers: {'X-CSRF-TOKEN': token}
      },
      columns: [
          {data: 'id', name: 'id','orderable': false},
          {data: 'name', name: 'name', orderable: false},
          {data: 'guard_name', name: 'guard_name', orderable: false},
           {data: 'actions', name: 'actions', orderable: false, searchable: false},
          
      ],
  });

  
  $(document).on('click','.roleTable a.btn.btn-danger.btn-xs.mrs',function(){
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
                oTable.ajax.reload();
                
              }
            });
          }
        }
      });
    });
  });