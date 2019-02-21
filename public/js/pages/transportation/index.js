$(document).ready(function () {
    var dataUrl = $(".table").attr('fetch');
    var token = $(".table").attr('token');
    var search = $(".table").attr('search');
    var setCheckStateUrl = $(".table").attr('checkUrl');

    oTable = $('.table').DataTable({
        pageLength: 50,
        processing: false,
        serverSide: true,
        ajax: {
            url: dataUrl,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': token
            }
        },
        columns: [
        {
            data: 'tripId', name: 'tripId', 'orderable': false, searchable: false,
        },
        {
            data: 'date', name: 'date', orderable: false
        },
        {
            data: 'driver.forname', name: 'driver.forname', orderable: false
        },
        {
            data: 'outCount', name: 'inCount', orderable: false
        },
        {
            data: 'staff.forname', name: 'staff.forname', orderable: false
        },
        {
            data: 'inCount', name: 'outCount', orderable: false
        },
        {
            data: 'actions', name: 'actions', orderable: false, searchable: false,
        },

        ],
        drawCallback: function () {
            $(".icheckBox").iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%'
            });
            // CHECKBOX CHECK EVENT
            $('.icheckBox').on('ifChecked', function (event) {
            });
            // CHECKBOX UNCHECK EVENT
            $('.icheckBox').on('ifUnchecked', function (event) {
            });
        }
    });
});
