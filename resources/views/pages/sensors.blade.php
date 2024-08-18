@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Daftar Sensor</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ti ti-plus fs-6 me-2"></i> TAMBAH SENSOR
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Sensor</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Dibuat</th>
                                    <th scope="col">Diubah</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sensors as $sensor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sensor->name }}</td>
                                        <td>{{ $sensor->type }}</td>
                                        <td>{{ $sensor->description }}</td>
                                        <td>{{ $sensor->created_at }}</td>
                                        <td>{{ $sensor->updated_at }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $sensor->id }}"
                                                data-name="{{ $sensor->name }}" data-type="{{ $sensor->type }}"
                                                data-description="{{ $sensor->description }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $sensor->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal tambah sensor -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="tambahForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Sensor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Sensor</label>
                        <input type="text" class="form-control" id="name" name="name" required
                            placeholder="Masukkan Nama Sensor">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Sensor</label>
                        <input type="text" class="form-control" id="type" name="type" required
                            placeholder="Masukkan Tipe Sensor">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Masukkan Deskripsi (Opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal edit sensor -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Sensor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Sensor</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required
                            placeholder="Masukkan Nama Sensor">
                    </div>
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Tipe Sensor</label>
                        <input type="text" class="form-control" id="edit_type" name="type" required
                            placeholder="Masukkan Tipe Sensor">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" placeholder="Masukkan Deskripsi (Opsional)"></textarea>
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
        // Tambah sensor
        $('#tambahForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('web.sensors.store') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#tambahModal').modal('hide');
                    Swal.fire('Berhasil', response.message, 'success').then(function() {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = 'Terjadi kesalahan';

                    if (errors) {
                        if (errors.name) {
                            errorMessage = 'Nama sensor sudah digunakan. Silakan gunakan nama yang lain.'; // Pesan error terkait 'name'
                        } else if (errors.type) {
                            errorMessage = 'Tipe sensor tidak valid. Harap periksa kembali.'; // Pesan error terkait 'type'
                        } else if (errors.description) {
                            errorMessage = 'Deskripsi sensor tidak valid. Harap periksa kembali.'; // Pesan error terkait 'description'
                        }
                    }

                    Swal.fire('Gagal', errorMessage, 'error');
                }
            });
        });

        // Edit sensor
        $('.edit-btn').on('click', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let type = $(this).data('type');
            let description = $(this).data('description');
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_type').val(type);
            $('#edit_description').val(description);
        });

        // Edit sensor
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let formData = $(this).serialize();
            $.ajax({
                url: "/sensors/" + id,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editModal').modal('hide');
                    Swal.fire('Berhasil', response.message, 'success').then(function() {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = 'Terjadi kesalahan';

                    if (errors) {
                        if (errors.name) {
                            errorMessage = 'Nama sensor sudah digunakan. Silakan gunakan nama yang lain.'; // Pesan error terkait 'name'
                        } else if (errors.type) {
                            errorMessage = 'Tipe sensor tidak valid. Harap periksa kembali.'; // Pesan error terkait 'type'
                        } else if (errors.description) {
                            errorMessage = 'Deskripsi sensor tidak valid. Harap periksa kembali.'; // Pesan error terkait 'description'
                        }
                    }

                    Swal.fire('Gagal', errorMessage, 'error');
                }
            });
        });

        // Hapus sensor
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
                        url: "/sensors/" + id,
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
