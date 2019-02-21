$.ajax({
    type: "GET",
    async: false,
    url: $("#monthReportLine").attr("action"),
    success: function(response) {
        // MONTH CHART
        var lineChart = Highcharts.chart("monthReportLine", {
            chart: {
                type: "line"
            },
            title: {
                text: "Monthly Average Bookings"
            },
            subtitle: {
                text: "Unit Confirmed Bookings Only"
            },
            xAxis: {
                categories: response.lastMonthCnfr.categories
            },
            yAxis: {
                title: {
                    text: "Number of Bookings"
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            series: [
                {
                    name: "All Bookings",
                    data: response.lastMonthCnfr.data
                },
                {
                    name: "Cancelled",
                    data: response.lastMonthCncl.data
                },
                {
                    name: "Unable to Cover",
                    data: response.lastMonthUnbl.data
                }
            ]
        });

        lineChart.series[0].data[19].update({ color: "red" });

        // MONTH CHART
    }
});
