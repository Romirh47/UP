@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Data Actuator</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ti ti-plus fs-6 me-2"></i> TAMBAH DATA ACTUATOR
                        </button>
                    </div>
                    <div id="loading"
                        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center; padding-top: 20%;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="actuatorTable">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Actuator</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Dibuat</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat oleh AJAX -->
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination" id="paginationNav">
                                <!-- Pagination akan dihasilkan oleh JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal tambah data actuator -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="tambahForm" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Actuator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="actuator_name" class="form-label">Nama Actuator</label>
                        <select class="form-select" id="actuator_name" name="actuator_id" required>
                            <option value="">Pilih Nama Actuator</option>
                            @foreach ($actuators as $actuator)
                                <option value="{{ $actuator->id }}">{{ $actuator->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <select class="form-select" id="value" name="value" required>
                            <option value="1">On</option>
                            <option value="0">Off</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit (Dimatikan tetapi masih ditulis sebagai komentar) -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm" class="modal-content">
                @csrf
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Nilai Actuator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_actuator_name" class="form-label">Nama Actuator</label>
                        <input type="text" class="form-control" id="edit_actuator_name" name="actuator_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_value" class="form-label">Nilai</label>
                        <select class="form-select" id="edit_value" name="value" required>
                            <option value="1">On</option>
                            <option value="0">Off</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            function loadData(page = 1) {
                $('#loading').show();
                $.ajax({
                    url: "{{ route('api.actuator_values.index') }}?page=" + page,
                    type: 'GET',
                    success: function(response) {
                        $('#loading').hide();
                        let rows = '';
                        if (response.data && Array.isArray(response.data)) {
                            response.data.forEach(function(actuatorValue, index) {
                                rows += `<tr>
                            <td>${(response.from + index)}</td>
                            <td>${actuatorValue.actuator ? actuatorValue.actuator.name : 'Tidak ada'}</td>
                            <td>${actuatorValue.value === 1 ? 'On' : 'Off'}</td>
                            <td>${formatDate(actuatorValue.created_at)}</td>
                            <td>
                                <!-- Bagian Edit dimatikan -->
                                <!-- <button class="btn btn-warning btn-sm edit-btn" data-id="${actuatorValue.id}" data-actuator_id="${actuatorValue.actuator_id}" data-value="${actuatorValue.value}" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button> -->
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${actuatorValue.id}">Hapus</button>
                            </td>
                        </tr>`;
                            });
                            $('#actuatorTable tbody').html(rows);
                        } else {
                            $('#actuatorTable tbody').html(
                                '<tr><td colspan="5" class="text-center">Tidak ada data ditemukan</td></tr>'
                            );
                        }

                        // Pagination
                        let pagination = '';
                        pagination += `<ul class="pagination">`;
                        if (response.prev_page_url) {
                            pagination +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}">Previous</a></li>`;
                        } else {
                            pagination +=
                                `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                        }

                        for (let i = 1; i <= response.last_page; i++) {
                            if (i === response.current_page) {
                                pagination +=
                                    `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                            } else {
                                pagination +=
                                    `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                            }
                        }

                        if (response.next_page_url) {
                            pagination +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}">Next</a></li>`;
                        } else {
                            pagination +=
                                `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                        }
                        pagination += `</ul>`;

                        $('#paginationNav').html(pagination);
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        console.error('Error:', xhr.responseText);
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data actuator.', 'error');
                    }
                });
            }

            loadData();

            $('#tambahForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('api.actuator_values.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tambahModal').modal('hide');
                        Swal.fire('Berhasil!', 'Data actuator berhasil ditambahkan.',
                        'success');
                        loadData();
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat menambahkan data actuator.',
                            'error');
                    }
                });
            });

            $('#actuatorTable').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const actuatorId = $(this).data('actuator_id');
                const value = $(this).data('value');

                $('#edit_id').val(id);

                // Load actuator name for the selected actuator_id
                $.ajax({
                    url: `{{ route('api.actuators.show', '') }}/${actuatorId}`,
                    type: 'GET',
                    success: function(response) {
                        $('#edit_actuator_name').val(response.name);
                        $('#edit_value').val(value);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data actuator.',
                            'error');
                    }
                });
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                $.ajax({
                    url: `{{ route('api.actuator_values.update', '') }}/${id}`,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editModal').modal('hide');
                        Swal.fire('Berhasil!', 'Nilai actuator berhasil diperbarui.',
                        'success');
                        loadData();
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        Swal.fire('Terjadi kesalahan',
                            'Tidak dapat memperbarui nilai actuator.', 'error');
                    }
                });
            });

            $('#actuatorTable').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data actuator yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('api.actuator_values.destroy', '') }}/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Terhapus!',
                                    'Data actuator berhasil dihapus.', 'success');
                                loadData();
                            },
                            error: function(xhr) {
                                console.error('Error:', xhr.responseText);
                                Swal.fire('Terjadi kesalahan',
                                    'Tidak dapat menghapus data actuator.', 'error');
                            }
                        });
                    }
                });
            });

            // Pagination click handler
            $('#paginationNav').on('click', 'a.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                if (page) {
                    loadData(page);
                }
            });

            function formatDate(dateStr) {
                let date = new Date(dateStr);
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            }
        });
    </script>
@endpush
