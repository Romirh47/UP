@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Daftar Intensities</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ti ti-plus fs-6 me-2"></i> TAMBAH INTENSITY
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($intensities as $intensity)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $intensity->value }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-btn"
                                                    data-id="{{ $intensity->id }}" data-value="{{ $intensity->value }}"
                                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn"
                                                    data-id="{{ $intensity->id }}">
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

    <!-- Modal tambah value -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="tambahForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Intensity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" class="form-control" id="value" name="value" required placeholder="Masukkan Value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal edit value -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Value</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_value" class="form-label">Value</label>
                        <input type="number" class="form-control" id="edit_value" name="value" required placeholder="Masukkan Perubahan">
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
        // Tambah intensity
        $('#tambahForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ route("intensities.store") }}',
                data: $(this).serialize(),
                success: function(response) {
                    $('#tambahModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data intensity berhasil ditambahkan!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Set data edit modal
        $('.edit-btn').on('click', function() {
            let id = $(this).data('id');
            let value = $(this).data('value');
            $('#edit_id').val(id);
            $('#edit_value').val(value);
        });

        // Edit intensity
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            $.ajax({
                type: 'POST',
                url: '/intensities/' + id,
                data: $(this).serialize(),
                success: function(response) {
                    $('#editModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data intensity berhasil diubah!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Delete intensity
        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan bisa mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/intensities/' + id,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Dihapus!',
                                'Data intensity telah dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan!',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            })
        });
    });
</script>
@endpush
