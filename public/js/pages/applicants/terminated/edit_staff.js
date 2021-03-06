$(document).ready(function(){
  $("select.select2[name=categoryId]").select2({
    placeholder: "Select a category",
    allowClear: true
  });
  $("select.select2[name=modeOfTransport]").select2({
    placeholder: "Select a Mode Of Transport",
    allowClear: true
  });
  $("select.select2[name=branchId]").select2({
    placeholder: "Select a Branch",
    allowClear: true
  });
  $("select.select2[name=zoneId]").select2({
    placeholder: "Select a Zone",
    allowClear: true
  });
  $("select.select2[name=bandId]").select2({
    placeholder: "Select a Band",
    allowClear: true
  });
  $("select.select2[name=paymentMode]").select2({
    placeholder: "Select a Payment Mode",
    allowClear: true
  });
  $("select.select2.multiple").select2({
    placeholder: "Select a Preferred Unit",
    allowClear: true
  });

  $("select[name=selfPaymentCompanyType]").select2({
    placeholder: "Select a Type",
    allowClear: true
  });

  $("select.select2[name=branchId]").change(function(){
    var branchId = $(this).val();
    var action = $(this).attr('zone');
    var token = $(this).attr('token');
    $.ajax({
      type:'POST',
      async:false,
      url:action,
      data:{"_token": token,'branchId':branchId},
      success:function(response){
        $("select.select2[name=zoneId]").html(response);
        $("select.select2[name=zoneId]").select2({
          placeholder: "Select a Zone",
          allowClear: true
        });
      }
    });
  });

  $("select.select2[name=paymentMode]").change(function(){
    if($(this).val()==1){
      $(".paymentSelf").removeClass('hidden');
      $("select[name=selfPaymentCompanyType]").select2({
        placeholder: "Select a Type",
        allowClear: true
      });
    }else{
      $(".paymentSelf").addClass('hidden');
    }
  });

  $("form").submit(function(){
    $(".error").hide();
    var forname = $("input[name=forname]").val();
    var surname = $("input[name=surname]").val();
    var categoryId = $("select[name=categoryId]").val();
    var email = $("input[name=email]").val();
    var mobile = $("input[name=mobile]").val();
    var whatsappNumber = $("input[name=whatsappNumber]").val();
    var address = $("textarea[name=address]").val();
    var pincode = $("input[name=pincode]").val();
    var modeOfTransport = $("select[name=modeOfTransport]").val();
    var pickupLocation = $("input[name=pickupLocation]").val();
    var branchId = $("select[name=branchId]").val();
    var bandId = $("select[name=bandId]").val();
    var paymentMode = $("select[name=paymentMode]").val();
    var preferredClient = $("select.select2.multiple").val();
    var payRateWeekday = $("input[name=payRateWeekday]").val();
    var payRateWeekNight = $("input[name=payRateWeekNight]").val();
    var payRateWeekendDay = $("input[name=payRateWeekendDay]").val();
    var payRateWeekendNight = $("input[name=payRateWeekendNight]").val();
    var payRateSpecialBhday = $("input[name=payRateSpecialBhday]").val();
    var payRateSpecialBhnight = $("input[name=payRateSpecialBhnight]").val();
    var payRateBhday = $("input[name=payRateBhday]").val();
    var payRateBhnight = $("input[name=payRateBhnight]").val();

    if(forname==""){
      $("input[name=forname]").focus().next().show();
      return false;
    }
    if(surname==""){
      $("input[name=surname]").focus().next().show();
      return false;
    }
    if(categoryId==""){
      $("select[name=categoryId]").focus().next().next().show();
      return false;
    }
    if(email==""){
      $("input[name=email]").focus().next().show();
      return false;
    }
    if(email!=="" && !isEmail(email)){
      $("input[name=email]").focus().next().next().show();
      return false;
    }
    if(mobile==""){
      $("input[name=mobile]").focus().next().show();
      return false;
    }
    if(whatsappNumber==""){
      $("input[name=whatsappNumber]").focus().next().show();
      return false;
    }
    if(address==""){
      $("textarea[name=address]").focus().next().show();
      return false;
    }
    if(pincode==""){
      $("input[name=pincode]").focus().next().show();
      return false;
    }
    if(modeOfTransport==""){
      $("select[name=modeOfTransport]").focus().next().next().show();
      return false;
    }
    if(pickupLocation==""){
      $("input[name=pickupLocation]").focus().next().show();
      return false;
    }
    if(branchId==""){
      $("select[name=branchId]").focus().next().next().show();
      return false;
    }
    if(bandId==""){
      $("select[name=bandId]").focus().next().next().show();
      return false;
    }
    if(paymentMode==""){
      $("select[name=paymentMode]").focus().next().next().show();
      return false;
    }
    if(preferredClient.length==0){
      $("select.select2.multiple").focus().next().next().show();
      return false;
    }
    if(payRateWeekday==""){
      $("input[name=payRateWeekday]").focus().next().show();
      return false;
    }
    if(payRateWeekNight==""){
      $("input[name=payRateWeekNight]").focus().next().show();
      return false;
    }
    if(payRateWeekendDay==""){
      $("input[name=payRateWeekendDay]").focus().next().show();
      return false;
    }
    if(payRateWeekendNight==""){
      $("input[name=payRateWeekendNight]").focus().next().show();
      return false;
    }
    if(payRateSpecialBhday==""){
      $("input[name=payRateSpecialBhday]").focus().next().show();
      return false;
    }
    if(payRateSpecialBhnight==""){
      $("input[name=payRateSpecialBhnight]").focus().next().show();
      return false;
    }
    if(payRateBhday==""){
      $("input[name=payRateBhday]").focus().next().show();
      return false;
    }
    if(payRateBhnight==""){
      $("input[name=payRateBhnight]").focus().next().show();
      return false;
    }
  })
});
