$(document).ready(function(){
	var action = $(".injectHere").attr('action');
	var token = $(".injectHere").attr('token');
	$.ajax({
        type: "POST",
        async: false,
        url: action,
        data: {
            _token: token,
            ids: JSON.parse(localStorage.getItem("bookingsTableCheckBoxes"))
        },
        success: function(response) {
        	$(".injectHere").html(response.html);
        	$(".box-title strong").html(response.unitAlias);
        	localStorage.removeItem("bookingsTableCheckBoxes");
        }
    });
})