@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body">
                <h2 class="card-title">Data Sensor</h2>
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                        <i class="ti ti-plus fs-6 me-2"></i> TAMBAH DATA SENSOR
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">NO</th>
                                <th scope="col">Nama Sensor</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Dibuat</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sensorData as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->sensor->name }}</td>
                                <td>{{ $data->value }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn"
                                        data-id="{{ $data->id }}" data-sensor-id="{{ $data->sensor_id }}"
                                        data-value="{{ $data->value }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $data->id }}">
                                        Delete
                                    </button>
                                </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal tambah data sensor -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="tambahForm">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Sensor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="sensor_id" class="form-label">Sensor</label>
                    <select class="form-control" id="sensor_id" name="sensor_id" required>
                        @foreach ($sensors as $sensor)
                            <option value="{{ $sensor->id }}">{{ $sensor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="value" class="form-label">Nilai</label>
                    <input type="number" class="form-control" id="value" name="value" required placeholder="Masukkan Nilai Sensor">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit data sensor -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Sensor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-3">
                    <label for="edit_sensor_id" class="form-label">Sensor</label>
                    <select class="form-control" id="edit_sensor_id" name="sensor_id" required>
                        @foreach ($sensors as $sensor)
                            <option value="{{ $sensor->id }}">{{ $sensor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="edit_value" class="form-label">Nilai</label>
                    <input type="number" class="form-control" id="edit_value" name="value" required placeholder="Masukkan Nilai Sensor">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Tambah data sensor
        $('#tambahForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('sensordata.store') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#tambahModal').modal('hide');
                    Swal.fire('Berhasil', response.success, 'success').then(function() {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                }
            });
        });

        // Edit data sensor
        $('.edit-btn').on('click', function() {
            let id = $(this).data('id');
            let sensorId = $(this).data('sensor-id');
            let value = $(this).data('value');
            $('#edit_id').val(id);
            $('#edit_sensor_id').val(sensorId);
            $('#edit_value').val(value);
        });

        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let formData = $(this).serialize();
            $.ajax({
                url: "/sensor_data/" + id,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editModal').modal('hide');
                    Swal.fire('Berhasil', response.success, 'success').then(function() {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                }
            });
        });

        // Hapus data sensor
        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan tindakan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/sensor_data/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', response.success, 'success').then(function() {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
