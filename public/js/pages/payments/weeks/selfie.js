$(document).ready(function() {

  var dataUrl = $(".table").attr('fetch');
  var token = $(".table").attr('token');
  var setCheckStateUrl = $(".table").attr('checkUrl');

  $(document).on( 'init.dt', function ( e, settings ) {
    var api = new $.fn.dataTable.Api( settings );
    var state = api.state.loaded();

    if (state) {
      console.log(state);
      if(state.columns[1].search.search!=""){
        $('#searchStaff').select2('destroy').val(state.columns[1].search.search).select2({
          placeholder: "Staff",
          allowClear: true,
          width: '100%',
        });
        $("#searchStaff").parent().parent().addClass('filterSlected');
      }
    }
    if(state.columns[3].search.search!=""){
        $('#searchWeek').select2('destroy').val(state.columns[3].search.search).select2({
          placeholder: "Week",
          allowClear: true,
          width: '100%',
        });
        $("#searchWeek").parent().parent().addClass('filterSlected');
        $('.reports').removeClass("hidden");

        var urlReport = $('.selfieReport').attr('href').slice(0,-1);
        var newurlReport = urlReport+state.columns[3].search.search;
        $('.selfieReport').attr('href',newurlReport);

        var urlBright = $('.selfiePaymntReport').attr('href').slice(0,-1);
        var newurlBright = urlBright+state.columns[3].search.search;
        $('.selfiePaymntReport').attr('href',newurlBright);
      }
  });

  oTable = $('.table').DataTable({
    pageLength: 50,
    processing: false,
    serverSide: true,
    bStateSave: true,
    fnStateSave: function (oSettings, oData) {
      localStorage.setItem('payeeWeeksTable', JSON.stringify(oData));
    },
    fnStateLoad: function (oSettings) {
      return JSON.parse(localStorage.getItem('payeeWeeksTable'));
    },
    ajax: {
      url: dataUrl,
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': token
      }
    },
    columns: [
      {
        data: 'paymentId',
        name: 'paymentId',
        orderable: false
      },
      {
        data: 'booking.staff.name',
        name: 'booking.staff.name',
        orderable: false
      },
      {
        data: 'companyName',
        name: 'companyName',
        orderable: false
      },
      {
        data: 'paymentWeek',
        name: 'paymentWeek',
        orderable: false
      },
      {
        data: 'amtotal',
        name: 'amtotal',
        orderable: false
      },
      {
        data: 'noOfActualBooking',
        name: 'noOfActualBooking',
        orderable: false
      },
      {
        data: 'numberOfVerifiedBookings',
        name: 'numberOfVerifiedBookings',
        orderable: false
      },
      {
        data: 'archive.statusBtn',
        name: 'archive.statusBtn',
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
  $('#searchWeek').select2({
    placeholder: "Week",
    allowClear: true,
    width: '100%'
  });

  $('#searchStaff').on('change', function () {
    oTable.column('booking.staff.name:name').search(this.value).draw();
    var elment = $("#searchStaff").parent().parent();
      if(this.value > 0){
        elment.addClass('filterSlected');
      }else{
        elment.removeClass('filterSlected');
      }
  });
  $('#searchWeek').on('change', function () {
    oTable.column('paymentWeek:name').search(this.value).draw();
    var elment = $("#searchWeek").parent().parent();
    if(this.value > 0){
      localStorage.setItem("SelfieWeekFilterPaymentWeek", this.value);
      elment.addClass('filterSlected');
      $('.reports').removeClass("hidden");
      var urlReport = $('.selfieReport').attr('href').slice(0,-1);
      var newurlReport = urlReport+this.value;
      $('.selfieReport').attr('href',newurlReport);

      var urlBright = $('.selfiePaymntReport').attr('href').slice(0,-1);
      var newurlBright = urlBright+this.value;
      $('.selfiePaymntReport').attr('href',newurlBright);
    }else{
      elment.removeClass('filterSlected');
      $('.reports').attr("disabled", "disabled");
    }
  });
  $('#searchReset').on('click', function () {
    $('.reports').addClass("hidden");
    $(".bgDarkBlue .col-sm-2").removeClass('filterSlected');
    $(".bgDarkBlue .col-sm-1").removeClass('filterSlected');
    $('.reports').attr("disabled", "disabled");

    $('#searchStaff').select2('destroy').val("").select2({
      placeholder: "Staff",
      allowClear: true,
      width: '100%',
    });
    $('#searchWeek').select2('destroy').val("").select2({
      placeholder: "Week",
      allowClear: true,
      width: '100%',
    });

    localStorage.removeItem("SelfieWeekFilterPaymentWeek");

    oTable.columns().search('').draw();

  });
});
