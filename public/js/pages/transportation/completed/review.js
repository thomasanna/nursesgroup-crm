$(document).ready(function(){
	$(".extractIt").click(function(){
	    var action = $(this).attr('action');
	    var token = $(this).attr('token');
	    var payeeWeek = $(this).attr('payeeweek');
	    var driverId = $(this).attr('driverid');
	    var date = $(this).attr('date');
	    $.ajax({
	      type: 'post',
	      async: false,
	      url: action,
	      data: {
	        "_token": token,
	        "payeeWeek": payeeWeek,
	        "driverId": driverId,
	        "date": date,
	      },
	      success: function(response) {
	      	$("#tripExtract .modal-body").html(response);
	      	$("#tripExtract").modal();
	      }
	    });
	});
});