$(document).ready(function(){
  $("select.select2[name=type]").select2({
    placeholder: "Select a Type",
    allowClear: true
  });

  $("form").submit(function(){
    $(".error").hide();
    var name = $("input[name=name]").val();
    var description = $("textarea[name=description]").val();
    var read = $("input[name=read]").val();
    var write = $("input[name=write]").val();
    var delete = $("input[name=delete]").val();
    var edit = $("input[name=edit]").val();
    var viewall = $("input[name=viewall]").val();
    var manageall = $("input[name=manageall]").val();

    if(name==""){
      $("input[name=name]").focus().next().show();
      return false;
    }
    if(description==""){
      $("textarea[name=description]").focus().next().show();
      return false;
    }
//     if(phone==""){
//       $("input[name=phone]").focus().next().show();
//       return false;
//     }
//     if(mobile==""){
//       $("input[name=mobile]").focus().next().show();
//       return false;
//     }
//     if(email==""){
//       $("input[name=email]").focus().next().show();
//       return false;
//     }
//     if(email!=="" && !isEmail(email)){
//       $("input[name=email]").focus().next().next().show();
//       return false;
//     }
//     if(type==""){
//       $("select[name=type]").focus().next().next().show();
//       return false;
//     }
//     if(personInCharge==""){
//       $("input[name=personInCharge]").focus().next().show();
//       return false;
//     }
//   })
});