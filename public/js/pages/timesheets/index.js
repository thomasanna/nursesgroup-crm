$(document).ready(function() {

  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');
  var setCheckStateUrl = $(".table").attr('checkUrl');

  $('#searchStaffStatus').select2({
    placeholder: "Search Staff Status",
    allowClear: true,
    width: '100%'
  });

  $('#searchCategory').select2({
    placeholder: "Category",
    allowClear: true,
    width: '100%'
  });

  $('#searchShift').select2({
    placeholder: "Shift",
    allowClear: true,
    width: '100%'
  });

  $('#tsStatus').select2({
    placeholder: "TS Status",
    allowClear: true,
    width: '100%'
  });

  $('#searchStaff').select2({
    placeholder: "Search Staff",
    allowClear: true,
    width: '100%'
  });

  $('#searchUnit').select2({
    placeholder: "Search Unit",
    allowClear: true,
    width: '100%',
    dropdownCssClass: "bigdrop"
  });

  $('#searchDate').daterangepicker({
    startDate: moment().subtract(1, 'days'),
    endDate: moment().subtract(1, 'days'),
    locale: {
      format: 'DD-MM-YYYY',
      firstDay: 1
    }
  }).trigger('change');

  oTable = $('.table').DataTable({
    pageLength: 50,
    processing: false,
    serverSide: true,
    bStateSave: true,
    fnStateSave: function (oSettings, oData) {
      localStorage.setItem('timesheetTable', JSON.stringify(oData));
    },
    fnStateLoad: function (oSettings) {
      return JSON.parse(localStorage.getItem('timesheetTable'));
    },
    ajax: {
      url: dataUrl,
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [{
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
        data: 'booking.unit.name',
        name: 'booking.unit.name',
        orderable: false
      },
      {
        data: 'booking.staff.forname',
        name: 'booking.staff.forname',
        orderable: false
      },
      {
        data: 'booking.category.name',
        name: 'booking.category.name',
        orderable: false
      },
      {
        data: 'number',
        name: 'number',
        orderable: false,
      },
      {
        data: 'status',
        name: 'status',
        orderable: false
      },
      {
        data: 'tslog',
        name: 'tslog',
        orderable: false,
        searchable: false
      },
      {
        data: 'actions',
        name: 'actions',
        orderable: false,
        searchable: false
      }
    ]
  });


  $(document).on( 'init.dt', function ( e, settings ) {
    var api = new $.fn.dataTable.Api( settings );
    var state = api.state.loaded();

    if (state) {

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

      if(state.columns[2].search.search!=""){
        $('#searchShift').select2('destroy').val(state.columns[2].search.search).select2({
          placeholder: "Shift",
          allowClear: true,
          width: '100%',
          dropdownCssClass: "bigdrop"
        });
        $("#searchShift").parent().parent().addClass('filterSlected');
      }

      if(state.columns[3].search.search!=""){
        $('#searchUnit').select2('destroy').val(state.columns[3].search.search).select2({
          placeholder: "Unit",
          allowClear: true,
          width: '100%',
          dropdownCssClass: "bigdrop"
        });
        $("#searchUnit").parent().parent().addClass('filterSlected');
      }

      if(state.columns[4].search.search!=""){
        $('#searchStaff').select2('destroy').val(state.columns[4].search.search).select2({
          placeholder: "Shift",
          allowClear: true,
          width: '100%',
          dropdownCssClass: "bigdrop"
        });
        $("#searchStaff").parent().parent().addClass('filterSlected');
      }

      if(state.columns[5].search.search!=""){
        $('#searchCategory').select2('destroy').val(state.columns[5].search.search).select2({
          placeholder: "Category",
          allowClear: true,
          width: '100%',
          dropdownCssClass: "bigdrop"
        });
        $("#searchCategory").parent().parent().addClass('filterSlected');
      }

      if(state.columns[7].search.search!=""){
        $('#tsStatus').select2('destroy').val(state.columns[7].search.search).select2({
          placeholder: "TS Status",
          allowClear: true,
          width: '100%',
          dropdownCssClass: "bigdrop"
        });
        $("#tsStatus").parent().parent().addClass('filterSlected');
      }
    }
  });

  $('#searchDate').on('keyup change', function () {
    $("#searchDate").parent().parent().addClass('filterSlected');
    oTable.columns('booking.date:name').search(this.value).draw();
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

  $('#searchUnit').on('change', function () {
    oTable.column('booking.unit.name:name').search(this.value).draw();
    var elment = $("#searchUnit").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });


  $('#searchStaff').on('change', function () {
    oTable.column('booking.staff.forname:name').search(this.value).draw();
    var elment = $("#searchStaff").parent().parent();
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

  $('#tsStatus').on('change', function () {
    oTable.column('status:name').search(this.value).draw();
    var elment = $("#tsStatus").parent().parent();
        elment.addClass('filterSlected');
    });

  // RESET

  $('#searchReset').on('click', function () {

    $(".bgDarkBlue .col-sm-2").removeClass('filterSlected');
    $(".bgDarkBlue .col-sm-1").removeClass('filterSlected');
    bookingDate = moment(new Date()).format('YYYY-MM-DD');
    $("#searchDate").val(moment().subtract(1, 'days').format('DD-MM-YYYY')+" - "+moment().subtract(1, 'days').format('DD-MM-YYYY'));

    $('#searchShift').select2('destroy').val("").select2({
      placeholder: "Shift",
      allowClear: true,
      width: '100%',
      dropdownCssClass: "bigdrop"
    });

    $('#searchUnit').select2('destroy').val("").select2({
      placeholder: "Unit",
      allowClear: true,
      width: '100%',
      dropdownCssClass: "bigdrop"
    });

    $('#searchStaff').select2('destroy').val("").select2({
      placeholder: "Staff",
      allowClear: true,
      width: '100%',
      dropdownCssClass: "bigdrop"
    });

    $('#searchCategory').select2('destroy').val("").select2({
      placeholder: "Category",
      allowClear: true,
      width: '100%',
      dropdownCssClass: "bigdrop"
    });

    $('#tsStatus').select2('destroy').val("").select2({
      placeholder: "TS Status",
      allowClear: true,
      width: '100%',
      dropdownCssClass: "bigdrop"
    });

    oTable.columns().search('').column('booking.date:name').search(moment().subtract(1, 'days').format('DD-MM-YYYY')+" - "+moment().subtract(1, 'days').format('DD-MM-YYYY')).draw();

  });
    // RESET

  $(document).on('click',".openCheckin",function(){
    $("#verifyModal #staffHours").removeClass('input_alert');
    $("#verifyModal #unitHours").removeClass('input_alert');
     $(".error").hide();
     var action = $("#checkInModal").attr("single");
     var token = $("#checkInModal").attr("token");
      $.ajax({
        type: "POST",
        async: false,
        url: action,
        data: {
            '_token': token,
            'timesheetId':$(this).attr("id")
        },
        success: function(response) {

            $("#checkInModal").modal();
            $("#checkInModal #startTime").val(moment(response.startTime,"HH:mm:ss").format( "HH:mm"));
            $("#checkInModal #endTime").val(moment(response.endTime,"HH:mm:ss").format( "HH:mm"));
            $("#checkInModal #breakHours").val(moment(response.breakHours,"HH:mm:ss").format( "HH:mm"));
            $("#checkInModal #unitHours").val((response.unitHours).toFixed(2));
            $("#checkInModal #staffHours").val((response.staffHours).toFixed(2));
            $("#checkInModal #comments").val(response.comments);
            $("#checkInModal input[name=timesheetId]").val(response.timesheetId);
            $("#checkInModal input[name=ts_No]").val(response.number);
            $("#checkInModal input[name=ts_Id]").val(response.timesheetRefId),
            $(".genrteId").attr('bookId',response.bookingId)
            .attr('date',moment(response.booking.date,"YYYY-MM-DD").format( "DD-MM-YYYY"))
            .attr('unit',response.booking.unit.alias)
            .attr('staff',response.booking.staff.forname+"_"+response.booking.staff.surname)
            .attr('shift',response.booking.shift.name);
            bindModalHeader(response);
            if(response.smsRejectedStatus == 1){
              $(".rejectdSms").attr('disabled','disabled');
            }else{
              $(".rejectdSms").removeAttr('disabled');
            }
        }
      });
  });

  $('#checkInModal input[name=startTime],#checkInModal input[name=endTime]').keypress(function(e) {
    if ((event.which < 48 || event.which > 57) &&  (event.which < 57 || event.which > 58)) {
      e.preventDefault();
    }
  });

  $(".checkInAction").click(function(){
    $(".error").hide();
    var action = $("#checkInModal").attr("action");
    var token = $("#checkInModal").attr("token");

    var startTime= $('#checkInModal input[name=startTime]').val();
    var endTime= $('#checkInModal input[name=endTime]').val();

    var reg=/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;

    if($("#checkInModal input[name=ts_No]").val() == ""){
      $("#checkInModal input[name=ts_No]").focus().next().show();
      return false;
    }
    if(reg.test(startTime) ==false){
      $("#checkInModal input[name=startTime]").focus().next().show();
      return false;

    }if(reg.test(endTime) ==false){
      $("#checkInModal input[name=endTime]").focus().next().show();
      return false;

    }else{
      $.ajax({
         url:action,
         method:"POST",
          async: false,
         data:{
          '_token': token,
          'startTime':$("#startTime").val(),
          'endTime':$("#endTime").val(),
          'breakHours':$("#breakHours").val(),
          'unitHours':$("#unitHours").val(),
          'staffHours':$("#staffHours").val(),
          'comments':$("#comments").val(),
          'number':$("input[name=ts_No]").val(),
          'timesheetId' : $("input[name=timesheetId]").val(),
          'timesheetRefId' : $("input[name=ts_Id]").val(),
        },
        success:function(data){
          $("#checkInModal").modal('hide');
          oTable.draw();
        }
      });
    }

  });

  $(".genrteId").click(function(){
    var name = $(this).attr('bookid')+"_"+$(this).attr('date')+"_"+$(this).attr('staff')+"_"+$(this).attr('unit')+"_"+$("input[name=ts_No]").val();
    $("input[name=ts_Id]").val(name);
  });

  $(".copyToClipBoard").click(function(){
    $("input[name=ts_Id]").select();
    document.execCommand("copy");
  });

  function bindModalHeader(timesheet){
    $(".shiftSmry .bookId").html(timesheet.bookingId);
    $(".shiftSmry .date").html( moment(timesheet.booking.date,"YYYY-MM-DD").format( "DD-MM-YYYY,ddd"));
    $(".shiftSmry .categry").html(timesheet.booking.category.name);
    $(".shiftSmry .unit").html(timesheet.booking.unit.alias);
    $(".shiftSmry .shift").html(timesheet.booking.shift.name);
    $(".shiftSmry .staff").html(timesheet.booking.staff.forname+" "+timesheet.booking.staff.surname);
  }

  $(document).on('click',".openVerfy",function(){
    $("#verifyModal input[name=staffHours]").removeClass('input_alert');
    $("#verifyModal input[name=unitHours]").removeClass('input_alert');
    $(".error").hide();


     var action = $("#verifyModal").attr("single");
     var token = $("#verifyModal").attr("token");
      $.ajax({
        type: "POST",
        async: false,
        url: action,
        data: {
            '_token': token,
            'timesheetId':$(this).attr("id")
        },
        success: function(response) {
            $("#verifyModal").modal();
            $("#verifyModal input[name=startTime]").val(moment(response.startTime,"HH:mm:ss").format( "HH:mm")).removeClass('input_alert');
            if(response.scheduleStaffHours.startTime != response.startTime){
              $("#verifyModal input[name=startTime]").addClass('input_alert');
            }

            $("#verifyModal input[name=endTime]").val(moment(response.endTime,"HH:mm:ss").format( "HH:mm")).removeClass('input_alert');
            if(response.scheduleStaffHours.endTime != response.endTime){
              $("#verifyModal input[name=endTime]").addClass('input_alert');
            }
            $("#verifyModal input[name=breakHours]").val(moment(response.breakHours,"HH:mm:ss").format( "HH:mm"));

            $("#verifyModal input[name=unitHours]").val((response.unitHours).toFixed(2)).removeClass('input_alert');
            
            if(response.scheduleStaffHours.totalHoursUnit != response.unitHours){
              $("#verifyModal input[name=unitHours]").addClass('input_alert');
            }
            $("#verifyModal input[name=staffHours]").val((response.staffHours).toFixed(2)).removeClass('input_alert');
            if(response.scheduleStaffHours.totalHoursStaff != response.staffHours){
              $("#verifyModal input[name=staffHours]").addClass('input_alert');
            }
            $("#verifyModal textarea[name=comments]").val(response.comments);
            $("#verifyModal input[name=timesheetId]").val(response.timesheetId);
            $("#verifyModal input[name=ts_No]").val(response.number);
            $("#verifyModal input[name=ts_Id]").val(response.timesheetRefId),
            $("#verifyModal .genrteId").attr('bookId',response.bookingId).attr('date',response.booking.date).attr('unit',response.booking.unit.alias);
            if(response.smsAcceptedStatus == 1){
              $(".sendSMS").attr('disabled','disabled');
            }else{
              $(".sendSMS").removeAttr('disabled');
            }
            bindModalHeader(response);
        }
      });
  });

  $(".revertActn").click(function(){
    var action = $("#verifyModal").attr("revert");
    var token = $("#verifyModal").attr("token");
    $.ajax({
         url:action,
         method:"POST",
          async: false,
         data:{
          '_token': token,
          'timesheetId' : $("#verifyModal input[name=timesheetId]").val(),
          'remarks' : $("#verifyModal textarea[name=comments]").val()
        },
        success:function(data){
          $("#verifyModal").modal('hide');
          oTable.draw();
        }
      });
  });

  $(".verifyActn").click(function(){
    var action = $("#verifyModal").attr("action");
    var token = $("#verifyModal").attr("token");
    if($("#verifyModal input[name=ts_No]").val() == ""){
      $("#verifyModal input[name=ts_No]").focus().next().show();
      return false;
    }else{
      $.ajax({
         url:action,
         method:"POST",
          async: false,
         data:{
          '_token': token,
          'comments':$("#verifyModal textarea[name=comments]").val(),
          'timesheetId' : $("#verifyModal input[name=timesheetId]").val()
        },
        success:function(data){
          $("#verifyModal").modal('hide');
          oTable.draw();
        }
      });
    }
  });

  $(document).on("click", ".openLogModal", function() {
    $("#timesheetLogBook").modal("show");
    var token = $("#timesheetLogBook").attr("token");
    var bookId = $(this).attr("booking");
    var action = $("#timesheetLogBook").attr("get-url");
    var name = $(this).attr('name');
    var category = $(this).attr('category');
    var bookingId = $(this).attr('booking');
    var shift = $(this).attr('shift');
    var unit = $(this).attr('unit');
    $.ajax({
        type: "POST",
        url: action,
        data: {
            _token: token,
            bookId: bookId
        },
        success: function(response) {
          $("#timesheetLogBook .modal-body").html(response);
          $("#timesheetLogBook .modal-header .name").html(name);
          $("#timesheetLogBook .modal-header .category").html(category);
          $("#timesheetLogBook .modal-header .bookingId").html(bookingId);
          $("#timesheetLogBook .modal-header .shift").html(shift);
          $("#timesheetLogBook .modal-header .unit").html(unit);
        }
    });
    $("#timesheetLogBook .timesheetId").val(timesheetId);
  });

  $(".sendSMS").click(function() {
    var token = $("#verifyModal .sendSMS").attr("token");
    var timesheetId = $("#verifyModal input[name=timesheetId]").val();
    var action = $("#verifyModal .sendSMS").attr("action");
    var message = '<span class="alert-msg" style="margin - left: 30px">Sucessfully sent SMS</span>';
    $('#pageTitle').find('span.alert-msg').remove();
    $('#pageTitle').append(message);

    $.ajax({
      type: "POST",
      url: action,
      data: {
          _token: token,
          timesheetId: timesheetId
      }
    });
  });

  $(document).on('click',".rejectdSms",function(){
    $("#modal-sms").modal('show');
    var timesheetId = $("#checkInModal input[name=timesheetId]").val();
    var action = $("#modal-sms").attr("action");
    var token = $("#modal-sms").attr("token");
      $.ajax({
        type: "POST",
        url: action,
        data: {
            '_token': token,
            'timesheetId':timesheetId
        },
        success: function(response) {
          $("#modal-sms textarea[name=msgContent]").val(response.data);
          $("#modal-sms input[name=timesheetIdSMS]").val(timesheetId);
        }
      });
  });

  $(document).on('click',".resendSMS",function(){
    var timesheetId = $("#modal-sms input[name=timesheetIdSMS]").val();
    var messages = $("#modal-sms textarea[name=msgContent]").val();
    var action = $(".resendSMS").attr("action");
    var token = $(".resendSMS").attr("token");
    $("#modal-sms").modal('hide');
    $("#checkInModal").modal('hide');

    var message = '<span class="alert-msg" style="margin - left: 30px">Sucessfully sent SMS</span>';
    $('#pageTitle').find('span.alert-msg').remove();
    $('#pageTitle').append(message);
    $.ajax({
      type: "POST",
      url: action,
      data: {
          '_token': token,
          'timesheetId':timesheetId,
          'messages':messages,
      },
    });
  });

  $("#timesheetLogBook .modal-content").mCustomScrollbar();
});
