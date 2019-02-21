function loadView(params) {
    fetchUri = $("#entryView").data("view-uri");
    token = $("#entryView").data("token");
    $.ajax({
        url: fetchUri,
        method: "GET",
        data: {
            _token: token,
            startDate: params.date,
            staffId: params.staffId
        },
        beforeSend: function() {
            $(".loading,.modelPophldr").show();
        },
        complete: function() {
            $(".loading,.modelPophldr").hide();
        },
        success: function(response) {
            $("#entryView").html(response.content);
            $("#viewControl").data("prev", response.prev);
            $("#viewControl").data("next", response.next);
            $("#viewControl").removeClass("hidden");

            $('input[type="checkbox"].minimal').iCheck({
                checkboxClass: "icheckbox_minimal-blue"
            });
        }
    });
}

$(document).ready(function() {
    var url = $(location).attr("href"),
    parts = url.split("/");   
    last_part = parts[parts.length - 1];
    var staffId = last_part;
    if (staffId != "" && staffId > 0) {
        var now = moment().format("YYYY-MM-DD");
        loadView({ staffId: staffId, date: now });
    } else {
        $("#entryView").html("");
        $("#viewControl").addClass("hidden");
        $("#viewControl").data("prev", "");
        $("#viewControl").data("next", "");
    }

    $("#prevView").on("click", function() {
        //var staffId = $("#staffs option:selected").val();
        if (staffId != "" && staffId > 0) {
            var now = $("#viewControl").data("prev");
            loadView({ staffId: staffId, date: now });
        } else {
            $("#entryView").html("");
            $("#viewControl").addClass("hidden");
            $("#viewControl").data("prev", "");
            $("#viewControl").data("next", "");
        }
    });
    $("#nextView").on("click", function() {
        //var staffId = $("#staffs option:selected").val();
        if (staffId != "" && staffId > 0) {
            var now = $("#viewControl").data("next");
            loadView({ staffId: staffId, date: now });
        } else {
            $("#entryView").html("");
            $("#viewControl").addClass("hidden");
            $("#viewControl").data("prev", "");
            $("#viewControl").data("next", "");
        }
    });

    $("body").on(
        "ifChecked ifUnchecked",
        'input[type="checkbox"].minimal',
        function(event) {
            var element = $(this);

            var dataValue = event.type == "ifChecked" ? 1 : 0;
            var dataType = $(this).data("type");
            var dataDate = $(this).data("day");
            var url = $(location).attr("href"),
            parts = url.split("/");   
            last_part = parts[parts.length - 1];
            var staffId = last_part;
            if (staffId == "") {
                $("#message-error").show(500);
                var cual = this;
                setTimeout(function() {
                    $(cual).iCheck("uncheck");
                }, 1);
                setTimeout(function() {
                    $(this)
                        .parent()
                        .removeClass("checked");
                    $("#message-error").hide(500);
                }, 3000);
                return false;
            } else {
                saveUri = $("#entryView").data("save-uri");
                token = $("#entryView").data("token");

                $.ajax({
                    url: saveUri,
                    method: "POST",
                    data: {
                        _token: token,
                        data: dataValue,
                        date: dataDate,
                        staffId: staffId,
                        type: dataType
                    },
                    success: function() {
                        switch (dataType) {
                            case "early":
                                var prevNight = $(element)
                                    .parents(".currDay")
                                    .prev()
                                    .find(
                                        'input[type="checkbox"].minimal.night'
                                    );
                                var prevLate = $(element)
                                    .parents(".currDay")
                                    .prev()
                                    .find(
                                        'input[type="checkbox"].minimal.late'
                                    );
                                var currAbsent = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.absent'
                                    );
                                var prevAbsent = $(element)
                                    .parents(".currDay")
                                    .prev()
                                    .find(
                                        'input[type="checkbox"].minimal.absent'
                                    );
                                if (event.type == "ifChecked") {
                                    $(currAbsent).iCheck("disable");
                                    $(prevNight).iCheck("disable");
                                } else {
                                    if (
                                        !$(prevLate).is(":checked") &&
                                        !$(prevAbsent).is(":checked")
                                    ) {
                                        $(prevNight).iCheck("enable");
                                    }
                                    var currEarly = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.early'
                                        );
                                    var currLate = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.late'
                                        );
                                    var currNight = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.night'
                                        );
                                    if (
                                        !$(currEarly).is(":checked") &&
                                        !$(currLate).is(":checked") &&
                                        !$(currNight).is(":checked")
                                    ) {
                                        $(currAbsent).iCheck("enable");
                                    }
                                }
                                break;
                            case "late":
                                var nextNight = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.night'
                                    );
                                var nextEarly = $(element)
                                    .parents(".currDay")
                                    .next()
                                    .find(
                                        'input[type="checkbox"].minimal.early'
                                    );
                                var currAbsent = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.absent'
                                    );
                                if (event.type == "ifChecked") {
                                    $(currAbsent).iCheck("disable");
                                    $(nextNight).iCheck("disable");
                                } else {
                                    if (
                                        !$(nextNight).hasClass(
                                            "lastDisabled"
                                        ) &&
                                        !$(nextEarly).is(":checked")
                                    ) {
                                        $(nextNight).iCheck("enable");
                                    }
                                    var currEarly = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.early'
                                        );
                                    var currLate = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.late'
                                        );
                                    var currNight = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.night'
                                        );
                                    if (
                                        !$(currEarly).is(":checked") &&
                                        !$(currLate).is(":checked") &&
                                        !$(currNight).is(":checked")
                                    ) {
                                        $(currAbsent).iCheck("enable");
                                    }
                                }
                                break;
                            case "night":
                                var prevLate = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.late'
                                    );
                                var nextEarly = $(element)
                                    .parents(".currDay")
                                    .next()
                                    .find(
                                        'input[type="checkbox"].minimal.early'
                                    );
                                var currAbsent = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.absent'
                                    );
                                var nextAbsent = $(element)
                                    .parents(".currDay")
                                    .next()
                                    .find(
                                        'input[type="checkbox"].minimal.absent'
                                    );

                                if (event.type == "ifChecked") {
                                    $(currAbsent).iCheck("disable");
                                    $(prevLate).iCheck("disable");
                                    $(nextEarly).iCheck("disable");
                                } else {
                                    $(prevLate).iCheck("enable");
                                    if (!$(nextAbsent).is(":checked")) {
                                        $(nextEarly).iCheck("enable");
                                    }
                                    var currEarly = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.early'
                                        );
                                    var currLate = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.late'
                                        );
                                    var currNight = $(element)
                                        .parents(".currDay")
                                        .find(
                                            'input[type="checkbox"].minimal.night'
                                        );
                                    if (
                                        !$(currEarly).is(":checked") &&
                                        !$(currLate).is(":checked") &&
                                        !$(currNight).is(":checked")
                                    ) {
                                        $(currAbsent).iCheck("enable");
                                    }
                                }
                                break;
                            case "absent":
                                var currEarly = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.early'
                                    );
                                var currLate = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.late'
                                    );
                                var currNight = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.night'
                                    );
                                if (event.type == "ifChecked") {
                                    $(currEarly).iCheck("disable");
                                    $(currLate).iCheck("disable");
                                    $(currNight).iCheck("disable");
                                } else {
                                    $(currLate).iCheck("enable");
                                    var nextEarly = $(element)
                                        .parents(".currDay")
                                        .next()
                                        .find(
                                            'input[type="checkbox"].minimal.early'
                                        );
                                    if (
                                        !$(currNight).hasClass(
                                            "lastDisabled"
                                        ) &&
                                        !$(nextEarly).is(":checked")
                                    ) {
                                        $(currNight).iCheck("enable");
                                    }
                                    var prevNight = $(element)
                                        .parents(".currDay")
                                        .prev()
                                        .find(
                                            'input[type="checkbox"].minimal.night'
                                        );
                                    if (
                                        !$(currEarly).hasClass(
                                            "firstDisabled"
                                        ) &&
                                        !$(prevNight).is(":checked")
                                    ) {
                                        $(currEarly).iCheck("enable");
                                    }
                                }
                                break;

                            case "both":
                                var currEarly = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.early'
                                    );
                                var currLate = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.late'
                                    );
                                var currNight = $(element)
                                    .parents(".currDay")
                                    .find(
                                        'input[type="checkbox"].minimal.night'
                                    );
                                if (event.type == "ifChecked") {
                                    $(currEarly).iCheck("disable");
                                    $(currLate).iCheck("disable");
                                    $(currNight).iCheck("disable");
                                } else {
                                    $(currLate).iCheck("enable");
                                    var nextEarly = $(element)
                                        .parents(".currDay")
                                        .next()
                                        .find(
                                            'input[type="checkbox"].minimal.early'
                                        );
                                    if (
                                        !$(currNight).hasClass(
                                            "lastDisabled"
                                        ) &&
                                        !$(nextEarly).is(":checked")
                                    ) {
                                        $(currNight).iCheck("enable");
                                    }
                                    var prevNight = $(element)
                                        .parents(".currDay")
                                        .prev()
                                        .find(
                                            'input[type="checkbox"].minimal.night'
                                        );
                                    if (
                                        !$(currEarly).hasClass(
                                            "firstDisabled"
                                        ) &&
                                        !$(prevNight).is(":checked")
                                    ) {
                                        $(currEarly).iCheck("enable");
                                    }
                                }
                                break;
                        }
                    }
                });
            }
        }
    );
});
