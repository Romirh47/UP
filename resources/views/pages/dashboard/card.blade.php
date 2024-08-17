
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="mb-0 fs-4 text-white">Temperature</h6>
                    <h4 id="temperatureDisplay" class="mb-0 fw-bold text-white"></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h6 class="mb-0 fs-4 text-white">Humidity</h6>
                    <h4 id="humidityDisplay" class="mb-0 fw-bold text-white"></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="mb-0 fs-4 text-white">Soil</h6>
                    <h4 id="soilDisplay" class="mb-0 fw-bold text-white"></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6 class="mb-0 fs-4 text-white">Intensity</h6>
                    <h4 id="intensityDisplay" class="mb-0 fw-bold text-white"></h4>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchData() {
                $.ajax({
                    url: '/api/sensors', // Ganti dengan URL endpoint Anda
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        response.forEach(function(sensor) {
                            if (sensor.name === 'Temperature') {
                                $('#temperatureDisplay').text(sensor.value + ' °C');
                            } else if (sensor.name === 'Humidity') {
                                $('#humidityDisplay').text(sensor.value + ' %');
                            } else if (sensor.name === 'Soil') {
                                $('#soilDisplay').text(sensor.value + ' %');
                            } else if (sensor.name === 'Intensity') {
                                $('#intensityDisplay').text(sensor.value);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Panggil fungsi fetchData untuk pertama kali
            fetchData();

            // Atur interval untuk memperbarui data setiap 5 detik (5000 milidetik)
            setInterval(fetchData, 5000);
        });
    </script>
    @endpush
