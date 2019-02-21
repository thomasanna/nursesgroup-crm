$(document).ready(function(){
    $("select.select2[name=type]").select2({
      placeholder: "Select a Type",
      allowClear: true
    });
    $("select.select2[name=clientId]").select2({
      placeholder: "Select a Client",
      allowClear: true
    });
    $("select.select2[name=branchId]").select2({
      placeholder: "Select a Branch",
      allowClear: true
    });
    $("select.select2.multiple").select2({
      placeholder: "Select Zones",
      allowClear: true
    });
    $("select.select2[name=agencyUsageLevelHCA]").select2({
      placeholder: "Select a Agency Usage Level - HCA",
      allowClear: true
    });
    $("select.select2[name=agencyUsageLevelRGN]").select2({
      placeholder: "Select a Agency Usage Level - RGN",
      allowClear: true
    });
    $("select.select2[name=agencyUsageLevelOthers]").select2({
      placeholder: "Select a Agency Usage Level - Others",
      allowClear: true
    });
    $("select.select2[name=invoiceFrequency]").select2({
      placeholder: "Select a Invoice Frequency",
      allowClear: true
    });
  
    $("select.select2[name=branchId]").change(function(){
      var branchId = $(this).val();
      var zoneAction = $(this).attr('zones');
      var token = $(this).attr('token');
      // GET ZONES
      $.ajax({
        type:'POST',
        async:false,
        url:zoneAction,
        data:{"_token": token,'branchId':branchId},
        beforeSend: function () {
          $('.loading,.modelPophldr').show();
        },
        success:function(response){
          $("select.select2.multiple").html(response);
          $("select.select2.multiple").select2({
            placeholder: "Select Units",
            allowClear: true
          });
          // $("select.select2.multiple > option").prop("selected","selected");
          // $("select.select2.multiple").trigger("change");
        },
        complete: function () {
          $('.loading,.modelPophldr').hide();
        }
      });
    });
  
    
  });
  