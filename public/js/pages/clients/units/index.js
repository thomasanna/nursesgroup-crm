$(document).ready(function(){
  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');

  oTable = $('.table').DataTable({
      processing: false,
      pageLength: 50,
      serverSide: true,
      ajax: {
          url: dataUrl,
          type: 'post',
          headers: {'X-CSRF-TOKEN': token}
      },
      columns: [
          {data: 'DT_Row_Index', name: 'clientUnitId','orderable': false},
          {data: 'name', name: 'name', orderable: false},
          {data: 'client.name', name: 'client.name', orderable: false},
          {data: 'nameOfManager', name: 'nameOfManager', orderable: false},
          {data: 'contact.phone',  email: 'contact.phone', orderable: false},
		      {data: 'status',  status: 'status', orderable: false},
          {data: 'Utlog',  name: 'Utlog', orderable: false,searchable: false},
          {data: 'actions', name: 'actions', orderable: false, searchable: false}
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

  $(document).on("click", ".openLogModal", function(e) {

        $("#unitLogBook").modal("show");
        var token = $("#unitLogBook").attr("token");
        var clientUnitId = $(this).attr("clientunitid");
        var action = $("#unitLogBook").attr("get-url");
        var name = $(this).attr('name');
        var phone = $(this).attr('phone');

        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                clientUnitId: clientUnitId
            },
            success: function(response) {
                $("#unitLogBook .modal-body").html(response);
                $("#unitLogBook .modal-header .name").html(name);
                $("#unitLogBook .modal-header .phone").html(phone);

            }
        });
        $("#unitLogBook .clientUnitId").val(clientUnitId);
  });

  $("#unitLogBook .newLogEntryAction").click(function(){
    var token = $("#unitLogBook").attr("token");
    var action = $("#unitLogBook").attr("action");
    var unitId = $("#unitLogBook .clientUnitId").val();
    var contentData = $("#unitLogBook .contentData").val();

    $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                unitId: unitId,
                content: contentData
            },
            success: function(response) {
                $("#unitLogBook .modal-body").html(response);
                $(".contentData").val("").focus();
            }
        });
        oTable.draw();
  });

});
