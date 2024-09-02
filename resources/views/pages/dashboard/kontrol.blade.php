
<div class="container">
    <!-- Tombol Hidupkan dan Matikan Semua -->
    {{-- <div class="row mb-3">
        <div class="col-md-6">
            <button id="turn-all-on" class="btn btn-success w-100">Hidupkan Semua</button>
        </div>
        <div class="col-md-6">
            <button id="turn-all-off" class="btn btn-danger w-100">Matikan Semua</button>
        </div>
    </div> --}}

    <div class="row">
        @foreach($actuators as $actuator)
            @php
                // Cek nilai terbaru dari actuator_values untuk aktuator ini
                $actuatorValue = $actuatorValues->firstWhere('actuator_id', $actuator->id);
                $status = $actuatorValue ? ($actuatorValue->value ? 'on' : 'off') : 'off'; // Default ke 'off' jika tidak ada nilai
            @endphp

            <div class="col-md-3 mb-3">
                <div class="card bg-dark text-light">
                    <div class="card-header text-center">
                        <h5 class="font-weight-bold text-white">{{ $actuator->name }}</h5>
                    </div>
                    <div class="card-body p-2">
                        <p class="mb-2"><strong>Status:</strong> <span id="status-{{ $actuator->id }}">{{ ucfirst($status) }}</span></p>
                        <div class="d-flex flex-column">
                            <button type="button" data-id="{{ $actuator->id }}" data-action="on" class="btn btn-success btn-sm mb-1 {{ $status === 'on' ? 'disabled' : '' }}">Hidupkan</button>
                            <button type="button" data-id="{{ $actuator->id }}" data-action="off" class="btn btn-danger btn-sm {{ $status === 'off' ? 'disabled' : '' }}">Matikan</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Fungsi untuk memperbarui status aktuator
        function updateActuatorStatus(actuatorId, action) {
            var $statusElement = $('#status-' + actuatorId);

            $.ajax({
                url: "{{ url('controls') }}/" + actuatorId,
                type: 'PUT',
                data: {
                    _token: "{{ csrf_token() }}",
                    action: action
                },
                success: function(response) {
                    // Update status text
                    $statusElement.text(action === 'on' ? 'On' : 'Off');

                    // Disable/enable buttons
                    if (action === 'on') {
                        $('button[data-id="' + actuatorId + '"][data-action="on"]').addClass('disabled');
                        $('button[data-id="' + actuatorId + '"][data-action="off"]').removeClass('disabled');
                    } else {
                        $('button[data-id="' + actuatorId + '"][data-action="off"]').addClass('disabled');
                        $('button[data-id="' + actuatorId + '"][data-action="on"]').removeClass('disabled');
                    }
                },
                error: function(xhr) {
                    // Show error alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui status.',
                    });
                }
            });
        }

        // Event handler untuk tombol Hidupkan/Mematikan satu aktuator
        $('button[data-action]').on('click', function() {
            var actuatorId = $(this).data('id');
            var action = $(this).data('action');
            updateActuatorStatus(actuatorId, action);
        });

        // // Event handler untuk tombol Hidupkan Semua
        // $('#turn-all-on').on('click', function() {
        //     @foreach($actuators as $actuator)
        //         updateActuatorStatus("{{ $actuator->id }}", 'on');
        //     @endforeach
        // });

        // // Event handler untuk tombol Matikan Semua
        // $('#turn-all-off').on('click', function() {
        //     @foreach($actuators as $actuator)
        //         updateActuatorStatus("{{ $actuator->id }}", 'off');
        //     @endforeach
        // });
    });
</script>
@endpush
