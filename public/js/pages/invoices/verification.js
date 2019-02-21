$(document).ready(function() {

  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');
  var setCheckStateUrl = $(".table").attr('checkUrl');

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
      if(state.columns[2].search.search!=""){
        $('#searchShift').select2('destroy').val(state.columns[2].search.search).select2({
          placeholder: "Shift",
          allowClear: true,
          width: '100%',
        });
        $("#searchShift").parent().parent().addClass('filterSlected');
      }

      if(state.columns[5].search.search!=""){
        $('#searchCategory').select2('destroy').val(state.columns[5].search.search).select2({
          placeholder: "Category",
          allowClear: true,
          width: '100%',
        });
        $("#searchCategory").parent().parent().addClass('filterSlected');
      }

      if(state.columns[4].search.search!=""){
        $('#searchStaff').select2('destroy').val(state.columns[4].search.search).select2({
          placeholder: "Staff",
          allowClear: true,
          width: '100%',
        });
        $("#searchStaff").parent().parent().addClass('filterSlected');
      }
      if(state.columns[3].search.search!=""){
        $('#searchUnit').select2('destroy').val(state.columns[3].search.search).select2({
          placeholder: "Unit",
          allowClear: true,
          width: '100%',
        });
        $("#searchUnit").parent().parent().addClass('filterSlected');
      }
      if (state.columns[1].search.search != "") {
        var dates = state.columns[2].search.search;
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
    pageLength: 50,
    processing: false,
    serverSide: true,
    bStateSave: true,
    fnStateSave: function (oSettings, oData) {
      localStorage.setItem('UnitBillsTable', JSON.stringify(oData));
    },
    fnStateLoad: function (oSettings) {
      return JSON.parse(localStorage.getItem('UnitBillsTable'));
    },
    ajax: {
      url: dataUrl,
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [
      {
        data: 'booking.bookingId',
        name: 'booking.bookingId',
        orderable: false,
        searchable: false
      },
      {
        data: 'booking.date',
        name: 'booking.date',
        orderable: false
      },
      {
        data: 'booking.shift.name',
        name: 'booking.shift.name',
        orderable: false
      },
      {
        data: 'booking.unit.alias',
        name: 'booking.unit.alias',
        orderable: false
      },
      {
        data: 'booking.staff.name',
        name: 'booking.staff.name',
        orderable: false
      },
      {
        data: 'booking.category.name',
        name: 'booking.category.name',
        orderable: false
      },
      {
        data: 'timesheet.number',
        name: 'timesheet.number',
        orderable: false
      },
      {
        data: 'timesheet.tslog',
        name: 'timesheet.tslog',
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


  $('#searchShift').on('change', function () {
    oTable.column('booking.shift.name:name').search(this.value).draw();
    var elment = $("#searchShift").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });

  $('#searchCategory').on('change', function () {
    oTable.column('booking.category.name:name').search(this.value).draw();
    var elment = $("#searchCategory").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });
  $('#searchStaff').on('change', function () {
    oTable.column('booking.staff.name:name').search(this.value).draw();
    var elment = $("#searchStaff").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });
  $('#searchUnit').on('change', function () {
    oTable.column('booking.unit.alias:name').search(this.value).draw();
    var elment = $("#searchUnit").parent().parent();
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

  $('#searchReset').on('click', function () {

    $(".bgDarkBlue .col-sm-2").removeClass('filterSlected');
    $(".bgDarkBlue .col-sm-1").removeClass('filterSlected');
    $("#searchDate").val(moment().subtract(1, 'days').format('DD-MM-YYYY')+" - "+moment().subtract(1, 'days').format('DD-MM-YYYY'));

    $('#searchShift').select2('destroy').val("").select2({
      placeholder: "Shift",
      allowClear: true,
      width: '100%',
    });

    $('#searchStaff').select2('destroy').val("").select2({
      placeholder: "Staff",
      allowClear: true,
      width: '100%',
    });

    $('#searchUnit').select2('destroy').val("").select2({
      placeholder: "Unit",
      allowClear: true,
      width: '100%',
    });

    $('#searchCategory').select2('destroy').val("").select2({
      placeholder: "Category",
      allowClear: true,
      width: '100%',
    });


    oTable.columns().search('').draw();

  });

  $(document).on('click', '.verify-btn', function (e) {
    $('input[name=verifyInvoiceId]').val($(this).data('invoice'));
    var singleUrl = $("#verifyModal").attr("single");
    var weekNum = $("#verifyModal").attr('weekNum');
    var monthNum = $("#verifyModal").attr('monthNum');
    var token = $("#verifyModal").attr('token');
    $.ajax({
      type: 'POST',
      async: false,
      url: singleUrl,
      data: {
        "_token": token,
        "bookId" : $('#verifyModal input[name=bookingId]').val(),
        "invoiceId": $(this).data('invoice'),
        "weekNum" : weekNum,
        "monthNum" : monthNum
      },
      complete: function () {
        $('#verifyModal').modal('show');
      },
      success: function (response) {
        $("#verifyModal select[name=weekNumbr]").val(weekNum);
        $('#verifyModal .bookId').html("#"+response.data.booking.bookingId);
        $('#verifyModal .bookingId').val(response.data.booking.bookingId);
        $('#verifyModal .bookingDate span').html(response.data.booking.date);
        $('#verifyModal .unitName').html(response.data.booking.unit.alias);
        $('#verifyModal .categoryName span').html(response.data.booking.category.name);
        $('#verifyModal .shiftName').html(response.data.booking.shift.name);
        $('#verifyModal .staffName').html(response.data.booking.staff.forname+" "+response.data.booking.staff.surname);
        $('#verifyModal .breakHours').html(response.data.timesheet.breakHours);

        if( (response.data.weeks == '( Week ) ( Day )') ||  (response.data.weeks == '( Week ) ( Night )')) {
          $('#verifyModal .weekDetails').html(response.data.weeks).removeClass('redFonts');
        } else {
          $('#verifyModal .weekDetails').html(response.data.weeks).addClass('redFonts');
        }

        $('#verifyModal .hidUnitHr').val(response.data.timesheet.unitHours);
        $('#verifyModal .tsLink').val(response.data.timesheet.timesheetRefId);

        $('#verifyModal .startTime').html(moment(response.data.timesheet.startTime,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');
        $('#verifyModal .endTime').html(moment(response.data.timesheet.endTime,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        if(moment(response.data.scheduleStaffHours.startTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.startTime){
          $("#verifyModal .startTime").addClass('redBrdr');
        }

        if(moment(response.data.scheduleStaffHours.endTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.endTime){
          $("#verifyModal .endTime").addClass('redBrdr');
        }

        $('#verifyModal .breakHours').html(response.data.timesheet.breakHours).removeClass('redBrdr');

        $('#verifyModal .unitHours').html((response.data.timesheet.unitHours).toFixed(2));
        if(response.data.scheduleStaffHours.totalHoursUnit != response.data.timesheet.unitHours){
          $("#verifyModal .unitHours").addClass('redBrdr');
        }

        $('#verifyModal .staffHours').html((response.data.timesheet.staffHours).toFixed(2)).removeClass('redBrdr');
        if(response.data.scheduleStaffHours.totalHoursStaff != response.data.timesheet.staffHours){
          $("#verifyModal .staffHours").addClass('redBrdr');
        }


        $('#verifyModal .hRate').val(response.data.booking.unit.hourlyRate);
        $('#verifyModal .enic').val(response.data.booking.unit.enic);
        $('#verifyModal .ta').val(response.data.booking.transportAllowence);
        $('#verifyModal .distenceToWorkPlace').val(response.data.booking.distenceToWorkPlace);

        var lineTotal = parseFloat(response.data.booking.unit.hourlyRate * response.data.timesheet.unitHours) + parseFloat(response.data.timesheet.unitHours * response.data.booking.unit.enic) + parseFloat(response.data.booking.transportAllowence);
        $('#verifyModal .lineTotal').val(lineTotal.toFixed(2));

        // console.log(response.data.weekNumbr);
        $("#verifyModal select[name=invceFrqncy]").val(response.data.booking.unit.invoiceFrequency);
        if(response.data.booking.unit.invoiceFrequency==1){  //Weekly
          $(".frqncyWeek").removeClass('hidden');
          $(".frqncyMnth").addClass('hidden');
        }else{  // Monthly
          $(".frqncyMnth").removeClass('hidden');
          $(".frqncyWeek").addClass('hidden');
        }
        if(response.data.weekNumbr != 0 || response.data.weekNumbr != null) {
          $("#verifyModal select[name=weekNumbr]").val(response.data.weekNumbr);
        } else {
          $("#verifyModal select[name=weekNumbr]").val(weekNum);
        }

        if(response.data.monthNumbr != null) {
          $("#verifyModal select[name=monthNumbr]").val(response.data.monthNumbr);
        } else {
          $("#verifyModal select[name=monthNumbr]").val(monthNum);
        }

        if(response.data.paymentYear != null) {
          $("#verifyModal select[name=paymentYear]").val(response.data.paymentYear);
        } else {
          $("#verifyModal select[name=paymentYear]").val(4);
        }
        $("#verifyModal .remarks").val(response.data.remarks);
        $("#verifyModal .tsCheckedBy").html(response.data.tsCheckedBy);
        $("#verifyModal .tsVrfdBy").html(response.data.pymnetVrfdBy);
        $("#verifyModal .pymnetVrfdBy").html(response.data.pymnetVrfdBy);
      }
    });
  });

  $(document).on('click', '.update-verify', function (e) {
    var actionUrl = $("#verifyModal").attr("action");
    var token = $("#verifyModal").attr('token');
    $.ajax({
      type: 'POST',
      async: false,
      url: actionUrl,
      data: {
        "_token": token,
        "bookId" : $('#verifyModal input[name=bookingId]').val(),
        "invoiceId": $('input[name=verifyInvoiceId]').val(),
        "ta": $('#verifyModal .ta').val(),
        "hRate": $('#verifyModal .hRate').val(),
        "enic": $('#verifyModal .enic').val(),
        "unitDistence": $('#verifyModal .distenceToWorkPlace').val(),
        "invceFrqncy": $('#verifyModal .invceFrqncy').val(),
        "paymentYear": $('#verifyModal .paymentYear').val(),
        "weekNumbr": $('#verifyModal .weekNumbr').val(),
        "monthNumbr": $('#verifyModal .monthNumbr').val(),
        "remarks": $('#verifyModal .remarks').val(),
        'type'  : $(this).attr('method'),
      },
      complete: function () {
        $('#verifyModal').modal('hide');
      },
      success: function (response) {
        oTable.ajax.reload();
        var message = '<span class="alert-msg" style="margin - left: 30px">Sucessfully verified the entry</span>';
        $('#pageTitle').find('span.alert-msg').remove();
        $('#pageTitle').append(message);
      }
    });
  });

  $('#searchShift').select2({
    placeholder: "Shift",
    allowClear: true,
    width: '100%'
  });

  $('#searchCategory').select2({
    placeholder: "Category",
    allowClear: true,
    width: '100%'
  });

  $('#searchStaff').select2({
    placeholder: "Staff",
    allowClear: true,
    width: '100%'
  });

  $('#searchUnit').select2({
    placeholder: "Unit",
    allowClear: true,
    width: '100%'
  });

  $(".copyToClipBoard").click(function(){
    $("input[name=tsLink]").select();
    document.execCommand("copy");
  });

  $('.invceFrqncy').on('change', function (e) {
      var invoiceFrequency = this.value;
      if(invoiceFrequency==1){  //Weekly
          $(".frqncyWeek").removeClass('hidden');
          $(".frqncyMnth").addClass('hidden');
        }else{  // Monthly
          $(".frqncyMnth").removeClass('hidden');
          $(".frqncyWeek").addClass('hidden');
        }

  });

});
