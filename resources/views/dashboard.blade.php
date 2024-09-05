@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center">Sensor</h2>
        <div class="row" id="sensorCards"></div>
    </div>

    <div class="container mt-4">
        <h2 class="text-center">Actuator</h2>
        <div class="row" id="actuatorCards"></div>
    </div>

    <div id="sensorChart" class="mt-4"></div>
@endsection

@push('styles')
    <style>
        /* Gaya khusus untuk card sensor */
        .sensor-card {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .sensor-header {
            background-color: #28a745; /* Warna latar belakang header sensor */
            color: #ffffff; /* Warna teks header sensor */
            padding: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
        }

        .sensor-card-title {
            font-size: 16px;
            margin: 0;
        }

        .sensor-card-text {
            font-size: 14px;
            margin: 0;
        }

        .sensor-card-unit {
            font-size: 12px;
            color: #6c757d; /* Warna teks unit sensor */
        }

        .sensor-value {
            font-size: 18px;
            font-weight: bold;
        }

        /* Gaya khusus untuk card actuator */
        .actuator-card {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
            background-color: #ffffff;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .actuator-header {
            background-color: #007bff; /* Warna latar belakang header actuator */
            color: #ffffff; /* Warna teks header actuator */
            padding: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
        }

        .actuator-card-title {
            font-size: 16px;
        }

        .actuator-card-text {
            font-size: 14px;
            margin: 0;
        }

        .btn-container {
            display: flex;
            gap: 5px;
        }

        .btn-container .btn {
            flex: 1;
            min-width: 0;
            padding: 5px 10px;
            font-size: 12px;
        }

        /* CSS tambahan untuk card body */
        .card-body {
            padding: 10px;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        $(document).ready(function() {
            let chart;

            function loadData() {
                $.ajax({
                    url: `{{ route('api.dashboard.index') }}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.sensors && response.actuators && response.actuatorValues) {
                            renderSensors(response.sensors);
                            renderActuators(response.actuators, response.actuatorValues);
                            updateChart(getChartData(response.sensors));
                        } else {
                            Swal.fire('Terjadi kesalahan', 'Tidak ada data yang ditemukan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Log error ke konsol untuk debug
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data.', 'error');
                    }
                });
            }

            function renderSensors(sensors) {
                let sensorCards = '';
                sensors.forEach(function(sensor) {
                    let latestData = sensor.sensor_data.sort((a, b) => new Date(b.created_at) - new Date(a
                        .created_at))[0];
                    let value = latestData ? latestData.value : 'NaN';

                    sensorCards += `
                        <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                            <div class="sensor-card">
                                <div class="sensor-header">
                                    <h5 class="sensor-card-title">${sensor.name}</h5>
                                </div>
                                <div class="card-body">
                                    <h3 class="sensor-value">${value}</h3>
                                    <p class="sensor-card-text">${sensor.type}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });

                $('#sensorCards').html(sensorCards);
            }

            function renderActuators(actuators, actuatorValues) {
                let actuatorCards = '';
                actuators.forEach(function(actuator) {
                    let rawValue = actuatorValues[actuator.id] ? actuatorValues[actuator.id].value : null;
                    let latestValue = rawValue == 1 ? 'On' : (rawValue == 0 ? 'Off' : 'Unknown');

                    actuatorCards += `
                        <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                            <div class="actuator-card">
                                <div class="actuator-header">
                                    <h5 class="actuator-card-title">${actuator.name}</h5>
                                </div>
                                <div class="card-body">
                                    <h3 class="actuator-card-text">${latestValue}</h3>
                                    <div class="btn-container">
                                        <button class="btn btn-success" onclick="toggleActuator(${actuator.id}, 1)">Hidupkan</button>
                                        <button class="btn btn-danger" onclick="toggleActuator(${actuator.id}, 0)">Matikan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                $('#actuatorCards').html(actuatorCards);
            }

            function toggleActuator(actuatorId, value) {
                $.ajax({
                    url: '{{ route('api.dashboard.store') }}',
                    type: 'POST',
                    data: {
                        actuator_id: actuatorId,
                        value: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil', 'Status actuator berhasil diubah.', 'success');
                        loadData();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Log error ke konsol untuk debug
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat mengubah status actuator.', 'error');
                    }
                });
            }

            function getChartData(sensors) {
                return sensors.map(sensor => ({
                    name: sensor.name,
                    data: sensor.sensor_data.map(data => ({
                        x: new Date(data.created_at).getTime(),
                        y: parseFloat(data.value)
                    }))
                }));
            }

            function updateChart(data) {
                if (chart) {
                    chart.update({
                        series: data
                    });
                } else {
                    renderChart(data);
                }
            }

            function renderChart(data) {
                chart = Highcharts.chart('sensorChart', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Data Sensor'
                    },
                    xAxis: {
                        type: 'datetime',
                        title: {
                            text: 'Waktu'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Nilai'
                        }
                    },
                    series: data,
                    tooltip: {
                        shared: true,
                        valueSuffix: ' units'
                    }
                });
            }

            loadData();
            setInterval(loadData, 1000); // Memuat data setiap 1 detik
        });
    </script>
@endpush
