var token = $(".table").attr("token");
$(".checkAll").iCheck({
    checkboxClass: "icheckbox_square-red ichkAll",
    radioClass: "iradio_square-red",
    increaseArea: "20%"
});

// CHECKBOX CHECK EVENT
$(".checkAll").on("ifChecked", function(event) {
    $("tbody tr input").iCheck("check");
});
// CHECKBOX UNCHECK EVENT
$(".checkAll").on("ifUnchecked", function(event) {
    $("tbody tr input").iCheck("uncheck");
});

$("#searchStaffStatus").select2({
    placeholder: "Search Staff Status",
    allowClear: true,
    width: "100%"
});
$("#modeOfTransport").select2({
    placeholder: "Transport",
    allowClear: true,
    width: "100%"
});

$("#searchCategory").select2({
    placeholder: "Category",
    allowClear: true,
    width: "100%"
});

$("#searchShift").select2({
    placeholder: "Shift",
    allowClear: true,
    width: "100%"
});

$("#searchUnitStatus").select2({
    placeholder: "Search Unit Status",
    allowClear: true,
    width: "100%"
});

$("#searchStaff").select2({
    placeholder: "Search Staff",
    allowClear: true,
    width: "100%"
});

$("#searchUnit").select2({
    placeholder: "Search Staff Unit",
    allowClear: true,
    width: "100%",
    dropdownCssClass: "bigdrop"
});

$("#searchDate").daterangepicker({
    locale: {
        format: "DD-MM-YYYY",
        firstDay: 1
    }
});

$(document).on("click", "a.btn.btn-danger.btn-xs.mrs", function() {
    var action = $(this).attr("action");
    var token = $(this).attr("token");
    bootbox.confirm({
        message: "Are you sure want to delet it ?",
        buttons: {
            confirm: {
                label: "Yes",
                className: "btn-success"
            },
            cancel: {
                label: "Cancel",
                className: "btn-danger"
            }
        },
        callback: function(result) {
            if (result == true) {
                $.ajax({
                    type: "GET",
                    async: false,
                    url: action,
                    data: {
                        _token: token
                    },
                    success: function(response) {
                        window.location = response.url;
                    }
                });
            }
        }
    });
});

var bookingData = [];

function generateUnitForms(unit,unitId) {
    $(".updateStatus").removeClass("onCancel");
    
    if (unit == 1) {
        // IF new
        $(".canceled").addClass("hidden");
        $(".unabledToCover").addClass("hidden");
        $("input[name=staffStatus]").val(1);
    }
    if (unit == 2) {
        //Cancelled
        if(unitId != 21){
            $(".canceled").removeClass("hidden");
            $(".unabledToCover").addClass("hidden");
        }
        $("#statusSwitch input[name=staffStatus]").val(4);
        $("#statusSwitch select[name=modeOfCancelRequest]").val(bookingData.modeOfCancelRequest);
        $("#statusSwitch input[name=ticancelTimeme]").val(bookingData.cancelTime);
        $("#statusSwitch input[name=cancelDate]").val(moment(bookingData.cancelDate, "YYYY-MM-DD").format("DD-MM-YYYY"));

        $("#statusSwitch input[name=cancelCharge]").val(bookingData.cancelCharge);
        $("#statusSwitch select[name=cancelRequestedBy]").val(bookingData.cancelRequestedBy);
        $("#statusSwitch input[name=cancelAuthorizedBy]").removeClass('hidden').val(bookingData.canceled.name);
        $("#statusSwitch textarea[name=cancelExplainedReason]").val(bookingData.cancelExplainedReason);
        $(".updateStatus").addClass("onCancel");
    }
    if (unit == 3 || unit == 5) {
        //Unable to cover
        $(".canceled").addClass("hidden");
        $(".unabledToCover").removeClass("hidden");
        $("input[name=staffStatus]").val(4);
        $("select[name=canceledOrUTCreason]").val(bookingData.canceledOrUTCreason);
    }
    if (unit == 4) {
        //confirmed
        $(".canceled").addClass("hidden");
        $(".unabledToCover").addClass("hidden");
        $("input[name=staffStatus]").val(1);
    }
}

$("#editBookingModal").on("show.bs.modal", function(e) {
    var bookId = $(e.relatedTarget).data("book-id");
    $("#editbookId").val(bookId);
    var singleUrl = $("#editBookingModal").attr("single");

    $.ajax({
        type: "POST",
        async: false,
        url: singleUrl,
        data: {
            _token: token,
            bookId: bookId
        },
        success: function(response) {
            $("select[name=shiftId]").val(response.shiftId);
            $("[name=importNotes]").val(response.importantNotes);

            $("#editBookingModal .bookingDate span").html(
                response.formatedDate
            );
            $("#editBookingModal .unitName").html(response.unitName);
            $("#editBookingModal .categoryName span").html(
                response.categoryName
            );
            $("#editBookingModal .shiftName").html(response.shiftName);
            $("#editBookingModal .startTime").html(response.startTime);
            $("#editBookingModal .endTime").html(response.endTime);
            if (response.categoryId == 1) {
                $("#editBookingModal .categoryName span")
                    .removeClass()
                    .addClass("redFont");
            } else if (response.categoryId == 3) {
                $("#editBookingModal .categoryName span")
                    .removeClass()
                    .addClass("yellowFont");
            } else {
                $("#editBookingModal .categoryName span").removeClass();
            }
            if (response.isWeekend == true) {
                $("#editBookingModal .bookingDate span")
                    .removeClass()
                    .addClass("redFont");
            } else {
                $("#editBookingModal .bookingDate span").removeClass();
            }
        }
    });
});

