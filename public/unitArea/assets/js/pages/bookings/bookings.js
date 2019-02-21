$(document).ready(function(){
  var setCheckStateUrl = $(".table").attr('checkUrl');

  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');

  $('#searchDate').daterangepicker({
    startDate: moment().subtract(1, 'days'),
    endDate: moment().subtract(1, 'days'),
    locale: {
      format: 'DD-MM-YYYY',
      firstDay: 1
    }
  }).trigger('change');

  $(document).on( 'init.dt', function ( e, settings ) {
    var api = new $.fn.dataTable.Api( settings );
    var state = api.state.loaded();

    if (state) {
      if(state.columns[4].search.search!=""){
        $('#searchShift').select2('destroy').val(state.columns[4].search.search).select2({
          placeholder: "Shift",
          allowClear: true,
          width: '100%',
        });
        $("#searchShift").parent().parent().addClass('filterSlected');
      }

      if(state.columns[2].search.search!=""){
        $('#searchCategory').select2('destroy').val(state.columns[2].search.search).select2({
          placeholder: "Category",
          allowClear: true,
          width: '100%',
        });
        $("#searchCategory").parent().parent().addClass('filterSlected');
      }

      if (state.columns[1].search.search != "") {
        var dates = state.columns[1].search.search;
        var res = dates.split(" - ");
        $("#searchDate").val(dates).trigger("change");
        $("#searchDate").parent().parent().addClass("filterSlected");
        var inputDate = new Date(state.columns[1].search.search);
        // Get today's date
        var todaysDate = new Date();
        var tomorrowDate = new Date(todaysDate.getFullYear(),todaysDate.getMonth(),todaysDate.getDate() + 1);

        // call setHours to take the time out of the comparison
        if (inputDate.setHours(0, 0, 0, 0) ==todaysDate.setHours(0, 0, 0, 0)) {
            $("#todaySearch").addClass("btn-warning");
        }
      }

    }
  });

  oTable = $('.table').DataTable({
      processing: false,
      serverSide: true,
      ajax: {
          url: dataUrl,
          type: 'post',
          headers: {'X-CSRF-TOKEN': token}
      },
      columns: [
          {data: 'DT_Row_Index', name: 'bookingId','orderable': false},
          {data: 'booking.date', name: 'booking.date', orderable: false},
          {data: 'category.name', name: 'category.name', orderable: false},
          {data: "staff.forname",name: "staff.forname", orderable: false},
          {data: 'shift.name', name: 'shift.name', orderable: false},
          {data: 'actions', name: 'actions', orderable: false, searchable: false},
      ],
  });

  $('#searchShift').on('change', function () {
    oTable.column('shift.name:name').search(this.value).draw();
    var elment = $("#searchShift").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });

  $('#searchCategory').on('change', function () {
    oTable.column('category.name:name').search(this.value).draw();
    var elment = $("#searchCategory").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });

  $('#searchDate').on('keyup change', function () {
    $("#searchDate").parent().parent().addClass('filterSlected');
    oTable.columns('booking.date:name').search(this.value).draw();
  });
  
  $('.categorySel2').select2({
    placeholder: "Category",
    allowClear: true,
    width: '100%'
  });

  $('.shiftSel2').select2({
    placeholder: "Shift",
    allowClear: true,
    width: '100%'
  });


//RESET
  $('#searchReset').on('click', function () {

    $(".bgDarkBlue .col-sm-2").removeClass('filterSlected');
    $(".bgDarkBlue .col-sm-1").removeClass('filterSlected');

    $('#searchCategory').select2('destroy').val("").select2({
      placeholder: "Category",
      allowClear: true,
      width: '100%',
    });

    $('#searchShift').select2('destroy').val("").select2({
      placeholder: "Shift",
      allowClear: true,
      width: '100%',
    });

    $('#searchDate').daterangepicker({
        startDate: moment().subtract(1, 'days'),
        endDate: moment().subtract(1, 'days'),
        locale: {
          format: 'DD-MM-YYYY',
          firstDay: 1
        }
    }).trigger('change');
    
    oTable.columns().search('').draw();

  }); // ENDS
  
  $(document).on('click', '.add-new-booking', function (e) {
    var actionUrl = $("#newBookingModal").attr("action");
    var token = $("#newBookingModal").attr('token');
    if($('input[name=requestedDate]').val() == ""){
      $('.errmsg').css('display','block');
      return false;
    }
    else if($("#newBookingModal select[name=shiftId]").val() == "" ){
      $("#newBookingModal select[name=ts_No]").focus().next().next().show();
      $('.errmsg').css('display','block');
      return false;
    } else if($("#newBookingModal select[name=categoryId]").val() == "" ) {
      $("#newBookingModal select[name=categoryId]").focus().next().next().show();
      $('.errmsg').css('display','block');
      return false;
    } else{
      $('.errmsg').css('display','none');
      $.ajax({
      type: 'POST',
      async: false,
      url: actionUrl,
      data: {
        "_token": token,
        "unitId": $('#newBookingModal input[name=unitId]').val(),
        "requestedDate"  :$('input[name=requestedDate]').val(),
        "shiftId"  :$('select[name=shiftId]').val(),
        "categoryId"  :$('select[name=categoryId]').val(),
        "numbers"  :$("select[name='numbers[]']").val(),
      },
      complete: function () {
        $('#newBookingModal').modal('hide');
      },
      success: function (response) {
        oTable.ajax.reload();
        
      }
    });
  }
  });


  $('.requestedDate').datepicker({
    startDate:new Date(),
    locale: {
      format: 'DD-MM-YYYY'
    }
  });
});