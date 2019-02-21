$(document).ready(function() {
    var dataUrl = $(".table").attr("fetch");
    var token = $(".table").attr("token");
    var search = $(".table").attr("search");
    var setCheckStateUrl = $(".table").attr("checkUrl");
    var start = 0;
    var stateSet = 0;

    $(document).on("init.dt", function(e, settings) {
        var api = new $.fn.dataTable.Api(settings);
        var state = api.state.loaded();
        if (state) {
            stateSet = 1;
            if (state.columns[3].search.search != "") {
                $("#searchDate")
                    .val(state.columns[3].search.search)
                    .trigger("change");
                $("#searchDate")
                    .parent()
                    .parent()
                    .addClass("filterSlected");
                var inputDate = new Date(state.columns[3].search.search);
                // Get today's date
                var todaysDate = new Date();
                var tomorrowDate = new Date(
                    todaysDate.getFullYear(),
                    todaysDate.getMonth(),
                    todaysDate.getDate() + 1
                );

                // call setHours to take the time out of the comparison
                if (
                    inputDate.setHours(0, 0, 0, 0) ==
                    todaysDate.setHours(0, 0, 0, 0)
                ) {
                    $("#todaySearch").addClass("btn-warning");
                }
            }
            if (state.columns[4].search.search != "") {
                $("#searchShift")
                    .select2("destroy")
                    .val(state.columns[4].search.search)
                    .select2({
                        placeholder: "Shift",
                        allowClear: true,
                        width: "100%",
                        dropdownCssClass: "bigdrop"
                    });
                $("#searchShift")
                    .parent()
                    .parent()
                    .addClass("filterSlected");
            }
            if (state.columns[5].search.search != "") {
                $("#searchUnit")
                    .select2("destroy")
                    .val(state.columns[5].search.search)
                    .select2({
                        placeholder: "Search Unit",
                        allowClear: true,
                        width: "100%",
                        dropdownCssClass: "bigdrop"
                    });
                $("#searchUnit")
                    .parent()
                    .parent()
                    .addClass("filterSlected");
            }
            if (state.columns[6].search.search != "") {
                $("#searchCategory")
                    .select2("destroy")
                    .val(state.columns[6].search.search)
                    .select2({
                        placeholder: "Category",
                        allowClear: true,
                        width: "100%",
                        dropdownCssClass: "bigdrop"
                    });
                $("#searchCategory")
                    .parent()
                    .parent()
                    .addClass("filterSlected");
            }
            if (state.columns[7].search.search != "") {
                $("#searchStaff")
                    .select2("destroy")
                    .val(state.columns[7].search.search)
                    .select2({
                        placeholder: "Search Staff",
                        allowClear: true,
                        width: "100%",
                        dropdownCssClass: "bigdrop"
                    });
                $("#searchStaff")
                    .parent()
                    .parent()
                    .addClass("filterSlected");
            }
            if (state.columns[10].search.search != "") {
                $("#searchStaffStatus")
                    .select2("destroy")
                    .val(state.columns[10].search.search)
                    .select2({
                        placeholder: "Staff Status",
                        allowClear: true,
                        width: "100%",
                        dropdownCssClass: "bigdrop"
                    });
                $("#searchStaffStatus")
                    .parent()
                    .parent()
                    .addClass("filterSlected");
            }
            if (state.columns[9].search.search != "") {
                $("#searchUnitStatus")
                    .select2("destroy")
                    .val(state.columns[9].search.search)
                    .select2({
                        placeholder: "Search Unit Status",
                        allowClear: true,
                        width: "100%",
                        dropdownCssClass: "bigdrop"
                    });
                $("#searchUnitStatus")
                    .parent()
                    .parent()
                    .addClass("filterSlected");
            }
        }
        if (localStorage.getItem("modeOfTransport") > 0) {
            console.log("modeOfTransport",localStorage.getItem("modeOfTransport"));

            $("#modeOfTransport").select2("destroy").val(localStorage.getItem("modeOfTransport")).select2({
                placeholder: "Transport",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            }).trigger('change');
            $("#modeOfTransport").parent().parent().addClass("filterSlected");
        }
    });

    oTable = $(".table").DataTable({
        pageLength: 100,
        serverSide: true,
        bStateSave: true,
        processing: true,
        oLanguage: {sProcessing: "<div class='outerLoadr'></div><div id='loader'></div>"},
        fnStateSave: function(oSettings, oData) {
            localStorage.setItem(
                "BookingItemDataTables",
                JSON.stringify(oData)
            );
        },
        fnStateLoad: function(oSettings) {
            return JSON.parse(localStorage.getItem("BookingItemDataTables"));
        },
        ajax: {
            url: dataUrl,
            type: "post",
            headers: {
                "X-CSRF-TOKEN": token
            },
            data: function(d) {
                d.nextFourDay = $("#fourDayOption").val();
                d.transport = $("#modeOfTransport").val();
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
                data: "bookingId",
                name: "bookingId",
                orderable: false
            },
            {
                data: "type",
                name: "type",
                orderable: false
            },
            {
                data: "date",
                name: "date",
                orderable: false
            },
            {
                data: "shift.name",
                name: "shift.name",
                orderable: false
            },
            {
                data: "unit.name",
                name: "unit.name",
                orderable: false
            },
            {
                data: "category.name",
                name: "category.name",
                orderable: false
            },
            {
                data: "staff.forname",
                name: "staff.forname",
                orderable: false
            },
            {
                data: "staff.shiftlog",
                name: "staff.shiftlog",
                orderable: false
            },
            {
                data: "unitStatus",
                name: "unitStatus",
                orderable: false
            },
            {
                data: "staffStatus",
                name: "staffStatus",
                orderable: false
            },
            {
                data: "actions",
                name: "actions",
                orderable: false,
                searchable: false
            }
        ],
        drawCallback: function(response) {
            $(".comingDays").removeClass('invisible');
            $(".icheckBox").iCheck({
                checkboxClass: "icheckbox_square-red",
                radioClass: "iradio_square-red",
                increaseArea: "20%"
            });
            // CHECKBOX CHECK EVENT
            $(".icheckBox").on("ifChecked", function(event) {
                appendToLocalStorage("bookingsTableCheckBoxes",$(this).val());
            });
            // CHECKBOX UNCHECK EVENT
            $(".icheckBox").on("ifUnchecked", function(event) {
                removeFromLocalStorage("bookingsTableCheckBoxes",$(this).val());
            });

            $("div.dataTables_filter label").html('ID :  <span class="searcher"><input type="search" class="form-control input-sm"><button class="bookIdSearch m-l-10 btn btn-success">Search</button></span>');            

            start = 1;
        }
    });

    $(document).on('click','.bookIdSearch',function(){
        var bookId = $(this).prev().val();
        oTable.search(bookId).draw();
    });

    $("#searchStaffStatus").on("keyup change", function() {
        $("#fourDayOption").val(0);
        oTable
            .column("staffStatus:name")
            .search(this.value)
            .draw();
        if (this.value > 0) {
            $("#searchStaffStatus")
                .parent()
                .parent()
                .addClass("filterSlected");
        }
    });

    $("#modeOfTransport").on("keyup change", function() {
        $("#fourDayOption").val(0);
        if (this.value > 0) {
            localStorage.setItem("modeOfTransport", this.value);
            oTable
                .column("modeOfTransport")
                .search(this.value)
                .draw();
            $("#modeOfTransport")
                .parent()
                .parent()
                .addClass("filterSlected");
        } else {
            localStorage.setItem("modeOfTransport", 0);
            $("#modeOfTransport")
                .parent()
                .parent()
                .removeClass("filterSlected");
            oTable
                .column("modeOfTransport")
                .search()
                .draw();
        }
    });

    $("#searchCategory").on("keyup change", function() {
        $("#fourDayOption").val(0);
        oTable
            .column("category.name:name")
            .search(this.value)
            .draw();
        if (this.value > 0) {
            $("#searchCategory")
                .parent()
                .parent()
                .addClass("filterSlected");
        }
    });

    $("#searchStaff").on("keyup change", function() {
        $("#fourDayOption").val(0);
        oTable
            .column("staff.forname:name")
            .search(this.value)
            .draw();
        if (this.value > 0) {
            $("#searchStaff")
                .parent()
                .parent()
                .addClass("filterSlected");
        }
    });

    $("#searchUnit").on("keyup change", function() {
        $("#fourDayOption").val(0);
        oTable
            .column("unit.name:name")
            .search(this.value)
            .draw();
        if (this.value > 0 || this.value=='res') {
            $("#searchUnit")
                .parent()
                .parent()
                .addClass("filterSlected");
        }
    });

    $("#searchUnitStatus").on("keyup change", function() {
        $("#fourDayOption").val(0);
        oTable
            .column("unitStatus:name")
            .search(this.value)
            .draw();
        if (this.value > 0) {
            $("#searchUnitStatus")
                .parent()
                .parent()
                .addClass("filterSlected");
        }
    });

    $("#searchShift").on("keyup change", function() {
        $("#fourDayOption").val(0);
        oTable
            .column("shift.name:name")
            .search(this.value)
            .draw();
        if (this.value > 0) {
            $("#searchShift")
                .parent()
                .parent()
                .addClass("filterSlected");
        }
    });

    $("#searchDate").on("keyup change", function() {
        $("#searchDate")
            .parent()
            .parent()
            .addClass("filterSlected");
        $("#fourDayOption").val(0);
        var bookingDate = "";
        if (this.value != "") {
            bookingDate = moment(this.value, "DD-MM-YYYY").format("YYYY-MM-DD");
        }
        oTable
            .columns("date:name")
            .search(this.value)
            .draw();
    });

    $("#todaySearch").on("click", function() {
        //$("#todaySearch").addClass('btn-warning')
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $("#fourDayOption").val(0);
        oTable
            .columns()
            .search("")
            .draw();
        bookingDate = moment(new Date()).format("YYYY-MM-DD");
        $("#searchDate").val(
            moment(new Date()).format("DD-MM-YYYY") +
                " - " +
                moment(new Date()).format("DD-MM-YYYY")
        );
        oTable
            .column("date:name")
            .search(bookingDate + " - " + bookingDate)
            .draw();
    });
    $("#tomorrowSearch").on("click", function() {
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $("#fourDayOption").val(0);
        oTable
            .columns()
            .search("")
            .draw();
        bookingDate = moment(new Date())
            .add(1, "days")
            .format("YYYY-MM-DD");
        $("#searchDate").val(
            moment(new Date())
                .add(1, "days")
                .format("DD-MM-YYYY") +
                " - " +
                moment(new Date())
                    .add(1, "days")
                    .format("DD-MM-YYYY")
        );
        oTable
            .column("date:name")
            .search(bookingDate + " - " + bookingDate)
            .draw();
    });

    $("#newSearch").on("click", function() {
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $("#fourDayOption").val(0);
        oTable
            .columns()
            .search("")
            .draw();
        oTable
            .column("staffStatus:name")
            .search(1)
            .draw();
    });
    $("#informedSearch").on("click", function() {
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $("#fourDayOption").val(0);
        oTable
            .columns()
            .search("")
            .draw();
        oTable
            .column("staffStatus:name")
            .search(2)
            .draw();
    });
    $("#rgnSearch").on("click", function() {
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $("#fourDayOption").val(0);
        oTable
            .columns()
            .search("")
            .draw();
        oTable
            .column("category.name:name")
            .search(1)
            .draw();
    });
    $("#hcaSearch").on("click", function() {
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $("#fourDayOption").val(0);
        oTable
            .columns()
            .search("")
            .draw();
        oTable
            .column("category.name:name")
            .search(2)
            .draw();
    });
    $("#fourDaySearch").on("click", function() {
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        today = moment(new Date()).format("YYYY-MM-DD");
        nextFourDay = moment(new Date())
            .add(3, "days")
            .format("YYYY-MM-DD");
        $("#searchDate").val(
            moment(new Date()).format("DD-MM-YYYY") +
                " - " +
                moment(new Date())
                    .add(3, "days")
                    .format("DD-MM-YYYY")
        );
        oTable
            .column("date:name")
            .search(today + " - " + nextFourDay)
            .draw();

        $("#fourDayOption").val(1);
        //oTable.columns().search('').draw();
    });
    $("#searchReset").on("click", function() {
        localStorage.setItem("modeOfTransport", 0);
        $(".bgDarkBlue .col-sm-1").removeClass("filterSlected");
        $(".bgDarkBlue .col-sm-2").removeClass("filterSlected");
        // $('#searchDate').val("");
        $(
            "#todaySearch,#fourDaySearch,#hcaSearch,#rgnSearch,#informedSearch,#newSearch,#tomorrowSearch"
        ).removeClass("btn-warning");
        $("#searchUnit").select2("destroy")
            .val("")
            .select2({
                placeholder: "Search Staff Unit",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            });
        $("#searchStaff")
            .select2("destroy")
            .val("")
            .select2({
                placeholder: "Search Staff",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            });
        $("#modeOfTransport")
            .select2("destroy")
            .val("")
            .select2({
                placeholder: "Transport",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            });
        $("#searchCategory")
            .select2("destroy")
            .val("")
            .select2({
                placeholder: "Catregory",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            });
        $("#searchStaffStatus")
            .select2("destroy")
            .val("")
            .select2({
                placeholder: "Search Status",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            });
        $("#searchShift")
            .select2("destroy")
            .val("")
            .select2({
                placeholder: "Shift",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            });
        $("#searchUnitStatus")
            .select2("destroy")
            .val("")
            .select2({
                placeholder: "Search Unit Status",
                allowClear: true,
                width: "100%",
                dropdownCssClass: "bigdrop"
            });
        $("#fourDayOption").val(0);
        bookingDate = moment(new Date()).format("DD-MM-YYYY");
        $("#searchDate").val(moment(new Date()).format("DD-MM-YYYY") +" - " +moment(new Date()).format("DD-MM-YYYY")).parent().parent().addClass("filterSlected");
        oTable.search('').columns().search('').draw();
    });

    $(document).on("blur","div.dataTables_wrapper div.dataTables_filter input",function() {
        $(".table").attr("search", $(this).val());
    });

    $(document).on("click", ".bookingLog", function(e) {
        $("#BookingLogBook").modal("show");
        var token = $("#BookingLogBook").attr("token");
        var bookId = $(this).attr("bookId");
        $(".logClass .bookId").html(bookId);
        $(".logClass .staff").html($(this).attr("staff"));
        $(".logClass .date").html($(this).attr("date"));
        $(".logClass .categry").html($(this).attr("category"));
        $(".logClass .unit").html($(this).attr("unit"));
        $(".logClass .shift").html($(this).attr("shift"));
        var action = $("#BookingLogBook").attr("get-url");
        $.ajax({
            type: "POST",
            url: action,
            data: {
                _token: token,
                bookId: bookId
            },
            success: function(response) {
                $("#BookingLogBook .modal-body").html(response);
                $("#BookingLogBook .bookId").val(bookId);
            }
        });
    });

    $(".newLogEntryAction").click(function() {
        var token = $("#BookingLogBook").attr("token");
        var action = $("#BookingLogBook").attr("action");
        var bookId = $("#BookingLogBook .bookId").val();
        var contentData = $("#BookingLogBook .contentData").val();
        $.ajax({
            type: "POST",
            url: action,
            data: {
                _token: token,
                bookingId: bookId,
                content: contentData
            },
            success: function(response) {
                $("#BookingLogBook .modal-body").html(response);
                $(".contentData")
                    .val("")
                    .focus();
            }
        });
        oTable.draw();
    });

    $("#BookingLogBook .modal-content").mCustomScrollbar();

    function appendToLocalStorage(key,value){
        if (localStorage.getItem(key) === null) {
            value = JSON.stringify([value]);
            localStorage.setItem(key,value);
        }else{
            var bookIds = JSON.parse(localStorage.getItem(key));
            bookIds.push(value);
            localStorage.setItem(key,JSON.stringify(bookIds));
        }
    }

    function removeFromLocalStorage(key,value){
        var bookIds = JSON.parse(localStorage.getItem(key));
        var index = bookIds.indexOf(value);
        if (index !== -1) bookIds.splice(index, 1);
        localStorage.setItem(key,JSON.stringify(bookIds));
    }

    // window.setInterval(function(){
    //   oTable.ajax.reload();
    // }, 50000);
});
