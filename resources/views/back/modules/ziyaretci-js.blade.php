
    var options = {
        chart: {
            height: 350,
            type: 'line',
            stacked: true,
            toolbar: {
                show: false,
                autoSelected: 'zoom'
            },
            dropShadow: {
                enabled: true,
                top: 12,
                left: 0,
                bottom: 0,
                right: 0,
                blur: 2,
                color: '#45404a2e',
                opacity: 0.35
            },
        },
        colors: ['#2a77f4', '#1ccab8', '#f02fc2'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight',
            width: [4, 4],
            dashArray: [0, 3]
        },
        grid: {
            borderColor: "#45404a2e",
            padding: {
                left: 0,
                right: 0
            }
        },
        markers: {
            size: 0,
            hover: {
                size: 0
            }
        },
        series: [{
            name: '{{__('admin.cogul-ziyaretci')}}',
            data: [{{getPluralVisitorLast7Day()}}]
        }, {
            name: '{{__('admin.tekil-ziyaretci')}}',
            data: [{{getUniqueVisitorLast7Day()}}]
        }],



        xaxis: {
            type: 'datetime',
            categories: [@for($i = 0; $i < 7; $i++)  "{{ date('Y-m-d', strtotime(date('Y-m-d') . '-'.$i.' day')) }}", @endfor ],
            axisBorder: {
                show: true,
                color: '#45404a2e',
            },
            axisTicks: {
                show: true,
                color: '#45404a2e',
            },
        },

        fill: {
            type: 'gradient',
            gradient: {
                gradientToColors: ['#F55555', '#B5AC49', '#6094ea']
            },
        },
        tooltip: {
            x: {
                format: 'yy-MM-dd'
            },
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#liveVisits"),
        options
    );
    chart.render();

