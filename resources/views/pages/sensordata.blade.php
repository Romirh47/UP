@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Data Sensor</h2>
                    <!-- Tombol untuk menghapus semua data -->
                    <div class="d-flex justify-content-between mb-3">
                        <button id="deleteAllBtn" class="btn btn-danger">Delete All</button>
                        <span id="totalData">Total Data: 0</span>
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
            // Fungsi untuk memuat data sensor dan pagination
            function loadData(page = 1) {
                $.ajax({
                    url: "{{ route('api.sensordata.index') }}?page=" +
                    page, // Menggunakan query string untuk paginasi
                    type: 'GET',
                    success: function(response) {
                        let rows = '';
                        response.data.forEach(function(data, index) {
                            rows += `<tr>
                            <td>${(response.from + index)}</td>
                            <td>${data.sensor.name}</td>
                            <td>${data.value}</td>
                            <td>${formatDate(data.created_at)}</td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}">Delete</button>
                            </td>
                        </tr>`;
                        });
                        $('#sensorTable tbody').html(rows);

                        // Tampilkan jumlah total data
                        $('#totalData').text('Total Data: ' + response.total);

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
                    error: function(xhr) {
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data sensor.', 'error');
                    }
                });
            }

            // Panggil fungsi loadData saat halaman pertama kali dimuat
            loadData();

            // Interval untuk memuat ulang data setiap 1 detik
            setInterval(function() {
                loadData(); // Reload data
            }, 1000);

            // Event delegation untuk pagination
            $('#paginationNav').on('click', '.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                loadData(page); // Panggil fungsi loadData dengan halaman yang sesuai
            });

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
                                Swal.fire('Berhasil', response.success, 'success').then(
                                    function() {
                                        loadData
                                    (); // Reload data setelah berhasil menghapus
                                    });
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                });
            });

            // Event handler untuk tombol "Delete All"
            $('#deleteAllBtn').click(function() {
                Swal.fire({
                    title: 'Konfirmasi Hapus Semua',
                    text: 'Apakah Anda yakin ingin menghapus semua data?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('api.sensordata.destroy', '') }}/all`, // Gunakan endpoint "destroy" dengan parameter khusus
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire('Berhasil', 'Semua data berhasil dihapus.',
                                    'success').then(function() {
                                    loadData
                                (); // Reload data setelah semua data dihapus
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                });
            });

            // Fungsi untuk format tanggal
            function formatDate(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric'
                };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            }
        });
    </script>
@endpush
