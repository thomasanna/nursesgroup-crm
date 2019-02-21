
function loadView(params) {
    fetchUri = $("#reportView").data("view-uri");
    token = $("#reportView").data("token");
    $.ajax({
        url: fetchUri,
        method: 'GET',
        data: { 
            "_token": token,
            'startDate': params.date, 
            'staffId': params.staffId },
        beforeSend: function () {
            $('.loading,.modelPophldr').show();
        }, 
        complete: function () {
            $('.loading,.modelPophldr').hide();
        },
        success: function (response) {
            $("#reportView").html(response.content);
        }
    });
}


$(document).ready(function(){

    $("select.select2[name=staffs]").select2({
        placeholder: "Select a Staff",
        allowClear: true
    });

    $("select.select2[name=staffs]").on('change',function(){
        var staffId = $("#staffs option:selected").val();
        if ((staffId != '')&& (staffId>0)) {
            var now = moment().format('YYYY-MM-DD');
            loadView({ staffId: staffId, date: now })
        }else{
            $("#reportView").html('');                   
        }
    });

});
