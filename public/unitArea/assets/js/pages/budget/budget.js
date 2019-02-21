$(document).ready(function(){
  $(document).on('click', '.add-month-budget', function (e) {
    var actionUrl = $("#setBudgetModal").attr("action");
    var token = $("#setBudgetModal").attr('token');
    var budget0 = $('input[name=budget0]').val();
    var budget1 = $('input[name=budget1]').val();
    var budget2 = $('input[name=budget2]').val();
    var budget3 = $('input[name=budget3]').val();

   // if(budget0 !="" && budget1 !="" && budget2 !="" && budget3 != ""){
      $.ajax({
      type: 'POST',
      async: false,
      url: actionUrl,
      data: {
        "_token": token,
        "month0"  :$('input[name=month0]').val(),
        "budget0"  :$('input[name=budget0]').val(),
        "month1"  :$('input[name=month1]').val(),
        "budget1"  :$('input[name=budget1]').val(),
        "month2"  :$('input[name=month2]').val(),
        "budget2"  :$('input[name=budget2]').val(),
        "month3"  :$('input[name=month3]').val(),
        "budget3"  :$('input[name=budget3]').val(),
      },
      complete: function () {
      },
      success: function (response) {
        $('.errmsg').css('display','none');
        $('#setBudgetModal').modal('hide');

      }
     });


  });

  $(document).on( 'init.dt', function ( e, settings ) {
  var api = new $.fn.dataTable.Api( settings );
  var state = api.state.loaded();
});

   var dataUrl = $(".table").attr('fetch');
   var token = $(".table").attr('token');
   oTable = $('.table').DataTable({
      processing: false,
      serverSide: true,
      ajax: {
          url: dataUrl,
          type: 'post',
          headers: {'X-CSRF-TOKEN': token}

      },
      columns: [
          {data: 'DT_Row_Index', name: 'bookingId','orderable': false},
          {data: 'booking.date', name: 'booking.date', orderable: false},
          {data: 'category.name', name: 'category.name', orderable: false},
          {data: "staff.forname",name: "staff.forname", orderable: false},
          {data: 'shift.name', name: 'shift.name', orderable: false},
          // {data: 'actions', name: 'actions', orderable: false, searchable: false},
      ],
  });

   $.ajax({
    type: "GET",
    async: false,
    url: $("#container").attr("action"),
    success: function(response) {
       $('.graph1 .hcaamt').html('£ '+response.confirmed.hcaAmount); 
       $('.graph1 .rgnamt').html('£ '+response.confirmed.rgnAmount); 
       $('.graph1 .totalamt').html('£ '+response.confirmed.totalAmount); 
       Highcharts.chart('container', {

        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: response.confirmed.categories
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: 'Number of Bookings'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: [{
                    name: 'HCA',
                    data: response.confirmed.hca,
                    stack: 'male'
                },
                {
                    name: 'RGN',
                    data: response.confirmed.rgn,
                    stack: 'male'
              }]
        });
    }
  });

   $.ajax({
    type: "GET",
    async: false,
    url: $("#container1").attr("action"),
    success: function(response) {
        $('.graph2 .hcaamt').html('£ '+response.confirmed.hcaAmount); 
        $('.graph2 .rgnamt').html('£ '+response.confirmed.rgnAmount); 
        $('.graph2 .totalamt').html('£ '+response.confirmed.totalAmount); 
        Highcharts.chart('container1', {

        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: response.confirmed.categories
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: 'Number of Bookings'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: [{
                    name: 'HCA',
                    data: response.confirmed.hca,
                    stack: 'male'
                },
                {
                    name: 'RGN',
                    data: response.confirmed.rgn,
                    stack: 'male'
              }]
        });
    }
  });

  $('.monthSel').select2({
    placeholder: "Month",
    allowClear: true,
    width: '100%'
  });

  $('.monthSel').on('change', function () {
    var month = $('.monthSel').val();
    var actionUrl = $(".monthSel").attr("action");
    var token     = $(".monthSel").attr("token");
    $.ajax({
      type: 'POST',
      async: false,
      url: actionUrl,
      data: {
       "_token": token,
        "month"  : month
      },
      complete: function () {
      },
      success: function (response) {
      // Graph1 starts
       $('.graph1').html('');
       $('.graph1').html(response.graph.first.html);
       $('.graph1 .hcaamt').html('£ '+response.graph.first.hcaAmount); 
       $('.graph1 .rgnamt').html('£ '+response.graph.first.rgnAmount); 
       $('.graph1 .totalamt').html('£ '+response.graph.first.totalAmount); 

        Highcharts.chart('container', {

        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: response.graph.first.categories
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: 'Number of Bookings'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: [{
                    name: 'HCA',
                    data: response.graph.first.hca,
                    stack: 'male'
                },
                {
                    name: 'RGN',
                    data: response.graph.first.rgn,
                    stack: 'male'
              }]
        });

        // Graph1 ends

        $('.graph2').html('');
        $('.graph2').html(response.graph.second.html);
        $('.graph2 .hcaamt').html('£ '+response.graph.second.hcaAmount); 
        $('.graph2 .rgnamt').html('£ '+response.graph.second.rgnAmount); 
        $('.graph2 .totalamt').html('£ '+response.graph.second.totalAmount); 

        Highcharts.chart('container1', {

        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: response.graph.second.categories
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: 'Number of Bookings'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: [{
                    name: 'HCA',
                    data: response.graph.second.hca,
                    stack: 'male'
                },
                {
                    name: 'RGN',
                    data: response.graph.second.rgn,
                    stack: 'male'
              }]
        });
    }
     });
  });

  $('.monthSel ').on('change', function () {
    oTable.column('booking.date:name').search(this.value).draw();
    var elment = $(".monthSel").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });
});
