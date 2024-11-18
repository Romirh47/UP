@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Data Laporan</h2>

                    <!-- Tambahkan elemen untuk menampilkan total data -->
                    <div class="mb-3">
                        <span id="totalData">0</span>
                    </div>

                    <!-- Tombol untuk menghapus semua data hanya jika admin -->
                    @if (auth()->user()->role === 'admin')
                        <div class="d-flex justify-content-between mb-3">
                            <!-- Tombol untuk menghapus semua laporan -->
                            <button id="deleteAllBtn" class="btn btn-danger">Delete All</button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped" id="reportsTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Foto Kejadian</th>
                                    <th scope="col">Jenis Kejadian</th>
                                    <th scope="col">Dibuat</th>

                                    <!-- Tampilkan kolom Aksi hanya jika admin -->
                                    @if (auth()->user()->role === 'admin')
                                        <th scope="col">Aksi</th>
                                    @endif
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

    <!-- Modal untuk melihat foto besar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Kejadian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Foto Kejadian" class="img-fluid">
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
            let isFirstLoad = true; // Flag untuk mengecek apakah ini load pertama

            // Fungsi untuk memuat data laporan dan pagination
            function loadData(page = 1) {
                // Tampilkan animasi loading hanya saat load pertama
                if (isFirstLoad) {
                    $('#loading').show();
                }

                $.ajax({
                    url: "{{ route('api.reports.index') }}?page=" +
                        page, // Sesuaikan dengan route yang benar
                    type: 'GET',
                    success: function(response) {
                        console.log("Response Data:", response); // Debugging: cek data respons

                        // Set isFirstLoad ke false setelah load pertama
                        isFirstLoad = false;

                        // Periksa apakah response.data ada dan memiliki panjang lebih dari 0
                        if (response.success && Array.isArray(response.data.data) && response.data.data
                            .length > 0) {
                            let rows = '';
                            response.data.data.forEach(function(data, index) {
                                // Memeriksa apakah user yang login adalah admin
                                const isAdmin = @json(auth()->user()->role === 'admin');
                                let deleteButton = '';
                                if (isAdmin) {
                                    deleteButton =
                                        `<button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}">Delete</button>`;
                                }

                                rows += `<tr>
                                    <td>${(response.data.from - 1) + index + 1}</td>
                                    <td>
                                        <img src="{{ asset('storage') }}/${data.foto_kejadian}" alt="Foto Kejadian" width="100" height="100" data-bs-toggle="modal" data-bs-target="#imageModal" class="clickable-image" data-image="{{ asset('storage') }}/${data.foto_kejadian}">
                                    </td>
                                    <td>${data.jenis_kejadian}</td>
                                    <td>${formatDate(data.created_at)}</td>
                                    <!-- Tampilkan kolom aksi hanya jika admin -->
                                    @if (auth()->user()->role === 'admin')
                                        <td>${deleteButton}</td>
                                    @endif
                                </tr>`;
                            });
                            $('#reportsTable tbody').html(rows);
                            $('#totalData').text('Total Data: ' + response.data
                                .total); // Update total data

                            // Pagination
                            let pagination = '';
                            pagination += `<ul class="pagination">`;
                            if (!response.data.prev_page_url) {
                                pagination +=
                                    `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                            } else {
                                pagination +=
                                    `<li class="page-item"><a class="page-link" href="#" data-page="${response.data.current_page - 1}">Previous</a></li>`;
                            }

                            for (let i = 1; i <= response.data.last_page; i++) {
                                if (i === response.data.current_page) {
                                    pagination +=
                                        `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                                } else {
                                    pagination +=
                                        `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                                }
                            }

                            if (!response.data.next_page_url) {
                                pagination +=
                                    `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                            } else {
                                pagination +=
                                    `<li class="page-item"><a class="page-link" href="#" data-page="${response.data.current_page + 1}">Next</a></li>`;
                            }
                            pagination += `</ul>`;

                            $('#paginationNav').html(pagination); // Update pagination
                        } else {
                            $('#reportsTable tbody').html(
                                '<tr><td colspan="5">No data available.</td></tr>');
                            $('#totalData').text('Total Data: 0');
                        }

                        // Sembunyikan animasi loading setelah data dimuat
                        if (isFirstLoad === false) {
                            $('#loading').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error); // Debugging: cek error
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data laporan.', 'error');
                        $('#loading').hide(); // Sembunyikan animasi loading
                    }
                });
            }

            // Panggil fungsi loadData saat halaman pertama kali dimuat
            loadData();

            // Interval untuk memuat ulang data setiap 5 detik
            setInterval(function() {
                loadData(); // Reload data tanpa spinner
            }, 5000);

            // Event delegation untuk pagination
            $('#paginationNav').on('click', '.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                loadData(page); // Panggil fungsi loadData dengan halaman yang sesuai
            });

            // Event delegation untuk tombol delete
            $('#reportsTable').on('click', '.delete-btn', function() {
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
                            url: `{{ route('api.reports.destroy', '') }}/${id}`,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire('Berhasil', response.message, 'success').then(
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

            // Event handler untuk tombol delete all
            $('#deleteAllBtn').click(function() {
                console.log('Delete All button clicked'); // Debugging: cek tombol di-klik

                Swal.fire({
                    title: 'Konfirmasi Hapus Semua',
                    text: 'Apakah Anda yakin ingin menghapus semua laporan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(
                        'User confirmed delete all'); // Debugging: cek apakah user mengonfirmasi

                        // Kirim permintaan DELETE untuk menghapus semua data
                        $.ajax({
                            url: "{{ route('api.reports.deleteAll') }}",
                            type: 'DELETE', // Pastikan tipe adalah DELETE
                            dataType: 'json',
                            success: function(response) {
                                console.log('Response:',
                                response); // Periksa apakah respons sukses atau gagal
                                if (response.success) {
                                    Swal.fire('Berhasil', response.message, 'success')
                                        .then(() => {
                                            loadData
                                        (); // Reload data setelah berhasil menghapus
                                        });
                                } else {
                                    Swal.fire('Gagal', response.message, 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('AJAX Error: ', error);
                                console.log('Response Status: ', xhr.status);
                                console.log('Response Text: ', xhr.responseText);
                                Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                            }
                        });
                    } else {
                        console.log(
                        'User canceled delete all'); // Debugging: cek jika user membatalkan
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
