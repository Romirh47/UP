@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Data Actuator</h2>
                    <div class="d-flex justify-content-between mb-3">
                        <button id="deleteAllBtn" class="btn btn-danger btn-lg">Delete All</button>
                        <div id="totalData" class="me-4"></div>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Fungsi untuk memuat data actuator dan pagination
        function loadData(page = 1) {
            $('#loading').show(); // Tampilkan animasi loading
            $.ajax({
                url: "{{ route('api.actuator_values.index') }}?page=" + page, // Menggunakan query string untuk paginasi
                type: 'GET',
                success: function(response) {
                    $('#loading').hide(); // Sembunyikan animasi loading
                    let rows = '';
                    if (response.data && Array.isArray(response.data)) {
                        response.data.forEach(function(actuatorValue, index) {
                            rows += `<tr>
                                <td>${(response.from + index)}</td>
                                <td>${actuatorValue.actuator ? actuatorValue.actuator.name : 'Tidak ada'}</td>
                                <td>${actuatorValue.value === 1 ? 'On' : 'Off'}</td>
                                <td>${formatDate(actuatorValue.created_at)}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${actuatorValue.id}">Hapus</button>
                                </td>
                            </tr>`;
                        });
                        $('#actuatorTable tbody').html(rows);

                        // Tampilkan jumlah total data
                        $('#totalData').text('Jumlah Data: ' + response.total);
                    } else {
                        $('#actuatorTable tbody').html('<tr><td colspan="5" class="text-center">Tidak ada data ditemukan</td></tr>');
                        $('#totalData').text('Jumlah Data: 0');
                    }

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
                    Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data actuator.', 'error');
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

        // Event delegation untuk tombol delete
        $('#actuatorTable').on('click', '.delete-btn', function() {
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
                        url: `{{ route('api.actuator_values.destroy', '') }}/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', 'Data actuator berhasil dihapus.', 'success').then(function() {
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

        // Event handler untuk tombol "Delete All"
        $('#deleteAllBtn').click(function() {
            Swal.fire({
                title: 'Konfirmasi Hapus Semua',
                text: 'Apakah Anda yakin ingin menghapus semua data actuator?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('api.actuator_values.destroy', ['id' => 'all']) }}`, // URL untuk menghapus semua data
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', 'Semua data actuator berhasil dihapus.', 'success').then(function() {
                                loadData(); // Reload data setelah semua data dihapus
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
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }
    });
</script>
@endpush
