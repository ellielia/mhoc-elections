    function createDiagram(data) {
        console.log(data);
        Highcharts.chart('parliament-diagram-live', {
            chart: {
            type: 'item'
            },

            title: {
            text: null
            },

            legend: {
            labelFormat: '{name} <span style="opacity: 0.4">{y}</span>'
            },

            series: [{
            name: 'Number of seats',
            keys: ['name', 'y', 'color', 'label'],
            data: data,
            dataLabels: {
                enabled: false,
                format: '{point.label}'
            },
            // Circular options
            center: ['50%', '88%'],
            size: '170%',
            startAngle: -100,
            endAngle: 100
            }]

        });
    }
