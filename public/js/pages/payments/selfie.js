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
      if(state.columns[3].search.search!=""){
        $('#searchShift').select2('destroy').val(state.columns[3].search.search).select2({
          placeholder: "Shift",
          allowClear: true,
          width: '100%',
        });
        $("#searchShift").parent().parent().addClass('filterSlected');
      }

      if(state.columns[6].search.search!=""){
        $('#searchCategory').select2('destroy').val(state.columns[6].search.search).select2({
          placeholder: "Category",
          allowClear: true,
          width: '100%',
        });
        $("#searchCategory").parent().parent().addClass('filterSlected');
      }

      if(state.columns[5].search.search!=""){
        $('#searchStaff').select2('destroy').val(state.columns[5].search.search).select2({
          placeholder: "Staff",
          allowClear: true,
          width: '100%',
        });
        $("#searchStaff").parent().parent().addClass('filterSlected');
      }
      if(state.columns[9].search.search!=""){
        $('#weekSelect').select2('destroy').val(state.columns[9].search.search).select2({
          placeholder: "Week",
          allowClear: true,
          width: '100%',
        });
        $("#weekSelect").parent().parent().addClass('filterSlected');
      }

      if(state.columns[8].search.search!=""){
        $('#statusSlct').select2('destroy').val(state.columns[8].search.search).select2({
          placeholder: "Status",
          allowClear: true,
          width: '100%',
        });
        $("#statusSlct").parent().parent().addClass('filterSlected');
      }
      if (state.columns[2].search.search != "") {
        var dates = state.columns[2].search.search;
        var res = dates.split(" - ");
        $("#searchDate").val(dates).trigger("change");
        $("#searchDate").parent().parent().addClass("filterSlected");
        var inputDate = new Date(state.columns[2].search.search);
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
      localStorage.setItem('payeeVATable', JSON.stringify(oData));
    },
    fnStateLoad: function (oSettings) {
      return JSON.parse(localStorage.getItem('payeeVATable'));
    },
    ajax: {
      url: dataUrl,
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [{
        data: 'paymentId',
        name: 'paymentId',
        orderable: false,
        searchable: false
      },
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
        data: 'status',
        name: 'status',
        orderable: false
      },
      {
        data: 'timesheet.paymentWeek',
        name: 'timesheet.paymentWeek',
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

  $('#statusSlct').on('change', function () {
    oTable.column('status:name').search(this.value).draw();
    var elment = $("#statusSlct").parent().parent();
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
    $('#searchCategory').select2('destroy').val("").select2({
      placeholder: "Category",
      allowClear: true,
      width: '100%',
    });
    $('#weekSelect').select2('destroy').val("").select2({
      placeholder: "Week",
      allowClear: true,
      width: '100%',
    });

    oTable.columns().search('').draw();

  });

/* VERIFY POPUP STARTS */
  $(document).on('click', '.verify-btn', function (e) {
    var paymentId = $(this).data('paymentid');
    var singleUrl = $("#supModalverfy").attr("single");
    var token = $("#supModalverfy").attr('token');
    $('#supModalverfy').modal('show');
    $.ajax({
      type: 'POST',
      async: false,
      url: singleUrl,
      data: {
        "_token": token,
        "paymentId": paymentId
      },
      success: function (response) {
        $('#supModalverfy .bookingId').val(response.data.booking.bookingId);
        $('#supModalverfy .bookId').html("#"+response.data.booking.bookingId);
        $('#supModalverfy .bookingDate span').html(moment(response.data.booking.date).format('DD-MM-YYYY')+", "+response.data.booking.day);
        $('#supModalverfy .unitName').html(response.data.booking.unit.alias);
        $('#supModalverfy .categoryName span').html(response.data.booking.category.name);
        $('#supModalverfy .shiftName').html(response.data.booking.shift.name);
        $('#supModalverfy .staffName').html(response.data.booking.staff.forname+" "+response.data.booking.staff.surname);

        $('#supModalverfy input[name=ta]').val(response.data.booking.transportAllowence);
        $('#supModalverfy .distenceToWorkPlace').html(response.data.booking.distenceToWorkPlace);
        $('#supModalverfy .modeOfTransport').html(response.data.booking.transportMode);

        $('#supModalverfy .addtnalStaffs').html(response.data.addiotnalStaff.names);
        $('#supModalverfy .bonusAuthorizedBy').html(response.data.booking.bonusAuthorizedBy);
        $('#supModalverfy .notes').html(response.data.timesheet.comments);
        $("#supModalverfy input[name=ts_Id]").val(response.data.timesheet.timesheetRefId);
        $('#supModalverfy textarea[name=remarks]').html(response.data.remarks);
        $('#supModalverfy .startTime').html(moment(response.data.timesheet.startTime,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        if(moment(response.data.scheduleStaffHours.startTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.startTime){
          $("#supModalverfy .startTime").addClass('redBrdr');
        }

        $('#supModalverfy .endTime').html(moment(response.data.timesheet.endTime,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        if(moment(response.data.scheduleStaffHours.endTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.endTime){
          $("#supModalverfy .endTime").addClass('redBrdr');
        }

        $("#supModalverfy .breakHours").html(moment(response.data.timesheet.breakHours,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        $('#supModalverfy .unitHours').html((response.data.timesheet.unitHours).toFixed(2));
        if(response.data.scheduleStaffHours.totalHoursUnit != response.data.timesheet.unitHours){
          $("#supModalverfy .unitHours").addClass('redBrdr');
        }

        $('#supModalverfy .staffHours').html((response.data.timesheet.staffHours).toFixed(2));
        if(response.data.scheduleStaffHours.totalHoursStaff != response.data.timesheet.staffHours){
          $("#supModalverfy .staffHours").addClass('redBrdr');
        }

        $('#supModalverfy .checkdByName').html(response.data.timesheet.checkin.name);
        $('#supModalverfy .vrfiedByName').html(response.data.timesheet.verify.name);
        $('#supModalverfy input[name=paymentId]').val(response.data.paymentId);
        $('#supModalverfy input[name=hRate]').val(response.data.booking.staff.hourlyRate);
        $('#supModalverfy input[name=extraTA]').val(response.data.booking.extraTA);
        $('#supModalverfy input[name=bonus]').val(response.data.booking.bonus);
        if(response.data.otherPay != 'N/A') {
          $('#supModalverfy input[name=otherPay]').val(response.data.otherPay);
        } else {
          $('#supModalverfy input[name=otherPay]').val('');
        }
        if(response.data.otherPayAmount != null) {
          $('#supModalverfy input[name=otherPayAmount]').val(response.data.otherPayAmount.toFixed(2));
        }
        $('#supModalverfy input[name=hidStaffHrs]').val(response.data.timesheet.staffHours);
        $('#supModalverfy input[name=hidUnitHrs]').val(response.data.timesheet.unitHours);

        var ratePerHour = (
          parseFloat(response.data.booking.staff.hourlyRate)+
          parseFloat(response.data.booking.extraTA)+
          parseFloat(response.data.booking.bonus)
          );
        ratePerHour = parseFloat(ratePerHour)+parseFloat(response.data.booking.transportAllowence);

        var shiftTotalCal = (parseFloat(ratePerHour) * parseFloat(response.data.timesheet.staffHours));
        if(response.data.otherPayAmount != null){
          shiftTotalCal = parseFloat(shiftTotalCal)+parseFloat(response.data.otherPayAmount);
        };
        $('#supModalverfy .ratePerHrs').val(ratePerHour.toFixed(2));
        $('#supModalverfy .shiftTotal').val(shiftTotalCal.toFixed(2));
        $("#supModalverfy select[name=paymentWeek]").val(response.data.paymentWeek);
        if(response.data.paymentYear != null) {
          $("#supModalverfy select[name=paymentYear]").val(response.data.paymentYear);
        } else {
          $("#supModalverfy select[name=paymentYear]").val(4);
        }
      }
    });
  });
/* VERIFY POPUP ENDS */


/* VERIFY SAVE AND APPROVAL click events starts */
  $(document).on('click', '.update-verify', function (e) {
    var actionUrl = $("#supModalverfy").attr("action");
    var token = $("#supModalverfy").attr('token');
    $('#supModalverfy').modal('hide');
    $.ajax({
      type: 'POST',
      async: false,
      url: actionUrl,
      data: {
        "_token": token,
        "bookId" : $('input[name=bookingId]').val(),
        "paymentId": $('#supModalverfy input[name=paymentId]').val(),
        "hourlyRate"  :$('input[name=hRate]').val(),
        "ta"  :$('input[name=ta]').val(),
        "extraTa"  :$('input[name=extraTA]').val(),
        "bonus"  :$('input[name=bonus]').val(),
        "otherPay" : $('input[name=otherPay]').val(),
        "otherPayAmount" : $('input[name=otherPayAmount]').val(),
        "remarks"  :$('textarea[name=remarks]').val(),
        "paymentWeek"  :$('#sel2').val(),
        "paymentYear"  :$('#sel1').val(),
        'type'  : $(this).attr('method'),
      },
      success: function (response) {
        oTable.ajax.reload();

      }
    });
  });
/* VERIFY SAVE AND APPROVAL ends */


/* Approval POP UP STarts */
  $(document).on('click', '.approve-btn', function (e) {
    paymentId = $(this).data('paymentid');
    $('#approvePaymentId').val($(this).data('paymentid'));
    var singleUrl = $("#approveModal").attr("single");
    var token = $("#approveModal").attr('token');
    $('#approveModal').modal('show');
    $.ajax({
      type: 'POST',
      async: false,
      url: singleUrl,
      data: {
        "_token": token,
        "paymentId": paymentId
      },
      success: function (response) {
        $('#approveModal .bookingId').val(response.data.booking.bookingId);
        $('#approveModal .bookId').html("#"+response.data.booking.bookingId);
        $('#approveModal .bookingDate span').html(moment(response.data.booking.date).format('DD-MM-YYYY')+", "+response.data.booking.day);
        $('#approveModal .unitName').html(response.data.booking.unit.alias);
        $('#approveModal .categoryName span').html(response.data.booking.category.name);
        $('#approveModal .shiftName').html(response.data.booking.shift.name);
        $('#approveModal .staffName').html(response.data.booking.staff.forname+" "+response.data.booking.staff.surname);
        $('#approveModal .startTime').html(response.data.timesheet.startTime);

        if(moment(response.data.scheduleStaffHours.startTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.startTime){
          $("#approveModal .startTime").addClass('redBrdr');
        }

        $('#approveModal .endTime').html(moment(response.data.timesheet.endTime,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        if(moment(response.data.scheduleStaffHours.endTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.endTime){
          $("#approveModal .endTime").addClass('redBrdr');
        }

        $("#approveModal .breakHours").html(moment(response.data.timesheet.breakHours,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        $('#approveModal .unitHours').html((response.data.timesheet.unitHours).toFixed(2));
        if(response.data.scheduleStaffHours.totalHoursUnit != response.data.timesheet.unitHours){
          $("#approveModal .unitHours").addClass('redBrdr');
        }

        $('#approveModal .staffHours').html((response.data.timesheet.staffHours).toFixed(2));
        if(response.data.scheduleStaffHours.totalHoursStaff != response.data.timesheet.staffHours){
          $("#approveModal .staffHours").addClass('redBrdr');
        }

        $('#approveModal .tsNumbr').val(response.data.timesheet.number);
        $('#approveModal .verfyComment').html(response.data.timesheet.comments);
        $('#approveModal .aggreedRate').val(Number(response.data.booking.aggreedHrRate));

        $('#approveModal input[name=ta]').val(response.data.booking.transportAllowence);
        $('#approveModal .distenceToWorkPlace').html(response.data.booking.distenceToWorkPlace);
        $('#approveModal .modeOfTransport').html(response.data.booking.transportMode);

        $('#approveModal .addiotnalStaff').html(response.data.addiotnalStaff.names);
        $('#approveModal .bonusReason').html(response.data.booking.bonusReason);
        $('#approveModal .bonusAuthorizedBy').html(response.data.booking.bonusAuthorizedBy);

        $("#approveModal select[name=paymentWeek]").val(response.data.paymentWeek);
        $("#approveModal input[name=ts_Id]").val(response.data.timesheet.timesheetRefId);

        if(response.data.paymentYear != null) {
          $("#approveModal select[name=paymentYear]").val(response.data.paymentYear);
        } else {
          $("#approveModal select[name=paymentYear]").val(4);
        }

        $("#approveModal .tsCheckedBy").val(response.data.timesheet.tsCheckedBy);
        $("#approveModal .histrclRate").val(response.data.booking.staff.historical_rate);
        $("#approveModal .tsVrfdBy").val(response.data.timesheet.tsVrfdBy);
        $("#approveModal .pymnetVrfdBy").val(response.data.pymnetVrfdBy);
        $('#approveModal .checkdByName').html(response.data.timesheet.checkin.name);
        $('#approveModal .vrfiedByName').html(response.data.timesheet.verify.name);
        $('#approveModal .approvedByName').html(response.data.approvedBy);
        $('#approveModal textarea[name=remarks]').val(response.data.remarks);
        $('#approveModal input[name=paymentId]').val(response.data.paymentId);
        $('#approveModal input[name=hRate]').val((response.data.hourlyRate).toFixed(2));
        $('#approveModal input[name=extraTA]').val(response.data.booking.extraTA);
        $('#approveModal input[name=bonus]').val((response.data.bonus).toFixed(2));
        if(response.data.otherPay != 'N/A') {
          $('#approveModal input[name=otherPay]').val(response.data.otherPay);
        } else {
          $('#approveModal input[name=otherPay]').val('');
        }
        $('#approveModal input[name=otherPayAmount]').val(response.data.otherPayAmount);
        if(response.data.otherPayAmount != null) {
          $('#approveModal input[name=otherPayAmount]').val((response.data.otherPayAmount).toFixed(2));
        }
        $('#approveModal input[name=hidStaffHrs]').val(response.data.timesheet.staffHours);

        var ratePerHour = (
          parseFloat(response.data.booking.staff.hourlyRate)+
          (parseFloat(response.data.booking.extraTA)+parseFloat(response.data.addiotnalStaff.count))+
          parseFloat(response.data.booking.bonus)
          );
        ratePerHour = parseFloat(ratePerHour)+parseFloat(response.data.booking.transportAllowence);
        var shiftTotalCal = (parseFloat(ratePerHour) * parseFloat(response.data.timesheet.staffHours));
        if(response.data.otherPayAmount != null){
          shiftTotalCal = parseFloat(shiftTotalCal)+parseFloat(response.data.otherPayAmount);
          $("#approveModal .totalOtherPayAmount").val((response.data.otherPayAmount).toFixed(2));
        }else{
          $("#approveModal .totalOtherPayAmount").val('0.00');
        }
        var totlHr = response.data.booking.staff.hourlyRate * response.data.timesheet.staffHours;
        var totalTA = response.data.booking.transportAllowence*response.data.timesheet.staffHours;
        var totlExtraTa = response.data.extraTa*response.data.timesheet.staffHours;
        var totalBns = response.data.bonus*response.data.timesheet.staffHours;
        var grossPay = ((parseFloat(response.data.hourlyRate)+parseFloat(response.data.bonus))*response.data.timesheet.staffHours);
        var hldyPay = (grossPay * 12.08)/100 ;
        var weeklyPay = parseFloat(grossPay) - parseFloat(hldyPay);
        var grossTa = ((parseFloat(response.data.booking.transportAllowence)+parseFloat(response.data.extraTa))*response.data.timesheet.staffHours);
        var fhRate = weeklyPay/response.data.timesheet.staffHours;

        var total = parseFloat(totlHr);
        if(totalTA != null){
          total = parseFloat(total)+parseFloat(totalTA);
        }
        if(totlExtraTa != null){
          total = parseFloat(total)+parseFloat(totlExtraTa);
        }
        if(totalBns != null){
          total = parseFloat(total)+parseFloat(totalBns);
        }
        if(response.data.otherPayAmount != null){
          total = parseFloat(total)+parseFloat(response.data.otherPayAmount);
        }

        var shiftGrandTotal = parseFloat(grossPay);
        if(grossTa != null){
          shiftGrandTotal = parseFloat(shiftGrandTotal)+parseFloat(grossTa);
        }
        if(response.data.otherPayAmount != null){
          shiftGrandTotal = parseFloat(shiftGrandTotal)+parseFloat(response.data.otherPayAmount);
        }

        $('#approveModal .ratePerHr').val(ratePerHour.toFixed(2));
        $('#approveModal .appShiftTotal').val(shiftTotalCal.toFixed(2));
        $('#approveModal .totlHr').val(totlHr.toFixed(2));
        $('#approveModal .totTa').val(totalTA.toFixed(2));
        $('#approveModal .totlExtraTa').val(totlExtraTa.toFixed(2));
        $('#approveModal .totalBns').val(totalBns.toFixed(2));
        $('#approveModal .grossPay').val(grossPay.toFixed(2));
        $('#approveModal .hldyPay').val(hldyPay.toFixed(2));
        $('#approveModal .weeklyPay').val(weeklyPay.toFixed(2));
        $('#approveModal .grossTa').val(grossTa.toFixed(2));
        if(response.data.booking.fhRate < 8){ $('#approveModal .fhRate').addClass('redbg'); }
        $('#approveModal .fhRate').val(fhRate.toFixed(2));
        $('#approveModal .fhRate').removeClass('red').removeClass('green');
        if(parseFloat(fhRate) > 8.65){
          $('#approveModal .fhRate').addClass('green');
        }else{
          $('#approveModal .fhRate').addClass('red');
        }
        $('#approveModal .total').val(total.toFixed(2));
        $('#approveModal .shiftGrandTotal').val(shiftGrandTotal.toFixed(2));

      }
    });
  });
/* Approval POP up Ends */
$(".copyToClipBoard").click(function(){
  $("input[name=ts_Id]").select();
  document.execCommand("copy");
});
/* Approval Save, revert, approval Starts */
  $(document).on('click', '.update-approve', function (e) {
    var actionUrl = $("#approveModal").attr("action");
    var token = $("#approveModal").attr('token');
    $('#approveModal').modal('hide');
    $.ajax({
      type: 'POST',
      async: false,
      url: actionUrl,
      data: {
        "_token": token,
        "bookId" : $('input[name=bookingId]').val(),
        "paymentId": $('#approveModal input[name=paymentId]').val(),
        "hourlyRate":$('#approveModal input[name=hRate]').val(),
        "ta": $('#approveModal .appTa').val(),
        "extraTa": $('#approveModal .appExtraTA').val(),
        "bonus": $('#approveModal .appBonus').val(),
        "paymentYear": $('#approveModal .paymentYear').val(),
        "paymentWeek": $('#approveModal .paymentWeek').val(),
        "otherPay": $('#approveModal .otherPay').val(),
        "otherPayAmount": $('#approveModal .otherPayAmount').val(),
        "remarks": $('#approveModal .remarks').val(),
        'type'  : $(this).attr('method'),
      },
      success: function (response) {
        oTable.ajax.reload();
        var message = '<span class="alert-msg" style="margin - left: 30px">Sucessfully approved the entry</span>';
        $('#pageTitle').find('span.alert-msg').remove();
        $('#pageTitle').append(message);
      }
    });
  });
/* Approval Save, revert, approval Ends */


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

  $('#weekSelect').select2({
    placeholder: "Week",
    allowClear: true,
    width: '100%'
  });

  $('#statusSlct').select2({
    placeholder: "Status",
    allowClear: true,
    width: '100%'
  });

  // });
 /*Calculations in Verify Modal starts */
  $(document).on('keyup','input.form-control.ta',function(){
    var ta = $(this).val();
    var hRate = $("#supModalverfy input[name=hRate]").val();
    var extraTA = $("#supModalverfy input[name=extraTA]").val();
    var bonus = $("#supModalverfy input[name=bonus]").val();
    if(($("#supModalverfy input[name=otherPayAmount]").val() == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#supModalverfy input[name=otherPayAmount]").val();
    }
    var hidStaffHrs = $("#supModalverfy input[name=hidStaffHrs]").val();
    var hidUnitHrs = $("#supModalverfy input[name=hidUnitHrs]").val();
    var ratePerHr = (parseFloat(hRate)+parseFloat(ta)+parseFloat(extraTA)+parseFloat(bonus));
    $("#supModalverfy input[name=ratePerHr]").val(ratePerHr.toFixed(2));
    $("#supModalverfy input[name=ratePerHr]").trigger('keyup');
    var shiftTotal = parseFloat(ratePerHr * hidUnitHrs) + parseFloat(otherPayAmount);
    $("#supModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));
    $("#supModalverfy input[name=shiftTotal]").trigger('keyup');
  });

  $(document).on('keyup','input.form-control.extraTA',function(){
    var extraTA = $(this).val();
    var hRate = $("#supModalverfy input[name=hRate]").val();
    var ta = $("#supModalverfy input[name=ta]").val();
    var bonus = $("#supModalverfy input[name=bonus]").val();
    if(($("#supModalverfy input[name=otherPayAmount]").val() == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#supModalverfy input[name=otherPayAmount]").val();
    }
    var hidStaffHrs = $("#supModalverfy input[name=hidStaffHrs]").val();
    var hidUnitHrs = $("#supModalverfy input[name=hidUnitHrs]").val();
    var ratePerHr = (parseFloat(hRate)+parseFloat(ta)+parseFloat(extraTA)+parseFloat(bonus));
    $("#supModalverfy input[name=ratePerHr]").val(ratePerHr.toFixed(2));
    $("#supModalverfy input[name=ratePerHr]").trigger('keyup');
    var shiftTotal = parseFloat(ratePerHr * hidUnitHrs) + parseFloat(otherPayAmount);
    $("#supModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));
    $("#supModalverfy input[name=shiftTotal]").trigger('keyup');
  });

  $(document).on('keyup','input.form-control.bonus',function(){
    var bonus = $(this).val();
    var hRate = $("#supModalverfy input[name=hRate]").val();
    var ta = $("#supModalverfy input[name=ta]").val();
    var extraTA = $("#supModalverfy input[name=extraTA]").val();
    if(($("#supModalverfy input[name=otherPayAmount]").val() == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#supModalverfy input[name=otherPayAmount]").val();
    }
    var hidStaffHrs = $("#supModalverfy input[name=hidStaffHrs]").val();
    var hidUnitHrs = $("#supModalverfy input[name=hidUnitHrs]").val();
    var ratePerHr = (parseFloat(hRate)+parseFloat(ta)+parseFloat(extraTA)+parseFloat(bonus));
    $("#supModalverfy input[name=ratePerHr]").val(ratePerHr.toFixed(2));
    $("#supModalverfy input[name=ratePerHr]").trigger('keyup');
    var shiftTotal = parseFloat(ratePerHr * hidUnitHrs) + parseFloat(otherPayAmount);
    $("#supModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));
    $("#supModalverfy input[name=shiftTotal]").trigger('keyup');

  });

  $(document).on('keyup','input.form-control.otherPayAmount',function(){
    var otherPayAmount = $(this).val();
    var hRate = $("#supModalverfy input[name=hRate]").val();
    var ta = $("#supModalverfy input[name=ta]").val();
    var bonus = $("#supModalverfy input[name=bonus]").val();
    var extraTA = $("#supModalverfy input[name=extraTA]").val();
    if(($("#supModalverfy input[name=otherPayAmount]").val() == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#supModalverfy input[name=otherPayAmount]").val();
    }
    var hidStaffHrs = $("#supModalverfy input[name=hidStaffHrs]").val();
    var hidUnitHrs = $("#supModalverfy input[name=hidUnitHrs]").val();
    var ratePerHr = (parseFloat(hRate)+parseFloat(ta)+parseFloat(extraTA)+parseFloat(bonus));
    $("#supModalverfy input[name=ratePerHr]").val(ratePerHr.toFixed(2));
    $("#supModalverfy input[name=ratePerHr]").trigger('keyup');
    var shiftTotal = parseFloat(ratePerHr * hidUnitHrs) + parseFloat(otherPayAmount);
    $("#supModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));
    $("#supModalverfy input[name=shiftTotal]").trigger('keyup');

  });
/* Calculations in Verify Modal ENDS */


});
