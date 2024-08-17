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
            var categories = @json($categories);
            var series = @json($series);

            Highcharts.chart('combined-chart', {
                chart: {
                    type: 'line',
                    height: 500
                },
                title: {
                    text: 'Sensor Data Trends'
                },
                xAxis: {
                    categories: categories,
                    title: {
                        text: 'Time'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Value'
                    }
                },
                series: series,
                plotOptions: {
                    line: {
                        marker: {
                            enabled: false
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