$("#statusSwitch").on("show.bs.modal", function(e) {
    var bookId = $(e.relatedTarget).data("book-id");
    $('input[name="bookId"]').val(bookId);
    var singleUrl = $("#statusSwitch").attr("single");

    $.ajax({
        type: "POST",
        async: false,
        url: singleUrl,
        data: {
            _token: token,
            bookId: bookId
        },
        success: function(response) {
            $("select[name=unitStatus]").val(response.unitStatus);
            bookingData = response;
            $("#statusSwitch .bookingDate span").html(bookingData.formatedDate);
            $("#statusSwitch .unitName").html(bookingData.unitName);
            $("#statusSwitch input[name=unitId]").val(bookingData.unitId);
            $("#statusSwitch .categoryName span").html(bookingData.categoryName);
            $("#statusSwitch .shiftName").html(bookingData.shiftName);
            $("#statusSwitch .startTime").html(bookingData.startTime);
            $("#statusSwitch .endTime").html(bookingData.endTime);
            if (bookingData.categoryId == 1) {
                $("#statusSwitch .categoryName span").removeClass().addClass("redFont");
            } else if (bookingData.categoryId == 3) {
                $("#statusSwitch .categoryName span").removeClass().addClass("yellowFont");
            } else {
                $("#statusSwitch .categoryName span").removeClass();
            }
            if (bookingData.isWeekend == true) {
                $("#statusSwitch .bookingDate span").removeClass().addClass("redFont");
            } else {
                $("#statusSwitch .bookingDate span").removeClass();
            }

            var unitcontacts = generateUnitContactsSelect(response.unitcontacts);
            $("select[name=cancelRequestedBy]").html(unitcontacts);
            generateUnitForms(response.unitStatus,bookingData.unitId);
            if(bookingData.unitStatus!=2){  //cancelled
              var currentDate = new Date();
              $("input[name=cancelDate]").datepicker({
                  changeMonth: true,
                  changeYear: true,
                  dateFormat: "dd-mm-yy",
                  minDate: 0,
                  firstDay: 1
              });

              $(".timepicker").timepicker({
                  timeFormat: "hh:mm p",
                  interval: 10,
                  dynamic: false,
                  dropdown: true,
                  scrollbar: true
              });
              $(".canceltime").timepicker("setTime", new Date());
            }
        }
    });
});

$("select.select2[name=unitStatus]").change(function() {
    generateUnitForms($(this).val(),$("#statusSwitch input[name=unitId]").val());
});

// SHIFT CHANGE
$(document).on("click", ".updateEdit", function() {
    var action = $("#editBookingModal").attr("action");
    var token = $("#editBookingModal").attr("token");
    $.ajax({
        type: "POST",
        async: false,
        url: action,
        data: {
            _token: token,
            bookId: $("input[name=editbookId]").val(),
            importNotes: $("[name=importNotes]").val(),
            shiftId: $("select[name=shiftId]").val()
        },
        success: function(response) {
            oTable.ajax.reload();
            $("#editBookingModal").modal("hide");
        }
    });
});
// SHIFT CHANGE

// CHANGE STATUS OF STAFF POPUP
$(document).on("click", ".updateStatus", function() {
    if ($(".updateStatus").hasClass("onCancel")) {
        $(".error").hide();
        bootbox.confirm({
            message: "Are you sure want to cancel the booking ?",
            buttons: {
                confirm: {
                    label: "Yes",
                    className: "btn-success"
                },
                cancel: {
                    label: "No",
                    className: "btn-danger"
                }
            },
            callback: function(result) {
                if (result == true) {
                    var action = $("#statusSwitch").attr("action");
                    var token = $("#statusSwitch").attr("token");
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: action,
                        data: {
                            _token: token,
                            bookId: $("input[name=bookId]").val(),
                            unitStatus: $("select[name=unitStatus]").val(),
                            staffStatus: $("input[name=staffStatus]").val(),
                            informedTo: $("select[name=informedTo]").val(),
                            modeOfCancelRequest: $("select[name=modeOfCancelRequest]").val(),
                            cancelDate: $("input[name=cancelDate]").val(),
                            cancelTime: $("input[name=cancelTime]").val(),
                            cancelRequestedBy: $("select[name=cancelRequestedBy]").val(),
                            cancelCharge: $("input[name=cancelCharge]").val(),
                            cancelExplainedReason: $("textarea[name=cancelExplainedReason]").val(),
                            canceledOrUTCreason: $("select[name=canceledOrUTCreason]").val()
                        },
                        success: function(response) {
                            oTable.ajax.reload();
                            $("#statusSwitch").modal("hide");
                        }
                    });
                }
            }
        });
    } else {
        var action = $("#statusSwitch").attr("action");
        var token = $("#statusSwitch").attr("token");
        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                bookId: $("input[name=bookId]").val(),
                unitStatus: $("select[name=unitStatus]").val(),
                staffStatus: $("input[name=staffStatus]").val(),
                modeOfCancelRequest: $(
                    "select[name=modeOfCancelRequest]"
                ).val(),
                cancelDate: $("input[name=cancelDate]").val(),
                cancelTime: $("input[name=cancelTime]").val(),
                cancelRequestedBy: $("input[name=cancelRequestedBy]").val(),
                cancelCharge: $("input[name=cancelCharge]").val(),
                cancelExplainedReason: $(
                    "textarea[name=cancelExplainedReason]"
                ).val(),
                canceledOrUTCreason: $("select[name=canceledOrUTCreason]").val()
            },
            success: function(response) {
                oTable.ajax.reload();
                $("#statusSwitch").modal("hide");
            }
        });
    }
});
// CHANGE STATUS OF STAFF POPUP

