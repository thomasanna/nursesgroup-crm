$(document).ready(function() {

  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');
  var setCheckStateUrl = $(".table").attr('checkUrl');

  $(document).on( 'init.dt', function ( e, settings ) {
    var api = new $.fn.dataTable.Api( settings );
    var state = api.state.loaded();

    if (state) {
      if(state.columns[0].search.search!=""){
        $('#searchUnit').select2('destroy').val(state.columns[0].search.search).select2({
          placeholder: "Week",
          allowClear: true,
          width: '100%',
        });
        $("#searchUnit").parent().parent().addClass('filterSlected');
      }
      if(state.columns[1].search.search!=""){
        $('#weekSelect').select2('destroy').val(state.columns[1].search.search).select2({
          placeholder: "Week",
          allowClear: true,
          width: '100%',
        });
        $("#weekSelect").parent().parent().addClass('filterSlected');
      }
    }
  });


  oTable = $('.table').DataTable({
    pageLength: 50,
    processing: false,
    serverSide: true,
    bStateSave: true,
    fnStateSave: function (oSettings, oData) {
      localStorage.setItem('UnitBillsTable', JSON.stringify(oData));
    },
    fnStateLoad: function (oSettings) {
      return JSON.parse(localStorage.getItem('UnitBillsTable'));
    },
    ajax: {
      url: dataUrl,
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [
      // {
      //   data: 'booking.bookingId',
      //   name: 'booking.bookingId',
      //   orderable: false,
      //   searchable: false
      // },
      // {
      //   data: 'booking.date',
      //   name: 'booking.date',
      //   orderable: false
      // },
      // {
      //   data: 'booking.shift.name',
      //   name: 'booking.shift.name',
      //   orderable: false
      // },
      {
        data: 'booking.unit.alias',
        name: 'booking.unit.alias',
        orderable: false
      },
      // {
      //   data: 'log',
      //   name: 'log',
      //   orderable: false
      // },
      // {
      //   data: 'booking.category.name',
      //   name: 'booking.category.name',
      //   orderable: false
      // },
      // {
      //   data: 'timesheet.number',
      //   name: 'timesheet.number',
      //   orderable: false
      // },
      // {
      //   data: 'timesheet.status',
      //   name: 'timesheet.status',
      //   orderable: false
      // },
      {
        data: 'weekNumbr',
        name: 'weekNumbr',
        orderable: false
      },{
        data: 'statusBtn',
        name: 'statusBtn',
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
  $('#searchUnit').on('change', function () {
    oTable.column('booking.unit.alias:name').search(this.value).draw();
    var elment = $("#searchUnit").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });
  $('#weekSelect').on('change', function () {
    oTable.column('weekNumbr:name').search(this.value).draw();
    var elment = $("#weekSelect").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });


  $('#searchReset').on('click', function () {

    $(".bgDarkBlue .col-sm-2").removeClass('filterSlected');
    $(".bgDarkBlue .col-sm-1").removeClass('filterSlected');

    $('#searchUnit').select2('destroy').val("").select2({
      placeholder: "Unit",
      allowClear: true,
      width: '100%',
    });

    $('#weekSelect').select2('destroy').val("").select2({
      placeholder: "Week",
      allowClear: true,
      width: '100%',
    });

    oTable.columns().search('').draw();

  });

  $('#searchUnit').select2({
    placeholder: "Unit",
    allowClear: true,
    width: '100%'
  });
  $('#weekSelect').select2({
    placeholder: "Week",
    allowClear: true,
    width: '100%'
  });
  
});
