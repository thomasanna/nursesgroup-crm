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
          {data: 'DT_Row_Index', name: 'adminId','orderable': false},
          {data: 'name', name: 'name', orderable: false},
          {data: 'email', name: 'email', orderable: false},
          {data: 'type', name: 'type', orderable: false},
          {data: 'status',  status: 'status', orderable: false},
          {data: 'user_log',  name: 'user_log', orderable: false,searchable: false},
          {data: 'created_at',  created_at: 'created_at', orderable: false},
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



  $(document).on("click", ".openLogModal", function() {

        $("#userLogBook").modal("show");
        var token = $("#userLogBook").attr("token");
        var adminId = $(this).attr("adminid");      
        var action = $("#userLogBook").attr("get-url");
        var name = $(this).attr('name');
        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                adminId: adminId
            },
            success: function(response) {
                $("#userLogBook .modal-body").html(response);
                $("#userLogBook .modal-header .name").html(name);
            }
        });
        $("#userLogBook .adminId").val(adminId);

  });
    $("#userLogBook .newLogEntryAction").click(function(){
        var token = $("#userLogBook").attr("token");
        var action = $("#userLogBook").attr("action");
        var adminId = $("#userLogBook .adminId").val();
        var contentData = $("#userLogBook .contentData").val();

        $.ajax({
                type: "POST",
                async: false,
                url: action,
                data: {
                    _token: token,
                    adminId: adminId,
                    content: contentData
                },
                success: function(response) {
                    $("#userLogBook .modal-body").html(response);
                    $(".contentData").val("").focus();
                }
            });
            oTable.draw();
      });

    $(document).ready(function(){
 
  $("select.select2.multiple").select2({
    placeholder: "Select Roles",
    allowClear: true
  });

})
  
});
