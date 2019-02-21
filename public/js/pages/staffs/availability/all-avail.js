$(document).ready(function() {
  var dataUrl = $(".table").attr("fetch");
  var token = $(".table").attr("token");

  oTable = $(".table").DataTable({
    processing: false,
    serverSide: true,
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
        name: "category.name",
        orderable: false
      },

      {
        data: "branch.name",
        name: "branch.name",
        orderable: false
      },
      {
        data: "sms",
        name: "sms",
        orderable: false,
        searchable: false
      },
      {
        data: "avaiblity",
        name: "avaiblity",
        orderable: false
      },
      {
        data: "actions",
        name: "actions",
        orderable: false
      }
    ]
  });

  $(document).on("click", ".smsModalbtn", function() {
    var staffId = $(this).data("staff");
    $("#staffId").val(staffId);
    $(".modal-title").html(
      "Send SMS - " + $(this).attr("name") + " - " + $(this).attr("mobile")
    );
    $("#SendSmsModal").modal("show");
  });

  $("#sendStaffSms").on("click", function() {
    var staffId = $("#staffId").val();
    $("#sendStaffSms")
      .html("Sending.....Please wait..")
      .attr("disabled", "disabled");
    var message = $("#smsMessage").val();
    var token = $("#SendSmsModal").attr("token");
    var action = $("#SendSmsModal").attr("action");

    var input = {
      staffId: staffId,
      message: message,
      _token: token
    };

    if (staffId !== "") {
      $.ajax({
        type: "POST",
        async: true,
        url: action,
        data: input,
        success: function(response) {
          window.location = response.redirect;
        }
      });
    }
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
    oTable.draw();
  });

  $("#searchStaff").select2({
    placeholder: "Select Staff",
    allowClear: true,
    width: "100%"
  });
  $("#searchCategory").select2({
    placeholder: "Select Category",
    allowClear: true,
    width: "100%"
  });
  $("#searchBranch").select2({
    placeholder: "Select Branch",
    allowClear: true,
    width: "100%"
  });

  $("#searchStaff").on("keyup change", function() {
    oTable
      .column("forname:name")
      .search(this.value)
      .draw();
    if (this.value > 0) {
      $("#searchStaff")
        .parent()
        .parent()
        .addClass("filterSlected");
    }
  });

  $("#searchCategory").on("keyup change", function() {
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

  $("#searchBranch").on("keyup change", function() {
    oTable
      .column("branch.name:name")
      .search(this.value)
      .draw();
    if (this.value > 0) {
      $("#searchBranch")
        .parent()
        .parent()
        .addClass("filterSlected");
    }
  });

  $("#searchReset").click(function() {
    $(".bgDarkBlue .col-sm-2").removeClass("filterSlected");
    $("#searchCategory")
      .select2("destroy")
      .val("")
      .select2({
        placeholder: "Select Category",
        allowClear: true,
        width: "100%",
        dropdownCssClass: "bigdrop"
      })
      .trigger("change");

    $("#searchStaff")
      .select2("destroy")
      .val("")
      .select2({
        placeholder: "Select Staff",
        allowClear: true,
        width: "100%",
        dropdownCssClass: "bigdrop"
      })
      .trigger("change");

    $("#searchBranch")
      .select2("destroy")
      .val("")
      .select2({
        placeholder: "Select Branch",
        allowClear: true,
        width: "100%",
        dropdownCssClass: "bigdrop"
      })
      .trigger("change");
  });
});
