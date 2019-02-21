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
            data: 'DT_Row_Index', name: 'DT_Row_Index', 'orderable': false, searchable: false,
        },
        {
            data: 'driver.forname', name: 'driver.forname', 'orderable': false, searchable: false,
        },
        {
            data: 'staffNames', name: 'staffNames', orderable: false
        },
        {
            data: 'noOfTrips', name: 'noOfTrips', orderable: false
        },
        {
            data: 'aggreedAmount', name: 'aggreedAmount', orderable: false
        },
        {
            data: 'payeeWeek', name: 'payeeWeek', orderable: false
        },
        {
            data: 'status', name: 'status', orderable: false
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
