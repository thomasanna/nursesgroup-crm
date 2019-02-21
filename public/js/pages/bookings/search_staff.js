$(document).ready(function() {
    $("select.select2[name=staffStatus]").select2({
        placeholder: "Select a Status",
        allowClear: true
    });

    $("[name=searchUpdate]").on("blur", function() {
        var updateUrl = $(this).attr("searchUpdateUrl");
        var bookingId = $("[name=bookingId]").val();
        var updateInfo = $(this).val();

        $.ajax({
            type: "post",
            async: false,
            url: updateUrl,
            data: {
                _token: token,
                bookingId: bookingId,
                updateInfo: updateInfo
            },
            success: function(response) {}
        });
    });

    var dataUrl = $("#avialableStaffTable").attr("fetch");
    var token = $("#avialableStaffTable").attr("token");
    var setCheckStateUrl = $("#avialableStaffTable").attr("checkUrl");
    var avialableStaffTable = $("#avialableStaffTable").DataTable({
        processing: true,
        serverSide: true,
        lengthChange: false,
        oLanguage: {sProcessing: "<div id='loader'></div>"},
        pageLength: 50,
        ajax: {
            url: dataUrl,
            type: "post",
            headers: {
                "X-CSRF-TOKEN": token
            }
        },
        columns: [
            {
                data: "checkbox",
                name: "checkbox",
                orderable: false,
                searchable: false
            },
            {
                data: "forname",
                name: "forname",
                orderable: false
            },
            {
                data: "mobile",
                name: "mobile",
                orderable: false
            },
            {
                data: "category.name",
                name: "mobile",
                orderable: false
            },
            {
                data: "sms",
                name: "sms",
                orderable: false,
                searchable: false
            },
            {
                data: "actions",
                name: "actions",
                orderable: false,
                searchable: false
            },
            {
                data: "logStatus",
                name: "mobile",
                orderable: false
            },
            {
                data: "log",
                name: "forname",
                orderable: false
            },
            {
                data: "avaiblity",
                name: "forname",
                orderable: false
            },
            {
                data: "curentSts",
                name: "forname",
                orderable: false
            },{
                data: "hw",
                name: "hw",
                orderable: false,
                searchable: false
            }
        ],
        drawCallback: function() {
            $(".icheckBox").iCheck({
                checkboxClass: "icheckbox_square-red",
                radioClass: "iradio_square-red",
                increaseArea: "20%"
            });
            // CHECKBOX CHECK EVENT
            $(".icheckBox").on("ifChecked", function(event) {
                var thisVal = $(this).val();
                var data = {
                    _token: token,
                    type: 1,
                    staff: thisVal
                };
                setCheckedState(data);
            });
            // CHECKBOX UNCHECK EVENT
            $(".icheckBox").on("ifUnchecked", function(event) {
                var thisVal = $(this).val();
                var data = {
                    _token: token,
                    type: 0,
                    staff: thisVal
                };
                setCheckedState(data);
            });

            $("#avialableStaffTable .assign-btn").on("click", function() {
                var staffId = $(this).data("staff-id");
                var assignUrl = $("#assignUrl").val();
                var bookingId = $("#bookingId").val();
                var search = $(".mainHead").attr("search");
                var page = $(".mainHead").attr("page");

                bootbox.confirm({
                    message: "Are you sure want to assign this staff?",
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
                                type: "post",
                                async: false,
                                url: assignUrl,
                                data: {
                                    _token: token,
                                    bookingId: bookingId,
                                    staffId: staffId,
                                    search: search,
                                    page: page
                                },
                                success: function(response) {
                                    bootbox.alert(
                                        "Succesfully Assigned Potential Staff",
                                        function() {
                                            window.location = response.url;
                                        }
                                    );
                                }
                            });
                        }
                    }
                });
            });
        }
    });

    // PRIORITY STAFFS
    $(".priorityStaff-tab").one("click", function() {
        var dataPriorityUrl = $("#priorityStaffTable").attr("fetch");
        var token = $("#priorityStaffTable").attr("token");

        var priorityStaffTable = $("#priorityStaffTable").DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            oLanguage: {sProcessing: "<div id='loader'></div>"},
            pageLength: 50,
            ajax: {
                url: dataPriorityUrl,
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": token
                }
            },
            columns: [
                {
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "forname",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "mobile",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "category.name",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "sms",
                    name: "sms",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "logStatus",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "log",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "avaiblity",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "curentSts",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "hw",
                    name: "hw",
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function() {
                $(".icheckBox").iCheck({
                    checkboxClass: "icheckbox_square-red",
                    radioClass: "iradio_square-red",
                    increaseArea: "20%"
                });
                // CHECKBOX CHECK EVENT
                $(".icheckBox").on("ifChecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 1,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });
                // CHECKBOX UNCHECK EVENT
                $(".icheckBox").on("ifUnchecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 0,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });

                $("#priorityStaffTable .assign-btn").on("click", function() {
                    var staffId = $(this).data("staff-id");
                    var assignUrl = $("#assignUrl").val();
                    var bookingId = $("#bookingId").val();
                    var search = $(".mainHead").attr("search");
                    var page = $(".mainHead").attr("page");

                    bootbox.confirm({
                        message: "Are you sure want to assign this staff?",
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
                                    type: "post",
                                    async: false,
                                    url: assignUrl,
                                    data: {
                                        _token: token,
                                        bookingId: bookingId,
                                        staffId: staffId,
                                        search: search,
                                        page: page
                                    },
                                    success: function(response) {
                                        bootbox.alert(
                                            "Succesfully Assigned Potential Staff",
                                            function() {
                                                window.location = response.url;
                                            }
                                        );
                                    }
                                });
                            }
                        }
                    });
                });
            }
        });
    });
    // PRIORITY STAFFS
    $(".permenentStaffs-tab").one("click", function() {
        // PERMANENT STAFFS
        var dataPermanentUrl = $("#permenentStaffsTable").attr("fetch");
        var token = $("#permenentStaffsTable").attr("token");

        var permanentStaffTable = $("#permenentStaffsTable").DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            oLanguage: {sProcessing: "<div id='loader'></div>"},
            pageLength: 15,
            ajax: {
                url: dataPermanentUrl,
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": token
                }
            },
            columns: [
                {
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "forname",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "mobile",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "category.name",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "sms",
                    name: "sms",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "logStatus",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "log",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "avaiblity",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "curentSts",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "hw",
                    name: "hw",
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function() {
                $(".icheckBox").iCheck({
                    checkboxClass: "icheckbox_square-red",
                    radioClass: "iradio_square-red",
                    increaseArea: "20%"
                });
                // CHECKBOX CHECK EVENT
                $(".icheckBox").on("ifChecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 1,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });
                // CHECKBOX UNCHECK EVENT
                $(".icheckBox").on("ifUnchecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 0,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });

                $("#permenentStaffsTable .assign-btn").on("click", function() {
                    var staffId = $(this).data("staff-id");
                    var assignUrl = $("#assignUrl").val();
                    var bookingId = $("#bookingId").val();
                    var search = $(".mainHead").attr("search");
                    var page = $(".mainHead").attr("page");

                    bootbox.confirm({
                        message: "Are you sure want to assign this staff?",
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
                                    type: "post",
                                    async: false,
                                    url: assignUrl,
                                    data: {
                                        _token: token,
                                        bookingId: bookingId,
                                        staffId: staffId,
                                        search: search,
                                        page: page
                                    },
                                    success: function(response) {
                                        bootbox.alert(
                                            "Succesfully Assigned Potential Staff",
                                            function() {
                                                window.location = response.url;
                                            }
                                        );
                                    }
                                });
                            }
                        }
                    });
                });
            }
        });
    });
    // PERMANENT STAFFS

    // PREVIOUSLY WORKED STAFFS
    $(".previouslyWorked-tab").one("click", function() {
        var dataPrevWorkUrl = $("#previouslyWorkedStaffTable").attr("fetch");
        var token = $("#previouslyWorkedStaffTable").attr("token");

        var prevoislyWorkedStaffTable = $(
            "#previouslyWorkedStaffTable"
        ).DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            oLanguage: {sProcessing: "<div id='loader'></div>"},
            pageLength: 50,
            ajax: {
                url: dataPrevWorkUrl,
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": token
                }
            },
            columns: [
                {
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "forname",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "mobile",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "category.name",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "sms",
                    name: "sms",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "logStatus",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "log",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "avaiblity",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "curentSts",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "hw",
                    name: "hw",
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function() {
                $(".icheckBox").iCheck({
                    checkboxClass: "icheckbox_square-red",
                    radioClass: "iradio_square-red",
                    increaseArea: "20%"
                });
                // CHECKBOX CHECK EVENT
                $(".icheckBox").on("ifChecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 1,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });
                // CHECKBOX UNCHECK EVENT
                $(".icheckBox").on("ifUnchecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 0,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });

                $("#previouslyWorkedStaffTable .assign-btn").on(
                    "click",
                    function() {
                        var staffId = $(this).data("staff-id");
                        var assignUrl = $("#assignUrl").val();
                        var bookingId = $("#bookingId").val();
                        var search = $(".mainHead").attr("search");
                        var page = $(".mainHead").attr("page");

                        bootbox.confirm({
                            message: "Are you sure want to assign this staff?",
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
                                        type: "post",
                                        async: false,
                                        url: assignUrl,
                                        data: {
                                            _token: token,
                                            bookingId: bookingId,
                                            staffId: staffId,
                                            search: search,
                                            page: page
                                        },
                                        success: function(response) {
                                            bootbox.alert(
                                                "Succesfully Assigned Potential Staff",
                                                function() {
                                                    window.location =
                                                        response.url;
                                                }
                                            );
                                        }
                                    });
                                }
                            }
                        });
                    }
                );
            }
        });
    });
    // PREVIOUSLY WORKED STAFFS

    // STAFF IN THIS ZONE
    $(".inThisZone-tab").one("click", function() {
        var dataInZoneUrl = $("#staffInThisZoneTable").attr("fetch");
        var token = $("#staffInThisZoneTable").attr("token");

        var staffInThisZoneTable = $("#staffInThisZoneTable").DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            oLanguage: {sProcessing: "<div id='loader'></div>"},
            pageLength: 15,
            ajax: {
                url: dataInZoneUrl,
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": token
                }
            },
            columns: [
                {
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "forname",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "mobile",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "category.name",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "sms",
                    name: "sms",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "logStatus",
                    name: "mobile",
                    orderable: false
                },
                {
                    data: "log",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "avaiblity",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "curentSts",
                    name: "forname",
                    orderable: false
                },
                {
                    data: "hw",
                    name: "hw",
                    orderable: false,
                    searchable: false
                }
            ],

            drawCallback: function(response) {
                
                $(".icheckBox").iCheck({
                    checkboxClass: "icheckbox_square-red",
                    radioClass: "iradio_square-red",
                    increaseArea: "20%"
                });
                // CHECKBOX CHECK EVENT
                $(".icheckBox").on("ifChecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 1,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });
                // CHECKBOX UNCHECK EVENT
                $(".icheckBox").on("ifUnchecked", function(event) {
                    var thisVal = $(this).val();
                    var data = {
                        _token: token,
                        type: 0,
                        staff: thisVal
                    };
                    setCheckedState(data);
                });

                $("#staffInThisZoneTable .assign-btn").on("click", function() {
                    var staffId = $(this).data("staff-id");
                    var assignUrl = $("#assignUrl").val();
                    var bookingId = $("#bookingId").val();
                    var search = $(".mainHead").attr("search");
                    var page = $(".mainHead").attr("page");

                    bootbox.confirm({
                        message: "Are you sure want to assign this staff?",
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
                                    type: "post",
                                    async: false,
                                    url: assignUrl,
                                    data: {
                                        _token: token,
                                        bookingId: bookingId,
                                        staffId: staffId,
                                        search: search,
                                        page: page
                                    },
                                    success: function(response) {
                                        bootbox.alert(
                                            "Succesfully Assigned Potential Staff",
                                            function() {
                                                window.location = response.url;
                                            }
                                        );
                                    }
                                });
                            }
                        }
                    });
                });
            },
        });
    });

    // STAFF IN THIS ZONE

    $(".checkAll").iCheck({
        checkboxClass: "icheckbox_square-red",
        radioClass: "iradio_square-red",
        increaseArea: "20%"
    });

    // CHECKBOX CHECK EVENT
    $(".checkAll").on("ifChecked", function(event) {
        var thisVal = $(this).val();
        var bookingId = $("#bookingId").val();

        var data = {
            _token: token,
            type: 2,
            staff: thisVal,
            booking: bookingId
        };
        setCheckedState(data);
    });
    // CHECKBOX UNCHECK EVENT
    $(".checkAll").on("ifUnchecked", function(event) {
        var thisVal = $(this).val();
        var data = {
            _token: token,
            type: 3,
            staff: thisVal
        };
        setCheckedState(data);
    });

    function setCheckedState(input) {
        $.ajax({
            type: "POST",
            async: false,
            url: setCheckStateUrl,
            data: input,
            beforeSend: function() {
                $(".loading,.modelPophldr").show();
            },
            success: function(response) {
                if (response.length > 0) {
                    $(".sendSms").removeClass("hidden");
                } else {
                    $(".sendSms").addClass("hidden");
                }
                $("#selectedStaffs").data("ids", response);
            },
            complete: function() {
                $(".loading,.modelPophldr").hide();
                avialableStaffTable.ajax.reload();
            }
        });
    }

    $(document).on("click", ".sendSmsAction", function() {
        var action = $("#previewSms").attr("action");
        var token = $("#previewSms").attr("token");
        var bookId = $("#previewSms").attr("bookid");
        var search = $(".mainHead").attr("search");
        var page = $(".mainHead").attr("page");

        $(".sendSmsAction")
            .html("Sending SMS...Please Wait...")
            .attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                message: $("textarea[name=message]").val(),
                bookingId: bookId,
                search: search,
                page: page
            },
            beforeSend: function() {
                $(".loading,.modelPophldr").show();
            },
            success: function(response) {
                window.location = response.redirect;
            },
            complete: function() {
                $(".loading,.modelPophldr").hide();
            }
        });
    });

    $("#previewSms").on("show.bs.modal", function() {
        var staffs = $("#selectedStaffs").data("ids");

        var allStaffs = "";
        staffs.forEach(function(staff) {
            allStaffs +=
                staff.forname +
                " " +
                staff.surname +
                "&nbsp;|&nbsp;&nbsp;&nbsp;";
        });
        $("#smsStaffs").html(allStaffs);
    });

    $(".previewSmsSpotAction").click(function() {
        var action = $("#previewSmsSpot").attr("action");
        var token = $("#previewSmsSpot").attr("token");
        var bookId = $("#previewSmsSpot").attr("bookid");
        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                shiftConfirmation: $("textarea[name=spotMessage]").val(),
                bookingId: bookId,
                from: 1
            },
            beforeSend: function() {
                $(".loading,.modelPophldr").show();
            },
            success: function(response) {
                bootbox.alert("Succesfully Send SMS !!");
                $("#previewSmsSpot").modal("hide");
                $(".sptSmsNow")
                    .removeClass("btn-danger")
                    .addClass("btn-success")
                    .html("SENT");
            },
            complete: function() {
                $(".loading,.modelPophldr").hide();
            }
        });
    });

    $(document).on("click", ".openLogModal", function(e) {
        $("#staffLogBook").modal("show");
        var token = $("#staffLogBook").attr("token");
        var staffId = $(this).attr("staffid");
        $(".staffName").html($(this).attr("staffname"));
        $(".catgry").html($(this).attr("category"));
        var action = $("#staffLogBook").attr("get-url");
        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                staffId: staffId
            },
            success: function(response) {
                $("#staffLogBook .modal-body").html(response);
            }
        });
        $("#staffLogBook .staffId").val(staffId);
    });

    $(".newLogEntryAction").click(function() {
        var token = $("#staffLogBook").attr("token");
        var action = $("#staffLogBook").attr("action");
        var staffId = $("#staffLogBook .staffId").val();
        var contentData = $("#staffLogBook .contentData").val();
        var priority = $("#staffLogBook .priority").val();
        $.ajax({
            type: "POST",
            async: false,
            url: action,
            data: {
                _token: token,
                staffId: staffId,
                content: contentData,
                priority: priority
            },
            success: function(response) {
                $("#staffLogBook .modal-body").html(response);
                $(".contentData")
                    .val("")
                    .focus();
            }
        });
        avialableStaffTable.draw();
        priorityStaffTable.draw();
        permanentStaffTable.draw();
        prevoislyWorkedStaffTable.draw();
        staffInThisZoneTable.draw();
    });

    $("#changeStaffStatus").on("submit", function() {
        if ($("#staffId").val() != "" && $("#staffId").val() != "0") {
            return true;
        } else {
            $("[name=staffStatus]")
                .parent(".form-group")
                .find(".text-danger")
                .remove();
            $("[name=staffStatus]")
                .parent(".form-group")
                .append("<p class='text-danger'>* Staff Required</p>");
            return false;
        }
    });

    $("#staffLogBook .modal-content").mCustomScrollbar();

    $(document).on("click", ".unavailBtn", function() {
        var staffId = $(this).data("staff-id");
        var date = $(this).attr("date");
        var action = $(this).attr("action");

        bootbox.confirm({
            message: "Are you sure want to make this staff to unavailable ?",
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
                        type: "POST",
                        async: false,
                        url: action,
                        data: {
                            _token: token,
                            staffId: staffId,
                            date: date
                        },
                        success: function(response) {
                            avialableStaffTable.draw();
                        }
                    });
                }
            }
        });
    });

    $(document).on('click','.openAvailablty',function(){
        $("#emptyModal").modal();
        var action = $("#emptyModal").attr("get-availabilty-url");
        var token = $("#emptyModal").attr("token");
        var staff = $(this).attr("staff");
        $.ajax({
            type: "POST",
            url: action,
            data: {
                _token: token,
                staffId: staff,
            },
            success: function(response) {
                $("#emptyModal .modal-body").html(response);
            }
        });
    });

    $(document).on('click','.openHistry',function(){
        $("#emptyModal").modal();
        var action = $("#emptyModal").attr("get-history-url");
        var token = $("#emptyModal").attr("token");
        var staff = $(this).attr("staff");
        $.ajax({
            type: "POST",
            url: action,
            data: {
                _token: token,
                staffId: staff,
            },
            success: function(response) {
                $("#emptyModal .modal-body").html(response);
            }
        });
    });
});
