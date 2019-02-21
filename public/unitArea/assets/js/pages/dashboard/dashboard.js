$.ajax({
    type: "GET",
    async: false,
    url: $("#lastWeekChart").attr("action"),
    success: function(response) {
        // 10 DAYS CHART
        // Highcharts.chart("lastWeekChart", {
        //     chart: { type: "column" },
        //     title: {
        //         text: "Last Week Bookings"
        //     },
        //     subtitle: {
        //         text: "Unit Confirmed Bookings only"
        //     },
        //     xAxis: {
        //         categories: response.lastWeek.categories,
        //         crosshair: true
        //     },
        //     yAxis: {
        //         min: 0,
        //         title: { text: "Number of Bookings" }
        //     },
        //     tooltip: {
        //         headerFormat:
        //             '<span style="font-size:10px">{point.key}</span><table>',
        //         pointFormat:
        //             '<tr><td style="color:{series.color};padding:0"><strong>{series.name}: </strong></td>' +
        //             '<td style="padding:0"><b>{point.y}</b></td></tr>',
        //         footerFormat: "</table>",
        //         shared: true,
        //         useHTML: true
        //     },
        //     plotOptions: {
        //         column: {
        //             pointPadding: 0.2,
        //             borderWidth: 0
        //         }
        //     },
        //     series: response.lastWeek.data
        // });
        // 10 DAYS CHART

        // MONTH CHART
        var lineChart = Highcharts.chart("monthReportLine", {
            chart: {
                type: "line"
            },
            title: {
                text: "Monthly Average Bookings"
            },
            subtitle: {
                text: ""
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
               
            ]
        });

        lineChart.series[0].data[19].update({ color: "#FF8000" });

        // MONTH CHART

        // PIE CHART
        Highcharts.chart("unitPieChart", {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: "pie"
            },
            title: {
                text: "Last Month Unit Bookings"
            },
            subtitle: {
                text: response.pieData.from + " to " + response.pieData.to
            },
            tooltip: {
                pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: "pointer",
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [
                {
                    name: "Brands",
                    colorByPoint: true,
                    data: response.pieData.data
                }
            ]
        });
        // PIE CHART
    }
});
