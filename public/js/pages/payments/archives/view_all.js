$(document).ready(function() {
    /* Accordion Start*/
    $(".accordion" ).accordion({ 
      header: "span", 
      collapsible: true, 
      active: false ,
      heightStyle: "content",
      beforeActivate: function( event, ui ) {
        var year = ui.newHeader.attr('year');
        var staff = ui.newHeader.attr('staff');
        var action = ui.newHeader.attr('single');
        var token = ui.newHeader.attr('token');
        var element = ui.newHeader;
        $.ajax({
          type: 'POST',
          async: false,
          url: action,
          data: {
            "_token": token,
            "year": year,
            "staff": staff
          },
          success: function (response) {
            element.next().html(response.html);
          }
        });
      }
    });
    /* ends */

    $(document).on('click', '.weekItem', function (e) {
        var year = $(this).attr('year');
        var staff = $(this).attr('staff');
        var week = $(this).attr('week');
        var action = $(this).attr('single');
        var token = $(this).attr('token');

        $.ajax({
          type: 'POST',
          async: false,
          url: action,
          data: {
            "_token": token,
            "yearId": year,
            "weekNum": week,
            "staffId": staff
          },
          complete: function () {
            $('#raModal').modal('show');
          },
          success: function (response) {
            $('#raModal .modal-body').html(response.html);
          }
        });
    });
});