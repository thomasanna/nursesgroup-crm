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
        orderable: false,
        searchable: true
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
        data: 'paymentWeek',
        name: 'paymentWeek',
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
  $('#weekSelect').on('change', function () {
    oTable.column('timesheet.paymentWeek:name').search(this.value).draw();
    var elment = $("#weekSelect").parent().parent();
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
    $('#statusSlct').select2('destroy').val("").select2({
      placeholder: "Week",
      allowClear: true,
      width: '100%',
    });


    oTable.columns().search('').draw();

  });

  $(document).on('click', '.verify-btn', function (e) {
    $('#payeeModalverfy input[name=otherPay]').val('');
    $('#payeeModalverfy input[name=otherPayAmount]').val('');
    var paymentId = $(this).data('paymentid');
    var singleUrl = $("#payeeModalverfy").attr("single");
    var token = $("#payeeModalverfy").attr('token');
    $('#payeeModalverfy').modal('show');
    $.ajax({
      type: 'POST',
      url: singleUrl,
      data: {
        "_token": token,
        "paymentId": paymentId
      },
      success: function (response) {
        $('#payeeModalverfy .bookId').html("#"+response.data.booking.bookingId);
        $('#payeeModalverfy .bookingId').val(response.data.booking.bookingId);
        $('#payeeModalverfy .bookingDate span').html(moment(response.data.booking.date).format('DD-MM-YYYY')+", "+response.data.booking.day);
        $('#payeeModalverfy .unitName').html(response.data.booking.unit.alias);
        $('#payeeModalverfy .categoryName span').html(response.data.booking.category.name);
        $('#payeeModalverfy .shiftName').html(response.data.booking.shift.name);
        $('#payeeModalverfy .staffName').html(response.data.booking.staff.forname+" "+response.data.booking.staff.surname);

        $('#payeeModalverfy input[name=ta]').val(response.data.booking.transportAllowence);
        $('#payeeModalverfy .distenceToWorkPlace').html(response.data.booking.distenceToWorkPlace);

        $('#payeeModalverfy .modeOfTransport').html(response.data.booking.transportMode);

        $('#payeeModalverfy .addtnalStaffs').html(response.data.addiotnalStaff.names);
        $('#payeeModalverfy .bonusAuthorizedBy').html(response.data.booking.bonusAuthorizedBy);
        $('#payeeModalverfy .notes').html(response.data.timesheet.comments);
        $('#payeeModalverfy textarea[name=remarks]').val(response.data.remarks);
        $('#payeeModalverfy .startTime').html(moment(response.data.timesheet.startTime,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        if(moment(response.data.scheduleStaffHours.startTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.startTime){
          $("#payeeModalverfy .startTime").addClass('redBrdr');
        }

        $('#payeeModalverfy .endTime').html(moment(response.data.timesheet.endTime,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        if(moment(response.data.scheduleStaffHours.endTime,"HH:mm:ss").format( "HH:mm") != response.data.timesheet.endTime){
          $("#payeeModalverfy .endTime").addClass('redBrdr');
        }

        $("#payeeModalverfy .breakHours").html(moment(response.data.timesheet.breakHours,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        $('#payeeModalverfy .unitHours').html((response.data.timesheet.unitHours).toFixed(2));
        if(response.data.scheduleStaffHours.totalHoursUnit != response.data.timesheet.unitHours){
          $("#payeeModalverfy .unitHours").addClass('redBrdr');
        }

        $('#payeeModalverfy .staffHours').html((response.data.timesheet.staffHours).toFixed(2)).removeClass('redBrdr');
        if(response.data.scheduleStaffHours.totalHoursStaff != response.data.timesheet.staffHours){
          $("#payeeModalverfy .staffHours").addClass('redBrdr');
        }
        $("#payeeModalverfy input[name=ts_Id]").val(response.data.timesheet.timesheetRefId);
        $('#payeeModalverfy .checkdByName').html(response.data.timesheet.checkin.name);
        $('#payeeModalverfy .vrfiedByName').html(response.data.timesheet.verify.name);
        $('#payeeModalverfy input[name=paymentId]').val(response.data.paymentId);
        $('#payeeModalverfy input[name=hRate]').val(response.data.booking.staff.hourlyRate);
        $('#payeeModalverfy input[name=extraTA]').val(response.data.booking.extraTA);
        $('#payeeModalverfy input[name=bonus]').val(response.data.booking.bonus);
        if(response.data.otherPay != 'N/A') {
          $('#payeeModalverfy input[name=otherPay]').val(response.data.otherPay);
        } else {
          $('#payeeModalverfy input[name=otherPay]').val('');
        }
        if(response.data.otherPayAmount != null) {
          $('#payeeModalverfy input[name=otherPayAmount]').val(response.data.otherPayAmount.toFixed(2));
        }
        $('#payeeModalverfy input[name=hidStaffHrs]').val(response.data.timesheet.staffHours);
        $('#payeeModalverfy input[name=hidUnitHrs]').val(response.data.timesheet.unitHours);

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
        $('#payeeModalverfy .ratePerHrs').val(ratePerHour.toFixed(2));
        $('#payeeModalverfy .shiftTotal').val(shiftTotalCal.toFixed(2));
        $("#payeeModalverfy select[name=paymentWeek]").val(response.data.paymentWeek);

        if(response.data.paymentYear != null) {
          $("#payeeModalverfy select[name=paymentYear]").val(response.data.paymentYear);
        } else {
          $("#payeeModalverfy select[name=paymentYear]").val(4);
        }
      }
    });
  });
  $(".copyToClipBoard").click(function(){
    $("input[name=ts_Id]").select();
    document.execCommand("copy");
  });

  $(document).on('click', '.update-verify', function (e) {
    var actionUrl = $("#payeeModalverfy").attr("action");
    var token = $("#payeeModalverfy").attr('token');
    $('#payeeModalverfy').modal('hide');
    $.ajax({
      type: 'POST',
      url: actionUrl,
      data: {
        "_token": token,
        "bookId" : $('input[name=bookingId]').val(),
        "paymentId": $('#payeeModalverfy input[name=paymentId]').val(),
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
        var message = '<span class="alert-msg" style="margin - left: 30px">Sucessfully verified the entry</span>';
        $('#pageTitle').find('span.alert-msg').remove();
        $('#pageTitle').append(message);
      }
    });
  });

  $(document).on('click', '.approve-btn', function (e) {
    $('#approvePayeeModal input[name=otherPay]').val('');
    $('#approvePayeeModal input[name=otherPayAmount]').val('');
    paymentId = $(this).data('paymentid');
    $('#approvePaymentId').val($(this).data('paymentid'));
    var singleUrl = $("#approvePayeeModal").attr("single");
    var token = $("#approvePayeeModal").attr('token');
    $('#approvePayeeModal').modal('show');
    $.ajax({
      type: 'POST',
      url: singleUrl,
      data: {
        "_token": token,
        "paymentId": paymentId
      },
      success: function (response) {
        $('#approvePayeeModal .bookId').html("#"+response.data.booking.bookingId);
        $('#approvePayeeModal .bookingId').val(response.data.booking.bookingId);
        $('#approvePayeeModal .bookingDate span').html(moment(response.data.booking.date).format('DD-MM-YYYY')+", "+response.data.booking.day);
        $('#approvePayeeModal .unitName').html(response.data.booking.unit.alias);
        $('#approvePayeeModal .categoryName span').html(response.data.booking.category.name);
        $('#approvePayeeModal .shiftName').html(response.data.booking.shift.name);
        $('#approvePayeeModal .staffName').html(response.data.booking.staff.forname+" "+response.data.booking.staff.surname);
        $('#approvePayeeModal .startTime').html(response.data.timesheet.startTime).removeClass('redBrdr');

        if(response.data.scheduleStaffHours.startTime.substr(0, 5) != response.data.timesheet.startTime){
          $("#approvePayeeModal .startTime").addClass('redBrdr');
        }

        $('#approvePayeeModal .endTime').html(response.data.timesheet.endTime).removeClass('redBrdr');

        if(response.data.scheduleStaffHours.endTime.substr(0, 5) != response.data.timesheet.endTime){
          $("#approvePayeeModal .endTime").addClass('redBrdr');
        }

        $("#approvePayeeModal .breakHours").html(moment(response.data.timesheet.breakHours,"HH:mm:ss").format( "HH:mm")).removeClass('redBrdr');

        $('#approvePayeeModal .unitHours').html((response.data.timesheet.unitHours).toFixed(2));
        if(response.data.scheduleStaffHours.totalHoursUnit != response.data.timesheet.unitHours){
          $("#approvePayeeModal .unitHours").addClass('redBrdr');
        }

        $('#approvePayeeModal .staffHours').html((response.data.timesheet.staffHours).toFixed(2)).removeClass('redBrdr');
        if(response.data.scheduleStaffHours.totalHoursStaff != response.data.timesheet.staffHours){
          $("#approvePayeeModal .staffHours").addClass('redBrdr');
        }

        $('#approvePayeeModal .tsNumbr').val(response.data.timesheet.number);
        $('#approvePayeeModal .verfyComment').html(response.data.timesheet.comments);
        $('#approvePayeeModal .aggreedRate').val(Number(response.data.booking.aggreedHrRate));

        $('#approvePayeeModal input[name=ta]').val(response.data.booking.transportAllowence);
        $('#approvePayeeModal .distenceToWorkPlace').html(response.data.booking.distenceToWorkPlace);
        $('#approvePayeeModal .modeOfTransport').html(response.data.booking.transportMode);

        $('#approvePayeeModal .addtnalStaffs').html(response.data.addiotnalStaff.names);
        $('#approvePayeeModal .bonusReason').html(response.data.booking.bonusReason);
        $('#approvePayeeModal .bonusAuthorizedBy').html(response.data.booking.bonusAuthorizedBy);

        $("#approvePayeeModal select[name=paymentWeek]").val(response.data.paymentWeek);

        if(response.data.paymentYear != null) {
          $("#approvePayeeModal select[name=paymentYear]").val(response.data.paymentYear);
        } else {
          $("#approvePayeeModal select[name=paymentYear]").val(4);
        }

        $("#approvePayeeModal .tsCheckedBy").val(response.data.timesheet.tsCheckedBy);
        $("#approvePayeeModal .histrclRate").val(response.data.booking.staff.historical_rate);

        $("#approvePayeeModal .tsVrfdBy").val(response.data.timesheet.tsVrfdBy);
        $("#approvePayeeModal .pymnetVrfdBy").val(response.data.pymnetVrfdBy);
        $('#approvePayeeModal .checkdByName').html(response.data.timesheet.checkin.name);
        $('#approvePayeeModal .vrfiedByName').html(response.data.timesheet.verify.name);
        $('#approvePayeeModal .approvedByName').html(response.data.approvedBy);
        $('#approvePayeeModal textarea[name=remarks]').val(response.data.remarks);
        $("#approvePayeeModal input[name=ts_Id]").val(response.data.timesheet.timesheetRefId);

        $('#approvePayeeModal input[name=paymentId]').val(response.data.paymentId);
        $('#approvePayeeModal input[name=hRate]').val((response.data.hourlyRate).toFixed(2));
        $('#approvePayeeModal input[name=extraTA]').val(response.data.booking.extraTA);
        $('#approvePayeeModal input[name=bonus]').val((response.data.bonus).toFixed(2));
        if(response.data.otherPay != 'N/A') {
          $('#approvePayeeModal input[name=otherPay]').val(response.data.otherPay);
        } else {
          $('#approvePayeeModal input[name=otherPay]').val('');
        }

        if(response.data.otherPayAmount != null) {
          $('#approvePayeeModal input[name=otherPayAmount]').val((response.data.otherPayAmount).toFixed(2));
        } else {
          $('#approvePayeeModal input[name=otherPayAmount]').val('0.00');
        }
        $('#approvePayeeModal input[name=hidStaffHrs]').val(response.data.timesheet.staffHours);

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
        var totlHr = parseFloat(response.data.booking.staff.hourlyRate) * parseFloat(response.data.timesheet.staffHours);
        var totalTA = response.data.booking.transportAllowence*response.data.timesheet.staffHours;
        var totlExtraTa = response.data.extraTa*response.data.timesheet.staffHours;
        var totalBns = response.data.bonus*response.data.timesheet.staffHours;
        var grossPay = ((parseFloat(response.data.hourlyRate)+parseFloat(response.data.bonus))*response.data.timesheet.staffHours);
        var hldyPay = (grossPay * 12.08)/100 ;
        var weeklyPay = parseFloat(grossPay) - parseFloat(hldyPay);
        var grossTa = ((parseFloat(response.data.booking.transportAllowence)+parseFloat(response.data.extraTa))*response.data.timesheet.staffHours);
        var fhRate = weeklyPay/response.data.timesheet.staffHours;
        var total = parseFloat(totlHr)+parseFloat(totalTA)+parseFloat(totlExtraTa)+parseFloat(totalBns)+parseFloat(response.data.otherPayAmount);


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
        if(grossTa != null) shiftGrandTotal = parseFloat(shiftGrandTotal)+parseFloat(grossTa);
        if(response.data.otherPayAmount != null) shiftGrandTotal = parseFloat(shiftGrandTotal)+parseFloat(response.data.otherPayAmount);

        $('#approvePayeeModal .ratePerHr').val(ratePerHour.toFixed(2));
        $('#approvePayeeModal .appShiftTotal').val(shiftTotalCal.toFixed(2));
        $('#approvePayeeModal .totlHr').val(totlHr.toFixed(2));
        if(response.data.otherPayAmount != null) {
          $('#approvePayeeModal .totalOtherPayAmount').val(response.data.otherPayAmount.toFixed(2));
        } else {
          $('#approvePayeeModal .totalOtherPayAmount').val('0.00');
        }
        $('#approvePayeeModal .totTa').val(totalTA.toFixed(2));
        $('#approvePayeeModal .totlExtraTa').val(totlExtraTa.toFixed(2));
        $('#approvePayeeModal .totalBns').val(totalBns.toFixed(2));
        $('#approvePayeeModal .grossPay').val(grossPay.toFixed(2));
        $('#approvePayeeModal .hldyPay').val(hldyPay.toFixed(2));
        $('#approvePayeeModal .weeklyPay').val(weeklyPay.toFixed(2));
        $('#approvePayeeModal .grossTa').val(grossTa.toFixed(2));
        if(response.data.booking.fhRate < 8){ $('#approvePayeeModal .fhRate').addClass('redbg'); }
        $('#approvePayeeModal .fhRate').removeClass('red').removeClass('green');
        $('#approvePayeeModal .fhRate').val(fhRate.toFixed(2));
        if(parseFloat(fhRate) > 8.65){
          $('#approvePayeeModal .fhRate').addClass('green');
        }else{
          $('#approvePayeeModal .fhRate').addClass('red');
        }

        $('#approvePayeeModal .total').val(total.toFixed(2));
        $('#approvePayeeModal .shiftGrandTotal').val(shiftGrandTotal.toFixed(2));

      }
    });
  });

  $(document).on('click', '.update-approve', function (e) {
    var actionUrl = $("#approvePayeeModal").attr("action");
    var token = $("#approvePayeeModal").attr('token');
    $('#approvePayeeModal').modal('hide');
    $.ajax({
      type: 'POST',
      url: actionUrl,
      data: {
         "_token": token,
        "bookId" : $('#approvePayeeModal input[name=bookingId]').val(),
        "paymentId": $('#approvePayeeModal input[name=paymentId]').val(),
        "hourlyRate":$('#approvePayeeModal input[name=hRate]').val(),
        "ta": $('#approvePayeeModal .appTa').val(),
        "extraTa": $('#approvePayeeModal .appExtraTA').val(),
        "bonus": $('#approvePayeeModal .appBonus').val(),
        "paymentYear": $('#approvePayeeModal .paymentYear').val(),
        "paymentWeek": $('#approvePayeeModal .paymentWeek').val(),
        "otherPay": $('#approvePayeeModal .otherPay').val(),
        "otherPayAmount": $('#approvePayeeModal .otherPayAmount').val(),
        "remarks": $('#approvePayeeModal .remarks').val(),
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

 /*Calculations in Verify Modal starts */
  $(document).on('keyup','input.form-control.ta',function(){
    var ta = $(this).val();
    var hRate = $("#payeeModalverfy input[name=hRate]").val();
    var extraTA = $("#payeeModalverfy input[name=extraTA]").val();
    var bonus = $("#payeeModalverfy input[name=bonus]").val();
    if(($("#payeeModalverfy input[name=otherPayAmount]").val() == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#otherPayAmount").val();
    }
    var hidStaffHrs = $("#payeeModalverfy input[name=hidStaffHrs]").val();
    var ratePerHr = (parseFloat(hRate)+parseFloat(ta)+parseFloat(extraTA)+parseFloat(bonus));
    $("#payeeModalverfy input[name=ratePerHr]").val(ratePerHr.toFixed(2));
    $("#payeeModalverfy input[name=ratePerHr]").trigger('keyup');
    var shiftTotal = parseFloat(ratePerHr * hidStaffHrs) + parseFloat(otherPayAmount);
    $("#payeeModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));
    $("#payeeModalverfy input[name=shiftTotal]").trigger('keyup');
  });

  $(document).on('keyup','input.form-control.extraTA',function(){
    var extraTA = $(this).val();
    var hRate = $("#payeeModalverfy input[name=hRate]").val();
    var ta = $("#payeeModalverfy input[name=ta]").val();
    var bonus = $("#payeeModalverfy input[name=bonus]").val();
    if(($("#payeeModalverfy input[name=otherPayAmount]").val() == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#otherPayAmount").val();
    }
    var hidStaffHrs = $("#payeeModalverfy input[name=hidStaffHrs]").val();
    var ratePerHr = (parseFloat(hRate)+parseFloat(ta)+parseFloat(extraTA)+parseFloat(bonus));
    $("#payeeModalverfy input[name=ratePerHr]").val(ratePerHr.toFixed(2));
    $("#payeeModalverfy input[name=ratePerHr]").trigger('keyup');
    var shiftTotal = parseFloat(ratePerHr * hidStaffHrs) + parseFloat(otherPayAmount);
    $("#payeeModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));
    $("#payeeModalverfy input[name=shiftTotal]").trigger('keyup');
  });

  $(document).on('keyup','input.form-control.bonus',function(){
    var bonus = $(this).val();
    var hRate = $("#payeeModalverfy input[name=hRate]").val();
    var ta = $("#payeeModalverfy input[name=ta]").val();
    var extraTA = $("#payeeModalverfy input[name=extraTA]").val();
    if(($("#payeeModalverfy input[name=otherPayAmount]").val() == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#otherPayAmount").val();
    }
    var hidStaffHrs = $("#payeeModalverfy input[name=hidStaffHrs]").val();
    var ratePerHr = (parseFloat(hRate)+parseFloat(ta)+parseFloat(extraTA)+parseFloat(bonus));
    $("#payeeModalverfy input[name=ratePerHr]").val(ratePerHr.toFixed(2));
    $("#payeeModalverfy input[name=ratePerHr]").trigger('keyup');
    var shiftTotal = parseFloat(ratePerHr * hidStaffHrs) + parseFloat(otherPayAmount);
    $("#payeeModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));
    $("#payeeModalverfy input[name=shiftTotal]").trigger('keyup');

  });

  $(document).on('keyup','input.form-control.otherPayAmount',function(){
    var otherPayAmount = $(this).val();
    if((otherPayAmount == '')) {
      var otherPayAmount = 0.00;
    }
    else {
      var otherPayAmount = $("#payeeModalverfy input[name=otherPayAmount]").val();
    }
    var hidStaffHrs = $("#payeeModalverfy input[name=hidStaffHrs]").val();
    var ratePerHr = $("#payeeModalverfy input[name=ratePerHr]").val();

    var shiftTotal = parseFloat(ratePerHr * hidStaffHrs) + parseFloat(otherPayAmount);
    $("#payeeModalverfy input[name=shiftTotal]").val(shiftTotal.toFixed(2));

  });
/* Calculations in Verify Modal ENDS */

});
