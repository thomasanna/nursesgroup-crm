$(document).ready(function() {
  $("select.select2[name=nextAction]").select2({
    placeholder: "Select an Action",
    allowClear: true
  });
  $("select.select2[name=cancelImapctFactor]").select2({
    placeholder: "Select a IF",
    allowClear: true
  });
  $("select.select2[name=cancelInformUnit]").select2({
    placeholder: "Select an Option",
    allowClear: true
  });
  $("select.select2[name=cancelAuthorizedBy]").select2({
    placeholder: "Select an Option",
    allowClear: true
  });
  /*
    $("select.select2[name=nextAction]").on("change", function(e) {


      bootbox.confirm({
        message: "Are you sure want to confirm?",
        buttons: {
          confirm: {
            label: 'Yes',
            className: 'btn-success'
          },
          cancel: {
            label: 'Cancel',
            className: 'btn-danger'
          }
        },
        callback: function(result) {
          if (result == true) {
            $("#changeConfirmForm").submit();
          }
        }
      });

    });
  */
  $("[name=cancelDate]").on("change", function(e) {
    var date = $(this).val().trim();
    var time = $("[name=cancelTime]").val().trim();
    if (date != "") {
      var cancelDateTime = date + " " + time;
      var startTime = $("#bookingDateTime").val().trim();

      var date1 = moment(cancelDateTime, ['DD-MM-YYYY hh:mm a', 'DD-MM-YYYY']);
      var date2 = moment(startTime);
      var diff = date2.diff(date1, 'hours');
      $("[name=timeDiffrence]").val(diff + ' hours');

    }
  });

  $("[name=cancelTime]").on("time-change", function() {
    var date = $("[name=cancelDate]").val().trim();
    var time = $(this).val().trim();
    if (date != "") {
      var cancelDateTime = date + " " + time;
      var startTime = $("#bookingDateTime").val().trim();

      var date1 = moment(cancelDateTime, ['DD-MM-YYYY hh:mm a', 'DD-MM-YYYY']);
      var date2 = moment(startTime);
      var diff = date2.diff(date1, 'hours');
      $("[name=timeDiffrence]").val(diff + ' hours');
    }
  });

  $(".datepicker").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    minDate: 0,
    firstDay: 1
  });

  $('.timepicker').timepicker({
    timeFormat: 'hh:mm p',
    interval: 15,
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });

});