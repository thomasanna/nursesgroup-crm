$('.datepicker').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
  });

$("select[name=bankId]").select2({
	placeholder:'Select Bank'
});
$("select[name=handledBy]").select2();