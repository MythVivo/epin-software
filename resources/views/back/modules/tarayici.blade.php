
    var colors = ['#1ecab8', '#fd3c97', '#6d81f5', '#ffb822', '#0dc8de'];
    var options = {
        chart: {
            height: 300,
            type: 'bar',
            events: {
                click: function(chart, w, e) {
                    console.log(chart, w, e )
                }
            },
            toolbar:{
                show:false
            },
            dropShadow: {
                enabled: true,
                top: 0,
                left: 5,
                bottom: 5,
                right: 0,
                blur: 5,
                color: '#45404a2e',
                opacity: 0.35
            },
        },
        colors: colors,
        plotOptions: {
            bar: {
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
                columnWidth: '30',
                distributed: true,
            },

        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val + "%";
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#8997bd"]
            }
        },
        series: [{
            name: '@lang('admin.tarayici-adi')',
            data: [<?php foreach(getBrowserStatisticData() as $data) { echo $data.", "; } ?> ]
        }],
        xaxis: {
            categories: [<?php foreach (json_decode(getBrowserStatistic()) as $name ) { echo "'".$name."', "; }  ?> ],
            position: 'top',
            labels: {
                offsetY: -18,
                style: {
                    cssClass: 'apexcharts-xaxis-label',
                },
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            crosshairs: {
                fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                }
            },
            tooltip: {
                enabled: true,
                offsetY: -37,
            }
        },
        fill: {
            gradient: {
                type: "gradient",
                gradientToColors: ['#FEB019', '#775DD0', '#FEB019', '#FF4560', '#775DD0'],
            },
        },
        yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                }
            }

        },
    }

    var chart = new ApexCharts(
        document.querySelector("#barchart"),
        options
    );

    chart.render();

