$("input[name=paymentDate]").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
});

$("select[name=handledBy],select[name=bankId]").select2();