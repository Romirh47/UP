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
                        <table class="table table-striped" id="sensorTable">
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
                                {{-- Data akan dimuat di sini oleh JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination akan dimuat di sini -->
                    <nav aria-label="Page navigation" id="paginationNav">
                        {{-- Pagination akan dimuat di sini oleh JavaScript --}}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data Sensor -->
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
                        <input type="number" class="form-control" id="value" name="value" required
                            placeholder="Masukkan Nilai Sensor">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Data Sensor -->
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
                        <input type="number" class="form-control" id="edit_value" name="value" required
                            placeholder="Masukkan Nilai Sensor">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Animation -->
    <div id="loading"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center; padding-top: 20%;">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk memformat tanggal
            function formatDate(dateString) {
                const date = new Date(dateString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }

            // Fungsi untuk memuat data sensor dan pagination
            function loadData(page = 1) {
                $('#loading').show(); // Tampilkan animasi loading
                $.ajax({
                    url: "{{ route('api.sensordata.index') }}?page=" + page, // Menggunakan query string untuk paginasi
                    type: 'GET',
                    success: function(response) {
                        $('#loading').hide(); // Sembunyikan animasi loading
                        let rows = '';
                        response.data.forEach(function(data, index) {
                            rows += `<tr>
                                <td>${(response.from + index)}</td>
                                <td>${data.sensor.name}</td>
                                <td>${data.value}</td>
                                <td>${formatDate(data.created_at)}</td> <!-- Format tanggal -->
                                <td>
                                    <!-- Kode tombol edit dinonaktifkan dengan komentar -->
                                    <!-- <button class="btn btn-warning btn-sm edit-btn" data-id="${data.id}" data-sensor-id="${data.sensor_id}" data-value="${data.value}" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button> -->
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}">Delete</button>
                                </td>
                            </tr>`;
                        });
                        $('#sensorTable tbody').html(rows);

                        // Pagination
                        let pagination = '';
                        pagination += `<ul class="pagination">`;
                        if (response.prev_page_url == null) {
                            pagination += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                        } else {
                            pagination += `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}">Previous</a></li>`;
                        }

                        for (let i = 1; i <= response.last_page; i++) {
                            if (i === response.current_page) {
                                pagination += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                            } else {
                                pagination += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                            }
                        }

                        if (response.next_page_url == null) {
                            pagination += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                        } else {
                            pagination += `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}">Next</a></li>`;
                        }
                        pagination += `</ul>`;

                        $('#paginationNav').html(pagination); // Perbarui elemen pagination
                    },
                    error: function(xhr) {
                        $('#loading').hide(); // Sembunyikan animasi loading
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data sensor.', 'error');
                    }
                });
            }

            // Panggil fungsi loadData saat halaman pertama kali dimuat
            loadData();

            // Event delegation untuk pagination
            $('#paginationNav').on('click', '.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                loadData(page); // Panggil fungsi loadData dengan halaman yang sesuai
            });

            // Tambah data sensor
            $('#tambahForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('api.sensordata.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#tambahModal').modal('hide');
                        $('#tambahForm')[0].reset(); // Reset form
                        Swal.fire('Berhasil', response.success, 'success').then(function() {
                            loadData(); // Reload data setelah berhasil menambah
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                    }
                });
            });

            // Event delegation untuk tombol edit dinonaktifkan
            // $('#sensorTable').on('click', '.edit-btn', function() {
            //     let id = $(this).data('id');
            //     let sensorId = $(this).data('sensor-id');
            //     let value = $(this).data('value');
            //     $('#edit_id').val(id);
            //     $('#edit_sensor_id').val(sensorId);
            //     $('#edit_value').val(value);
            // });

            // Edit data sensor dinonaktifkan
            // $('#editForm').on('submit', function(e) {
            //     e.preventDefault();
            //     let id = $('#edit_id').val();
            //     let formData = $(this).serialize();
            //     $.ajax({
            //         url: `{{ route('api.sensordata.update', '') }}/${id}`,
            //         type: 'PUT',
            //         data: formData,
            //         success: function(response) {
            //             $('#editModal').modal('hide');
            //             Swal.fire('Berhasil', response.success, 'success').then(function() {
            //                 loadData(); // Reload data setelah berhasil mengedit
            //             });
            //         },
            //         error: function(xhr) {
            //             Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
            //         }
            //     });
            // });

            // Event delegation untuk tombol delete
            $('#sensorTable').on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('api.sensordata.destroy', '') }}/${id}`,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire('Berhasil', response.success, 'success').then(function() {
                                    loadData(); // Reload data setelah berhasil menghapus
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


