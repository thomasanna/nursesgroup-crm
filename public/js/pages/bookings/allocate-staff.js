$(document).ready(function() {
  $("select.select2[name=inClubId]").select2({
    placeholder: "Trip Club",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=outClubId]").select2({
    placeholder: "Trip Club",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=staffId]").select2({
    placeholder: "Select a Staff",
    allowClear: true
  });
  $("select.select2[name=staffAllocateStatus]").select2({
    placeholder: "Select a Staff Status",
    allowClear: true
  });
  $("select.select2[name=finalConfirm]").select2({
    placeholder: "Select a final confirmation",
    allowClear: true
  });
  $("select.select2[name=modeOfTransport]").select2({
    placeholder: "Select a Mode of Transport",
    allowClear: true
  });
  $("select.select2[name=bonusReason]").select2({
    placeholder: "Select a Reason",
    allowClear: true
  });
  $("select.select2[name=taReason]").select2({
    placeholder: "Select a Reason",
    allowClear: true
  });
  $("select.select2[name=bonus]").select2({
    placeholder: "Select a Bonus",
    allowClear: true
  });

  $("select.select2[name=outboundDriverType]").select2({
    placeholder: "Select a Driver Type",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=outboundDriverType1]").select2({
    placeholder: "Select a Driver",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=outboundDriverType2]").select2({
    placeholder: "Select a Driver",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=outboundDriverType3]").select2({
    placeholder: "Select a Driver",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=inboundDriverType]").select2({
    placeholder: "Select a Driver Type",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=inboundDriverType1]").select2({
    placeholder: "Select a Driver",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=inboundDriverType2]").select2({
    placeholder: "Select a Driver",
    allowClear: true,
    width: '100%'
  });
  $("select.select2[name=inboundDriverType3]").select2({
    placeholder: "Select a Driver",
    allowClear: true,
    width: '100%'
  });

  $("select.select2[name=outboundDriverType]").on("change", function(e) {

    var data = $(this).val();
    if (data == 1) {
      $("select.select2[name=outboundDriverType1]").parent('.form-group').removeClass('hidden');
      $("select.select2[name=outboundDriverType2]").parent('.form-group').addClass('hidden');
      $("select.select2[name=outboundDriverType3]").parent('.form-group').addClass('hidden');
      $("select.select2[name=outClubId]").parent('.form-group').removeClass('hidden');
      $(".outboundDiv").removeClass('hidden');

    } else if (data == 2) {
      $("select.select2[name=outboundDriverType1]").parent('.form-group').addClass('hidden');
      $("select.select2[name=outboundDriverType2]").parent('.form-group').removeClass('hidden');
      $("select.select2[name=outboundDriverType3]").parent('.form-group').addClass('hidden');
      $("select.select2[name=outClubId]").parent('.form-group').removeClass('hidden');
      $(".outboundDiv").removeClass('hidden');

    } else if (data == 3) {
      $("select.select2[name=outboundDriverType1]").parent('.form-group').addClass('hidden');
      $("select.select2[name=outboundDriverType2]").parent('.form-group').addClass('hidden');
      $("select.select2[name=outboundDriverType3]").parent('.form-group').removeClass('hidden');
      $("select.select2[name=outClubId]").parent('.form-group').removeClass('hidden');
      $(".outboundDiv").removeClass('hidden');

    } else {
      console.log("here");
      $(".outboundDiv").addClass('hidden');
      $("select.select2[name=outClubId]").parent('.form-group').addClass('hidden');

    }


  });
  $("select.select2[name=inboundDriverType]").on("change", function(e) {

    var data = $(this).val();
    if (data == 1) {
      $("select.select2[name=inboundDriverType1]").parent('.form-group').removeClass('hidden');
      $("select.select2[name=inboundDriverType2]").parent('.form-group').addClass('hidden');
      $("select.select2[name=inboundDriverType3]").parent('.form-group').addClass('hidden');
      $("select.select2[name=inClubId]").parent('.form-group').removeClass('hidden');

      $(".inboundDiv").removeClass('hidden');

    } else if (data == 2) {
      $("select.select2[name=inboundDriverType1]").parent('.form-group').addClass('hidden');
      $("select.select2[name=inboundDriverType2]").parent('.form-group').removeClass('hidden');
      $("select.select2[name=inboundDriverType3]").parent('.form-group').addClass('hidden');
      $("select.select2[name=inClubId]").parent('.form-group').removeClass('hidden');
      $(".inboundDiv").removeClass('hidden');

    } else if (data == 3) {
      $("select.select2[name=inboundDriverType1]").parent('.form-group').addClass('hidden');
      $("select.select2[name=inboundDriverType2]").parent('.form-group').addClass('hidden');
      $("select.select2[name=inboundDriverType3]").parent('.form-group').removeClass('hidden');
      $("select.select2[name=inClubId]").parent('.form-group').removeClass('hidden');
      $(".inboundDiv").removeClass('hidden');

    } else {
      $(".inboundDiv").addClass('hidden');
      $("select.select2[name=inClubId]").parent('.form-group').addClass('hidden');

    }


  });
  $("select.select2[name=modeOfTransport]").on("change", function(e) {
    var data = $(this).val();
    if (data != 2) {
      $("#transportDiv").addClass("hidden");
      $(".selfDiv").removeClass("hidden");
      var staffId = $("[name=staffId]").val();
      var bookingId = $('#bookingId').val();
      var getStaffTA = $('#staffTAURL').val();
      var token = $('#csrfToken').val();
      var input = {
        'staffId': staffId,
        'bookingId': bookingId,
        '_token': token,
      };
      if (staffId !== "") {
        $('.loading,.modelPophldr').show();
        $.ajax({
          type: 'POST',
          async: true,
          url: getStaffTA,
          data: input,
          complete: function(response) {
            //$('[name=transportAllowence]').val(response.responseJSON.transportAllowence).trigger("change");
            $('.loading,.modelPophldr').hide();
            //Todo fetch extra TA from db
          }
        });
      }
    } else {
      $("#transportDiv").removeClass("hidden");
      $(".selfDiv").addClass("hidden");
      $('#extraTA').val(0);
      $('[name=transportAllowence]').val(0).trigger('change');
    }
  });
  $("select.select2[name=staffId]").on("change", function(e) {
    var staffId = $(this).val();

    var bookingId = $('#bookingId').val();
    var getStaffInfoURL = $('#staffInfoURL').val();
    var token = $('#csrfToken').val();

    var input = {
      'staffId': staffId,
      'bookingId': bookingId,
      '_token': token,
    };

    if (staffId !== "") {
      $.ajax({
        type: 'POST',
        async: true,
        url: getStaffInfoURL,
        data: input,
        complete: function(response) {

          $('select.select2[name=modeOfTransport]').val(response.responseJSON.modeOfTransport);
          $('select.select2[name=modeOfTransport]').trigger('change');
          $('#staffRate').val(response.responseJSON.shiftCost);
          //Todo fetch extra TA from db
          $('[name=transportAllowence]').val(response.responseJSON.transportAllowence).trigger('change');
          $('[name=outPickTime]').val(response.responseJSON.outBoundPickupTime);


        }
      });
    }

  });

  $("[name=transportAllowence]").on("keyup change", function(e) {
    var transportAllowence = Number($(this).val());
    var bonus = Number($("[name=bonus]").val());
    var extraTA = Number($("#extraTA").val());
    var diffInHours = Number($("#diffInHours").val());

    var staffRate = Number($("input[name=staffRateOrgnal]").val());

    var total = (transportAllowence*diffInHours) + (extraTA*diffInHours) + (bonus * diffInHours) + (staffRate*diffInHours);
    // var total = (transportAllowence*diffInHours) + (extraTA*diffInHours) + (bonus * diffInHours) + (staffRate*diffInHours);
    total = parseFloat(total);
    if ((transportAllowence != "") || (transportAllowence == 0)) {
      $('[name=totalAmount]').val(total);
    }

    $("input[name=staffRate]").val(transportAllowence+extraTA+staffRate);
  });

  $("[name=bonus]").on("keyup change", function(e) {

    var bonus = Number($(this).val());
    var diffInHours = Number($("#diffInHours").val());
    var extraTA = Number($("#extraTA").val());
    var transportAllowence = Number($("[name=transportAllowence]").val());
    var staffRate = Number($("#staffRate").val());

    var total = (transportAllowence*diffInHours) + (extraTA*diffInHours) + (bonus * diffInHours) + (staffRate*diffInHours);
    total = parseFloat(total);

    if ((bonus != "") || (bonus == 0)) {
      $('[name=totalAmount]').val(total);
    }
  });

  $("#staffRate").on("change", function(e) {
    console.log("Triggered");
    var staffRate = Number($(this).val());
    var bonus = Number($("[name=bonus]").val());
    var diffInHours = Number($("#diffInHours").val());
    var extraTA = Number($("#extraTA").val());
    var transportAllowence = Number($("[name=transportAllowence]").val());

    var total = (transportAllowence*diffInHours) + (extraTA*diffInHours) + (bonus * diffInHours) + (staffRate*diffInHours);
    total = parseFloat(total).toFixed(2);

    if ((staffRate != "") || (staffRate == 0)) {
      $('[name=totalAmount]').val(total);
    }
  });

  $("#extraTA").on("keyup change", function(e) {
    var extraTA = Number($(this).val());
    var bonus = Number($("[name=bonus]").val());
    var diffInHours = Number($("#diffInHours").val());
    var staffRate = Number($("#staffRate").val());
    var transportAllowence = Number($("[name=transportAllowence]").val());

    var total = (transportAllowence*diffInHours) + (extraTA*diffInHours) + (bonus * diffInHours) + (staffRate*diffInHours);
    total = parseFloat(total).toFixed(2);

    if ((extraTA != "") || (extraTA == 0)) {
      $('[name=totalAmount]').val(total);
    }
    $("input[name=staffRate]").val(extraTA+transportAllowence+staffRate);
  });

  $("input[name=aggreedHrRate]").on("keyup change", function(e) {
    var current = $("input[name=staffRate]").val();
    var aggreed = $(this).val();
    $("[name=bonus]").val(Number(aggreed-current)).trigger("change");;

  });

  initialLoad();

  $("select[name=outboundDriverType1]").change(function(){
    var thisVal = $(this).val();
    var token = $('#csrfToken').val();
    var action = $(this).attr('action');
    var day = $(this).attr('day');
    $('.loading,.modelPophldr').show();
    $.ajax({
      type: 'POST',
      async: true,
      url: action,
      data: {'driverId':thisVal,'_token': token,'day':day},
      success: function (response) {
        $('.loading,.modelPophldr').hide();
        $("select[name=outClubId]").html(response);
      }
    });
  });

  $("select[name=inboundDriverType1]").change(function(){
    var thisVal = $(this).val();
    var token = $('#csrfToken').val();
    var action = $(this).attr('action');
    var day = $(this).attr('day');
    $('.loading,.modelPophldr').show();
    $.ajax({
      type: 'POST',
      async: true,
      url: action,
      data: {'driverId':thisVal,'_token': token,'day':day},
      success: function (response) {
        $('.loading,.modelPophldr').hide();
        $("select[name=inClubId]").html(response);
      }
    });
  });

});

function initialLoad()
{
  var staffId = $("[name=staffId]").val();

  var bookingId = $('#bookingId').val();
  var getStaffInfoURL = $('#staffInfoURL').val();
  var token = $('#csrfToken').val();

  var input = {
    'staffId': staffId,
    'bookingId': bookingId,
    '_token': token,
  };
  if (staffId !== "") {
    $('.loading,.modelPophldr').show();
    $.ajax({
      type: 'POST',
      async: true,
      url: getStaffInfoURL,
      data: input,
      complete: function (response) {

      //  $('[name=staffRate]').val(response.responseJSON.shiftCost);
        $('[name=outPickTime]').val(response.responseJSON.outBoundPickupTime);
        $('[name=distanceToWorkplace]').val(response.responseJSON.travelDistance);
        $('[name=transportAllowence]').val(response.responseJSON.transportAllowence).trigger('change');
        $('.loading,.modelPophldr').hide();

      }
    });
  }



}
