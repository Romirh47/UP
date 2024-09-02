<!-- Card Sensor -->
<div class="container">
    <div class="row mb-4">
        <div class="col-12 mb-4">
            <h2 class="text-center mb-4">SENSOR</h2>
        </div>

        @foreach ($sensors as $sensor)
            @php
                $latestValue = $sensor->sensorData->sortByDesc('created_at')->first();
                $value = $latestValue ? $latestValue->value : 'NaN';
                $type = $sensor->type;
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4"> <!-- Mengubah lebar kolom -->
                <div class="card bg-success text-light">
                    <div class="card-header text-center">
                        <h5 class="font-weight-bold">{{ strtoupper($sensor->name) }}</h5>
                    </div>
                    <div class="card-body bg-light text-dark d-flex flex-column justify-content-between">
                        <div class="text-center">
                            <div class="card p-3">
                                <h2 class="card-title mb-0">{{ strtoupper($value) }}</h2>
                            </div>
                        </div>
                        <div class="text-center mt-auto">
                            <p class="card-text mb-0"> {{ strtoupper($type) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
