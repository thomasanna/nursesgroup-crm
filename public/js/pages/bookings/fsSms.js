$(document).ready(function(){
	var action = $(".box-primary").attr('action');
	var token = $(".box-primary").attr('token');
	$.ajax({
        type: "POST",
        async: false,
        url: action,
        data: {
            _token: token,
            ids: JSON.parse(localStorage.getItem("bookingsTableCheckBoxes"))
        },
        success: function(response) {
            $(".smallHdr li").html(response.heading);
        	$("textarea").val(response.sms);
        	localStorage.removeItem("bookingsTableCheckBoxes");
        }
    });
})