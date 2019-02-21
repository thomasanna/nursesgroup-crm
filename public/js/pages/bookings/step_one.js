$(document).ready(function() {



  $('[name=requestedTime]').timepicker({
    timeFormat: 'HH:mm',
    interval: 60,
    minTime: '08',
    maxTime: '8:00pm',
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });

  function loadSelect2() {
    $("select.select2[name=unitId]").select2({
      placeholder: "Select a Unit",
      allowClear: true
    });


    $("select.select2[name=modeOfRequest]").select2({
      placeholder: "Select Mode of Request",
      allowClear: true
    });

    $("select.select2[name^=shiftId]").select2({
      placeholder: "Select a Shift",
      allowClear: true
    });

    $("select.select2[name^=categoryId]").select2({
      placeholder: "Select a Category",
      allowClear: true
    });

    $("select.select2[name^=numbers]").select2({
      placeholder: "Select a Number",
      allowClear: true
    });

    var adminId = $(".smallHdr").attr('adminId');

    switch(adminId){
      case '21':
        var default_date = new Date(2018, 0, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '22':
        var default_date = new Date(2018, 1, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '23':
        var default_date = new Date(2018, 2, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '24':
        var default_date = new Date(2018, 3, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '25':
        var default_date = new Date(2018, 4, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '26':
        var default_date = new Date(2018, 5, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '27':
        var default_date = new Date(2018, 6, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '28':
        var default_date = new Date(2018, 7, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      case '29':
        var default_date = new Date(2018, 8, 1);
        $(".datepicker").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        $("[name=requestedDate]").datepicker({
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        }).datepicker('setDate',default_date);
        break;

      default:
        $(".datepicker").datepicker({
          changeMonth: true,
          changeYear: true,
          numberOfMonths: 3,
          dateFormat: 'dd-mm-yy',
          firstDay: 1
        });
        $("[name=requestedDate]").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd-mm-yy',
          // minDate: 0,
          firstDay: 1
        });
    }

  }

  $("select.select2[name=unitId]").change(function(){
      var unitId = $(this).val();
      var action = $(this).attr('action');
      var token = $(this).attr('token');

      $.ajax({
        type: 'POST',
        async: false,
        url: action,
        data: {
          "_token": token,
          "unit":unitId
        },
        beforeSend: function() {
          $('.loading,.modelPophldr').show();
        },
        success: function(response) {
          $(".reqBy").html(response);
          $("select.select2[name=requestedBy]").select2({
            placeholder: "Select a Contact",
            allowClear: true
          });
        },
        complete: function() {
          $('.loading,.modelPophldr').hide();
        }
      });
    });

  loadSelect2();

  $("#back_btn").on("click", function() {


    bootbox.confirm({
      message: "Are you sure want to proceed the back action ?",
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
          location.href = $("#back_btn").data("url");
        }
      }
    });

  });

  $(".newRow").click(function() {
    var action = $(this).attr('action');
    var token = $(this).attr('token');

    $.ajax({
      type: 'GET',
      async: false,
      url: action,
      data: {
        "_token": token
      },
      beforeSend: function() {
        $('.loading,.modelPophldr').show();
      },
      success: function(response) {
        $(".newBookingForm").append(response);
        var adminId = $(".smallHdr").attr('adminId');

        switch(adminId){
          case '21':
            var default_date = new Date(2018, 0, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          case '22':
            var default_date = new Date(2018, 1, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);
            break;

          case '23':
            var default_date = new Date(2018, 2, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          case '24':
            var default_date = new Date(2018, 3, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          case '25':
            var default_date = new Date(2018, 4, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          case '26':
            var default_date = new Date(2018, 5, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          case '27':
            var default_date = new Date(2018, 6, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          case '28':
            var default_date = new Date(2018, 7, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          case '29':
            var default_date = new Date(2018, 8, 1);
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            }).datepicker('setDate',default_date);

            break;

          default:
            $(".newBookingForm .row:last-child()").find(".datepicker").datepicker({
              changeMonth: true,
              changeYear: true,
              numberOfMonths: 3,
              dateFormat: 'dd-mm-yy',
              firstDay: 1
            });
        }
        $("select.select2[name=unitId]").select2({
          placeholder: "Select a Unit",
          allowClear: true
        });


        $("select.select2[name=modeOfRequest]").select2({
          placeholder: "Select Mode of Request",
          allowClear: true
        });

        $("select.select2[name^=shiftId]").select2({
          placeholder: "Select a Shift",
          allowClear: true
        });

        $("select.select2[name^=categoryId]").select2({
          placeholder: "Select a Category",
          allowClear: true
        });

        $("select.select2[name^=numbers]").select2({
          placeholder: "Select a Number",
          allowClear: true
        });
        //loadSelect2();
      },
      complete: function() {
        $('.loading,.modelPophldr').hide();
      }
    });
  });

  $(document).on('click','.deleteRow',function(){
    $(this).closest('.row').remove();
  });

  $("input[type=submit]").click(function(){
    $(".error").hide();
    var unit = $("select.select2[name=unitId]").val();
    var modeOfReq = $("select.select2[name=modeOfRequest]").val();
    var reqBy = $("select.select2[name=requestedBy]").val();
    var requestedDate = $("input[name=requestedDate]").val();
    var requestedTime = $("input[name=requestedTime]").val();
    var date = $("input[name='date[]']").val();
    var shift = $("select.select2[name='shiftId[]']").val();
    var categoryId = $("select.select2[name='categoryId[]']").val();

    if(unit==""){
      $("select.select2[name=unitId]").next().next().show();
      return false;
    }
    else if(modeOfReq==""){
      $("select.select2[name=modeOfRequest]").next().next().show();
      return false;
    }
    else if(reqBy==""){
      $("select.select2[name=requestedBy]").next().next().show();
      return false;
    }
    else if(date==""){
      $("input[name='date[]']").next().show();
      return false;
    }
    else if(shift==""){
      $("select.select2[name='shiftId[]']").next().next().show();
      return false;
    }
    else if(categoryId==""){
      $("select.select2[name='categoryId[]']").next().next().show();
      return false;
    }
    else{
      return true;
    }


  });
});