// UNIT INFORM POPUP
$("#unitInformedModal").on("show.bs.modal", function(e) {
    $(".error").hide();
    var bookId = $(e.relatedTarget).data("book-id");
    var element = $(e.relatedTarget);
    $('input[name="bookId"]').val(bookId);
    var singleUrl = $("#unitInformedModal").attr("single");

    $.ajax({
        type: "POST",
        async: false,
        url: singleUrl,
        data: {
            _token: token,
            bookId: bookId
        },
        success: function(response) {
            $("#unitInformedModal .bookingDate span").html(response.formatedDate);
            $("#unitInformedModal .unitName").html(response.unitName);
            $("#unitInformedModal .categoryName span").html(response.categoryName);
            $("#unitInformedModal .shiftName").html(response.shiftName);
            $("#unitInformedModal .startTime").html(response.startTime);
            $("#unitInformedModal .endTime").html(response.endTime);
            $("#unitInformedModal input[name=bookId]").val(response.bookingId);
            var unitcontacts = generateUnitContactsSelect(response.unitcontacts);
            $("select[name=informedTo]").html(unitcontacts);

            if(response.informUnit ==1){
                $("select[name=informedTo]").val(response.unitinformlog.informedTo);
                $("select[name=modeOfInform]").val(response.unitinformlog.modeOfInform);
                $("input[name=informedDate]").val(moment(response.unitinformlog.date, "YYYY-MM-DD").format("DD-MM-YYYY"));
                $("input[name=time]").val(response.unitinformlog.time);
                $("textarea[name=notes]").val(response.unitinformlog.notes);
            }else{
                $("input[name=informedDate]").val(moment(new Date()).format("DD-MM-YYYY")).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    firstDay: 1
                });

                $(".timepicker").timepicker({
                    timeFormat: "HH:mm",
                    interval: 60,
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true
                });
                $(".timepicker").timepicker("setTime", new Date());
                $("select[name=informedTo]").val("");
                $("select[name=modeOfInform]").val("");
                $("textarea[name=notes]").val("");
            }
        }
    });
});

$(".saveUnitInform").click(function() {
    $(".error").hide();
    var informedTo = $("#unitInformedModal select[name=informedTo]").val();
    var modeOfInform = $("#unitInformedModal select[name=modeOfInform]").val();
    var date = $("#unitInformedModal input[name=informedDate]").val();
    var time = $("#unitInformedModal input[name=time]").val();
    var notes = $("#unitInformedModal textarea[name=notes]").val();
    var bookId = $("#unitInformedModal input[name=bookId]").val();
    var action = $("#unitInformedModal").attr("action");
    invalid = 0;

    if(informedTo==""){
        $("#unitInformedModal select[name=informedTo]").next().show();
        invalid = 1;
    }
    if(modeOfInform==""){
        $("#unitInformedModal select[name=modeOfInform]").next().show();
        invalid = 1;
    }
    if(date==""){
        $("#unitInformedModal input[name=informedDate]").next().show();
        invalid = 1;
    }
    if(time==""){
        $("#unitInformedModal input[name=time]").next().show();
        invalid = 1;
    }

    if(invalid ==0){
        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                bookId: bookId,
                informedTo: informedTo,
                modeOfInform: modeOfInform,
                date: date,
                time: time,
                notes: notes
            },
            success: function(response) {
                oTable.ajax.reload();
                $("#unitInformedModal").modal("hide");
            }
        });
    }
    
});

function generateUnitContactsSelect(unitcontacts) {
    var html = "<option value=''>Select an Option</option>";
    for (var i = 0; i < unitcontacts.length; i++) {
        html +=
            "<option value='" +
            unitcontacts[i].clientUnitPhoneId +
            "'>" +
            unitcontacts[i].fullName +
            "</option>";
    }
    return html;
}

// UNIT INFORM POPUP

$(".futrRest button").tooltip({track: true});
