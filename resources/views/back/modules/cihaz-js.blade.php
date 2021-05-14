<script>
    var options = {
        chart: {
            height: 250,
            type: 'donut',
            dropShadow: {
                enabled: true,
                top: 10,
                left: 0,
                bottom: 0,
                right: 0,
                blur: 2,
                color: '#45404a2e',
                opacity: 0.15
            },
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%'
                }
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },

        series: [10, 65, 25,],
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            verticalAlign: 'middle',
            floating: false,
            fontSize: '14px',
            offsetX: 0,
            offsetY: -13
        },
        labels: [ <?php foreach (json_decode(getDeviceUniqueLabel()) as $name ) { echo "'".$name."', "; }  ?>],
        colors: [<?php foreach(json_decode(createRandomColor(getDeviceUnique()->count())) as $u) { echo "'".$u."',"; } ?>],

        responsive: [{
            breakpoint: 600,
            options: {
                plotOptions: {
                    donut: {
                        customScale: 0.2
                    }
                },
                chart: {
                    height: 240
                },
                legend: {
                    show: false
                },
            }
        }],

        tooltip: {
            y: {
                formatter: function (val) {
                    return   val + " %"
                }
            }
        }

    }

    var chart = new ApexCharts(
        document.querySelector("#ana_device"),
        options
    );

    chart.render();
</script>
