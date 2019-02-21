$(document).ready(function() {
  $("input[name=dateOfIssue]").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
  });
  $("input[name=dateOfExpiry]").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
  });
  $("input[name=validFrom]").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
  });
  $("input[name=validTo]").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
  });
});