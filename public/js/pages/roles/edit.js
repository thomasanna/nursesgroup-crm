// $(document).ready(function(){
//   $("select.select2[name=type]").select2({
//     placeholder: "Select a Type",
//     allowClear: true
//   });

  $("form").submit(function(){
    $(".error").hide();
    var name = $("input[name=taxYearFrom]").val();
    var guard_name= $("input[name=taxYearTo]").val();
   
  

    if(name==""){
      $("input[name=taxYearFrom]").focus().next().show();
      return false;
    }
 
    }
    if(guard==""){
      $("input[name=taxYearTo]").focus().next().show();
      return false;
    }
 
  })
});