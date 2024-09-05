<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Sensor Data Charts with Highcharts</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <style>
        .chart-container {
            width: 100%; /* Full width for the single large chart */
            margin-bottom: 20px; /* Margin for spacing between charts */
        }
    </style>
</head>
<body>
    <div id="combined-chart" class="chart-container"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the chart
            var chart = Highcharts.chart('combined-chart', {
                chart: {
                    type: 'line',
                    height: 500
                },
                title: {
                    text: 'Sensor Data Trends'
                },
                xAxis: {
                    type: 'datetime', // Change to datetime to handle real-time data
                    title: {
                        text: 'Time'
                    },
                    tickPixelInterval: 150
                },
                yAxis: {
                    title: {
                        text: 'Value'
                    }
                },
                series: @json($series),
                plotOptions: {
                    line: {
                        marker: {
                            enabled: false
                        }
                    }
                }
            });

            // Function to fetch and update chart data
            function fetchDataAndUpdateChart() {
                fetch('/api/sensordata')
                    .then(response => response.json())
                    .then(data => {
                        var now = new Date().getTime();
                        Object.keys(data).forEach(sensorId => {
                            var sensor = data[sensorId];
                            var series = chart.series.find(s => s.name === `Sensor ${sensorId}`);

                            if (series) {
                                // Add new data point
                                series.addPoint([now, sensor.value], true, false);
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching sensor data:', error));
            }

            // Call fetchDataAndUpdateChart every 1000 ms (1 second)
            setInterval(fetchDataAndUpdateChart, 1000);
        });
    </script>
</body>
</html>
