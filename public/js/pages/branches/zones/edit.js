$(document).ready(function(){
  $("form").submit(function(){
    $(".error").hide();
    var name = $("input[name=name]").val();

    if(name==""){
      $("input[name=name]").focus().next().show();
      return false;
    }
  })
});
