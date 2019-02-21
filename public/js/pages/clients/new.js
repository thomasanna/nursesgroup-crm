$(document).ready(function(){
  $("select.select2[name=typeOfCompany]").select2({
    placeholder: "Select a Type",
    allowClear: true
  });


  // $("form").submit(function(){
  //   $(".error").hide();
  //   var name = $("input[name=name]").val();
  //   var phone = $("input[name=phone]").val();
  //   var altPhoneNumber = $("input[name=altPhoneNumber]").val();
  //   var email = $("input[name=email]").val();
  //   var personInCharge = $("input[name=personInCharge]").val();
  //   var address = $("textarea[name=address]").val();
  //   var branchId = $("select[name=branchId]").val();
  //   var ta = $("input[name=transportAllowance]").val();
  //   var zoneId = $("select.multiple").val();
  //   var shiftRateWeekday = $("input[name=shiftRateWeekday]").val();
  //   var shiftRateSpecialBHday = $("input[name=shiftRateSpecialBHday]").val();
  //   var shiftRateWeekNight = $("input[name=shiftRateWeekNight]").val();
  //   var shiftRateSpecialBHnight = $("input[name=shiftRateSpecialBHnight]").val();
  //   var shiftRateWeekendDay = $("input[name=shiftRateWeekendDay]").val();
  //   var shiftRateBHday = $("input[name=shiftRateBHday]").val();
  //   var shiftRateWeekendNight = $("input[name=shiftRateWeekendNight]").val();
  //   var shiftRateBHnight = $("input[name=shiftRateBHnight]").val();
  //
  //   if(name==""){
  //     $("input[name=name]").focus().next().show();
  //     return false;
  //   }
  //   if(phone==""){
  //     $("input[name=phone]").focus().next().show();
  //     return false;
  //   }
  //   if(altPhoneNumber==""){
  //     $("input[name=altPhoneNumber]").focus().next().show();
  //     return false;
  //   }
  //   if(email==""){
  //     $("input[name=email]").focus().next().show();
  //     return false;
  //   }
  //   if(email!=="" && !isEmail(email)){
  //     $("input[name=email]").focus().next().next().show();
  //     return false;
  //   }
  //   if(personInCharge==""){
  //     $("input[name=personInCharge]").focus().next().show();
  //     return false;
  //   }
  //   if(address==""){
  //     $("textarea[name=address]").focus().next().show();
  //     return false;
  //   }
  //   if(branchId==""){
  //     $("select[name=branchId]").focus().next().next().show();
  //     return false;
  //   }
  //   if(ta==""){
  //     $("input[name=transportAllowance]").focus().next().show();
  //     return false;
  //   }
  //   if(zoneId.length==0){
  //     $("select[name=zoneId]").focus().next().next().show();
  //     return false;
  //   }
  //   if(shiftRateWeekday==""){
  //     $("input[name=shiftRateWeekday]").focus().next().show();
  //     return false;
  //   }
  //   if(shiftRateSpecialBHday==""){
  //     $("input[name=shiftRateSpecialBHday]").focus().next().show();
  //     return false;
  //   }
  //   if(shiftRateWeekNight==""){
  //     $("input[name=shiftRateWeekNight]").focus().next().show();
  //     return false;
  //   }
  //   if(shiftRateSpecialBHnight==""){
  //     $("input[name=shiftRateSpecialBHnight]").focus().next().show();
  //     return false;
  //   }
  //   if(shiftRateWeekendDay==""){
  //     $("input[name=shiftRateWeekendDay]").focus().next().show();
  //     return false;
  //   }
  //   if(shiftRateBHday==""){
  //     $("input[name=shiftRateBHday]").focus().next().show();
  //     return false;
  //   }
  //   if(shiftRateWeekendNight==""){
  //     $("input[name=shiftRateWeekendNight]").focus().next().show();
  //     return false;
  //   }
  //   if(shiftRateBHnight==""){
  //     $("input[name=shiftRateBHnight]").focus().next().show();
  //     return false;
  //   }
  // })
});
