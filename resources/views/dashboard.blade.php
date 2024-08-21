@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard</h1>

    <!-- Sensor Data Cards -->
    <div class="row" id="sensorCards">
        <!-- Data sensor akan dimuat di sini -->
    </div>

    <!-- Actuator Cards -->
    <div class="row" id="actuatorCards">
        <!-- Data aktuator akan dimuat di sini -->
    </div>

    @include('pages.dashboard.grafik')
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Function to load data from the API
        function loadData() {
            $.ajax({
                url: "{{ route('dashboard.data') }}",
                type: 'GET',
                success: function(response) {
                    // Load Sensor Data
                    let sensorCards = '';
                    response.sensors.forEach(function(sensor) {
                        let latestData = response.sensorData[sensor.id] && response.sensorData[sensor.id].length ? response.sensorData[sensor.id][0].value : 'No data';
                        sensorCards += `
                        <div class="col-lg-3 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6 class="mb-3 fs-4 text-uppercase text-white font-weight-bold">${sensor.name}</h6>
                                    <h4 class="mb-0 fw-bold text-white" style="font-size: 2rem;">${latestData}</h4>
                                    <p class="mb-0 text-white" style="margin-top: 10px; font-size: 1.2rem; font-weight: bold;">
                                        ${sensor.type}
                                    </p>
                                </div>
                            </div>
                        </div>`;
                    });
                    $('#sensorCards').html(sensorCards);

                    // Load Actuator Data
                    let actuatorCards = '';
                    response.actuators.forEach(function(actuator) {
                        actuatorCards += `
                        <div class="col-lg-3 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="mb-0 fs-4 font-weight-bold">${actuator.name}</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="actuatorSwitch${actuator.id}"
                                            ${actuator.status ? 'checked' : ''} data-id="${actuator.id}">
                                        <label class="form-check-label" for="actuatorSwitch${actuator.id}">
                                            ${actuator.status ? 'On' : 'Off'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                    $('#actuatorCards').html(actuatorCards);
                },
                error: function(xhr) {
                    console.error(xhr.responseText); // Tampilkan detail error di konsol
                    Swal.fire('Error', 'An error occurred while loading data.', 'error');
                }
            });
        }

        // Load data when the page loads
        loadData();

        // Toggle actuator status
        $(document).on('change', '.form-check-input', function() {
            let id = $(this).data('id');
            let status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: `/actuators/${id}/status`,
                type: 'PUT',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function(response) {
                    Swal.fire('Success', response.success, 'success');
                },
                error: function(xhr) {
                    console.error(xhr.responseText); // Tampilkan detail error di konsol
                    Swal.fire('Error', 'An error occurred while updating actuator status.', 'error');
                }
            });
        });
    });
</script>
@endpush
