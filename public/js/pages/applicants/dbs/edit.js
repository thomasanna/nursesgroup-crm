$(document).ready(function() {
  $(`select.select2[name=dbsType]`).select2({
    placeholder: "Select a Type",
    allowClear: true
  });

  $("select.select2[name=apctnPaidBy]").select2({
    placeholder: "Select a Paid By",
    allowClear: true
  });

  $("select.select2[name=apctnStatus]").select2({
    placeholder: "Select an Option",
    allowClear: true
  });
  $("select.select2[name=validType]").select2({
    placeholder: "Select an Option",
    allowClear: true
  });
  $("select.select2[name=updateServiceStatus]").select2({
    placeholder: "Select an Option",
    allowClear: true
  });

  $(`select.select2[name=validPoliceRecordsOption],
    select.select2[name=validSection142Option],
    select.select2[name=validChildActListOption],
    select.select2[name=validVulnerableAdultOption],
    select.select2[name=validCpoRelevantOption],
    select.select2[name=updateServicePoliceRecordsOption],
    select.select2[name=updateServiceSection142Option],
    select.select2[name=updateServiceChildActListOption],
    select.select2[name=updateServiceVulnerableAdultOption],
    select.select2[name=updateServiceCpoRelevantOption]
    `).select2({
    placeholder: "Select an Option",
    allowClear: true
  });

  $("input[name=apctnFollowUpDate],input[name=apctnAppliedDate],input[name=validIssueDate],input[name=updateServiceCheckedDate]").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
  });

  $("select.select2[name=apctnStatus]").change(function() {
    var type = $(this).val();
    switch (type) {
      case '3':
        $(".beta").removeClass('hidden');
        $(".alpha,.gamma").addClass('hidden');
        $("select.select2[name=validType]").select2({
          placeholder: "Select a Type",
          allowClear: true
        });
        $("input[name=dbsType]").val(2);
        $(`select.select2.dbsType`).val(2).trigger('change');
        break;
    }
  });
});