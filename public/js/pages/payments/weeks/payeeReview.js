$(document).ready(function() {

    $(".employerNIC").on('keyup paste', function() {
        var vals = $(this).val();
        var numPayments = $("input[name=numPayments]").val();
        var employerPension = $("input[name=employerPension]").val();
        var employerNIC = $("input[name=employerNIC]").val();
        var totalStaffHrs = $("input[name=totalStaffHrs]").val();
        for($i=0;$i<numPayments;$i++) {
            var staffHours = $(".staffHours"+$i).val();
            var grandShiftTotal = $(".shiftGrandTotal"+$i).val();
            var splitValues = (vals/totalStaffHrs) * staffHours;
            $("input[name=nicSplitup]").val(splitValues.toFixed(2));
            var shiftCost = parseFloat(grandShiftTotal) + parseFloat(employerNIC) +  parseFloat(employerPension);
            var effectiveHrlyRate =  shiftCost/staffHours;
            if(employerPension != '' && employerNIC != '') {
                $(".shiftCost"+$i).val(shiftCost.toFixed(2));
                $(".effectiveHrlyRate"+$i).val(effectiveHrlyRate.toFixed(2));
            } 
        }    

    });
    
    $(".employerPension").on('keyup paste', function() {
        var vals = $(this).val();
        var numPayments = $("input[name=numPayments]").val();
        var employerPension = $("input[name=employerPension]").val();
        var employerNIC = $("input[name=employerNIC]").val();
        var totalStaffHrs = $("input[name=totalStaffHrs]").val();
        for($i=0;$i<numPayments;$i++) {
            var staffHours = $(".staffHours"+$i).val();
            var grandShiftTotal = $(".shiftGrandTotal"+$i).val();
            var splitValues = (vals/totalStaffHrs) * staffHours;
            $("input[name=pensionSplitup]").val(splitValues.toFixed(2));
            var shiftCost = parseFloat(grandShiftTotal) + parseFloat(employerNIC) +  parseFloat(employerPension);
            var effectiveHrlyRate =  shiftCost/staffHours;
            if(employerPension != '' && employerNIC != '') {
                $(".shiftCost"+$i).val(shiftCost.toFixed(2));
                $(".effectiveHrlyRate"+$i).val(effectiveHrlyRate.toFixed(2));
            } 
        }

    });
    

});