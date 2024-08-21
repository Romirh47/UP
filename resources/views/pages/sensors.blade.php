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
                    <div id="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center; padding-top: 20%;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="sensorTable">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Sensor</th>
                                    <th scope="col">Tipe</th>
                                    <th scope="col">Deskripsi</th>
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

    <!-- Modal tambah data sensor -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="tambahForm" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Sensor</h5>
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
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Masukkan Deskripsi Sensor"></textarea>
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
            <form id="editForm" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Sensor</h5>
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
                        <textarea class="form-control" id="edit_description" name="description" rows="3"
                            placeholder="Masukkan Deskripsi Sensor"></textarea>
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
            // Fungsi untuk memformat tanggal
            function formatDate(dateString) {
                const date = new Date(dateString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${day} ${month} ${year} ${hours}:${minutes}`;
            }

            // Fungsi untuk memuat data sensor dan pagination
            function loadData(page = 1) {
                $('#loading').show(); // Tampilkan animasi loading
                $.ajax({
                    url: "{{ route('api.sensors.index') }}?page=" + page,
                    type: 'GET',
                    success: function(response) {
                        let rows = '';
                        response.data.forEach(function(sensor, index) {
                            rows += `<tr>
                        <td>${(response.from + index)}</td>
                        <td>${sensor.name}</td>
                        <td>${sensor.type}</td>
                        <td>${sensor.description}</td>
                        <td>${formatDate(sensor.created_at)}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${sensor.id}" data-name="${sensor.name}" data-type="${sensor.type}" data-description="${sensor.description}">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${sensor.id}">
                                Hapus
                            </button>
                        </td>
                    </tr>`;
                        });
                        $('#sensorTable tbody').html(rows);

                        // Pagination
                        let pagination = '';
                        pagination += `<ul class="pagination">`;
                        if (response.prev_page_url == null) {
                            pagination +=
                                `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                        } else {
                            pagination +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}">Previous</a></li>`;
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

                        if (response.next_page_url == null) {
                            pagination +=
                                `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                        } else {
                            pagination +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}">Next</a></li>`;
                        }
                        pagination += `</ul>`;

                        $('#paginationNav').html(pagination); // Perbarui elemen pagination
                    },
                    error: function() {
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data sensor.', 'error');
                    },
                    complete: function() {
                        $('#loading').hide(); // Sembunyikan animasi loading
                    }
                });
            }

            // Panggil fungsi loadData saat halaman pertama kali dimuat
            loadData();

            // Event delegation untuk pagination
            $('#paginationNav').on('click', '.page-link', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page) {
                    loadData(page);
                }
            });

            // Event delegation untuk tombol edit
            $('#sensorTable').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const type = $(this).data('type');
                const description = $(this).data('description');
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_type').val(type);
                $('#edit_description').val(description);
                $('#editModal').modal('show');
            });

            // Event delegation untuk tombol hapus
            $('#sensorTable').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('sensors') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                Swal.fire('Terhapus!', 'Data sensor telah dihapus.', 'success');
                                loadData(); // Muat ulang data setelah penghapusan
                            },
                            error: function() {
                                Swal.fire('Terjadi kesalahan', 'Tidak dapat menghapus data sensor.', 'error');
                            }
                        });
                    }
                });
            });

            // Menangani form tambah sensor
            $('#tambahForm').submit(function(e) {
                e.preventDefault();
                $('#loading').show(); // Tampilkan animasi loading
                $.ajax({
                    url: "{{ route('api.sensors.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        Swal.fire('Berhasil!', 'Data sensor telah ditambahkan.', 'success');
                        $('#tambahModal').modal('hide');
                        loadData(); // Muat ulang data setelah penambahan
                    },
                    error: function() {
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat menambahkan data sensor.', 'error');
                    },
                    complete: function() {
                        $('#loading').hide(); // Sembunyikan animasi loading
                    }
                });
            });

            // Menangani form edit sensor
            $('#editForm').submit(function(e) {
                e.preventDefault();
                $('#loading').show(); // Tampilkan animasi loading
                const id = $('#edit_id').val();
                $.ajax({
                    url: "{{ url('sensors') }}/" + id,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function() {
                        Swal.fire('Berhasil!', 'Data sensor telah diperbarui.', 'success');
                        $('#editModal').modal('hide');
                        loadData(); // Muat ulang data setelah pembaruan
                    },
                    error: function() {
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memperbarui data sensor.', 'error');
                    },
                    complete: function() {
                        $('#loading').hide(); // Sembunyikan animasi loading
                    }
                });
            });
        });
    </script>
@endpush
