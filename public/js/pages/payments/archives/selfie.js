$(document).ready(function() {

  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');
  var setCheckStateUrl = $(".table").attr('checkUrl');

  $(document).on( 'init.dt', function ( e, settings ) {
    var api = new $.fn.dataTable.Api( settings );
    var state = api.state.loaded();

    if (state) {
      if(state.columns[2].search.search!=""){
        $('#searchStaff').select2('destroy').val(state.columns[2].search.search).select2({
          placeholder: "Staff",
          allowClear: true,
          width: '100%',
        });
        $("#searchStaff").parent().parent().addClass('filterSlected');
      }
    }
  });

  oTable = $('.table').DataTable({
    pageLength: 50,
    processing: false,
    serverSide: true,
    bStateSave: true,
    fnStateSave: function (oSettings, oData) {
      localStorage.setItem('selfieWeeksTable', JSON.stringify(oData));
    },
    fnStateLoad: function (oSettings) {
      return JSON.parse(localStorage.getItem('selfieWeeksTable'));
    },
    ajax: {
      url: dataUrl,
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [{
        data: 'DT_Row_Index',
        name: 'DT_Row_Index',
        orderable: false
      },
      {
        data: 'payment.booking.staff.name',
        name: 'payment.booking.staff.name',
        orderable: false
      },
      {
        data: 'amountTotal',
        name: 'amountTotal',
        orderable: false
      },
      {
        data: 'numberOfBookings',
        name: 'numberOfBookings',
        orderable: false
      },
      {
        data: 'actions',
        name: 'actions',
        orderable: false,
        searchable: false
      }
    ],
  });

  $('#searchStaff').select2({
    placeholder: "Staff",
    allowClear: true,
    width: '100%'
  });

  $('#searchStaff').on('change', function () {
    oTable.column('payment.booking.staff.name:name').search(this.value).draw();
    var elment = $("#searchStaff").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });


  $('#searchReset').on('click', function () {

    $(".bgDarkBlue .col-sm-2").removeClass('filterSlected');
    $(".bgDarkBlue .col-sm-1").removeClass('filterSlected');

    $('#searchStaff').select2('destroy').val("").select2({
      placeholder: "Staff",
      allowClear: true,
      width: '100%',
    });


    oTable.columns().search('').draw();

  });
});
