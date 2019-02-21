$(document).ready(function(){
  $("select.select2[name=type]").select2({
    placeholder: "Select a Type",
    allowClear: true
  });

  $("form").submit(function(){
    $(".error").hide();
    var name = $("input[name=name]").val();
    var address = $("textarea[name=address]").val();
    var phone = $("input[name=phone]").val();
    var mobile = $("input[name=mobile]").val();
    var email = $("input[name=email]").val();
    var type = $("select[name=type]").val();
    var personInCharge = $("input[name=personInCharge]").val();

    if(name==""){
      $("input[name=name]").focus().next().show();
      return false;
    }
    if(address==""){
      $("textarea[name=address]").focus().next().show();
      return false;
    }
    if(phone==""){
      $("input[name=phone]").focus().next().show();
      return false;
    }
    if(mobile==""){
      $("input[name=mobile]").focus().next().show();
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
    if(type==""){
      $("select[name=type]").focus().next().next().show();
      return false;
    }
    if(personInCharge==""){
      $("input[name=personInCharge]").focus().next().show();
      return false;
    }
  })
});
