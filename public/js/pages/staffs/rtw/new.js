$(document).ready(function(){
  $("select.select2[name=nokRelationship]").select2({
    placeholder: "Select a Relationship",
    allowClear: true
  });

  $("select.select2[name=nationality]").select2({
    placeholder: "Select a Nationality",
    allowClear: true
  });
  $("select.select2[name=switchProgress]").select2({
    placeholder: "Select a Nationality",
    allowClear: true
  });

  var visatype = $(".formRtW").attr('visatype');
  if(visatype==2){
    $(".border span").html("Non UK or EU National – Need Valid visa or Work Permit to Work");
    $("select.select2[name=visaType]").select2({
      placeholder: "Select a Type",
      allowClear: true
    });
    $("select.select2[name=visaExternalVerificationRequired]").select2({
      placeholder: "Select an Option",
      allowClear: true
    });
  }

  $(`select.select2[name=rightToWorkWithoutPermission],
    select.select2[name=disciplinaryProcedure],
    select.select2[name=pendingInvestigation],
    select.select2[name=status]`).select2({
    placeholder: "Select an Option",
    allowClear: true
  });


  $( `input[name=passportDateOfIssue],
    input[name=passportExpiryDate],
    input[name=visaDateOfIssue],
    input[name=visaExpiryDate],
    input[name=visaFollowUpDate],
    input[name=checkedDate]`).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy',
      firstDay: 1,
    });

    $("select[name=nationality]").change(function(){
      var type = $('select[name=nationality] option:selected').attr('data-type')
      if(type==1){
        $(".border").addClass("greenBorder");
        $(".border").removeClass("redBorder");
        $(".border span").html("UK or EU National - Right to work in the UK Without Visa");
        $(".visa").addClass('hidden');
      }
      if(type==2){
        $(".border").addClass("redBorder");
        $(".border").removeClass("greenBorder");
        $(".border span").html("Non UK or EU National – Need Valid visa or Work Permit to Work");
        $(".visa").removeClass('hidden');
        $("select.select2[name=visaType]").select2({
          placeholder: "Select a Type",
          allowClear: true
        });
        $("select.select2[name=visaExternalVerificationRequired]").select2({
          placeholder: "Select an Option",
          allowClear: true
        });
      }
    });


    $(document).on('change', 'select.select2[name=switchProgress]', function() {
      var action = $(this).attr('action');
      var token = $(this).attr('token');
      var staff = $(this).attr('staff');
      var progress = $(this).val();

      bootbox.confirm({
        message: "Are you sure want to change the progress ?",
        buttons: {
          confirm: {
            label: 'Yes',
            className: 'btn-success'
          },
          cancel: {
            label: 'Cancel',
            className: 'btn-danger'
          }
        },
        callback: function(result) {
          if (result == true) {
            $.ajax({
              type: 'POST',
              async: false,
              url: action,
              data: {
                "page": 2, // for RTW
                "staff": staff,
                "progress": progress,
                "_token": token
              },
              success: function(response) {
                location.reload();
              }
            });
          }
        }
      });
    });

});
