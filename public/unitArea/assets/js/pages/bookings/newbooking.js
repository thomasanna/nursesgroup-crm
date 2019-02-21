$(document).ready(function(){ 
    $('.requestedDate').datepicker({
	    startDate:new Date(),
	    locale: {
	      format: 'DD-MM-YYYY'
	    }
    });

    $('.categorySel2').select2({
	    placeholder: "Category",
	    allowClear: true,
	    width: '100%'
    });

   $('.shiftSel2').select2({
	    placeholder: "Shift",
	    allowClear: true,
	    width: '100%'
   });

    $(document).on('click', '.add-new-booking', function (e) {
	    var actionUrl = $("#newBooking").attr("action");
	    var token = $("#newBooking").attr('token');
	    var basUrl = $("#newBooking").attr('baseUrl');
	    if($('input[name=requestedDate]').val() == ""){
	      $('.errmsg').css('display','block');
	      return false;
	    }
	    else if($("#newBookingForm select[name=shiftId]").val() == "" ){
	      $("#newBookingForm select[name=ts_No]").focus().next().next().show();
	      $('.errmsg').css('display','block');
	      return false;
	    } else if($("#newBookingForm select[name=categoryId]").val() == "" ) {
	      $("#newBookingForm select[name=categoryId]").focus().next().next().show();
	      $('.errmsg').css('display','block');
	      return false;
	    } else{
	      $('.errmsg').css('display','none');
	      $.ajax({
	      type: 'POST',
	      async: false,
	      url: actionUrl,
	      data: {
	        "_token": token,
	        "requestedDate"  :$('input[name=requestedDate]').val(),
	        "shiftId"  :$('select[name=shiftId]').val(),
	        "categoryId"  :$('select[name=categoryId]').val(),
	        "numbers"  :$("select[name='numbers[]']").val(),
	      },
	      complete: function () {
	      },
	      success: function (response) {
	       window.location.href = basUrl+"/unit-area/bookings";
	      }
	    });
	  }
  });

});