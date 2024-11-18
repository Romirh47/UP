@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Daftar Laporan</h2>
                    <!-- Loading Spinner -->
                    <div id="loading"
                        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center; padding-top: 20%;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Foto Kejadian</th>
                                    <th scope="col">Jenis Kejadian</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="reports-table">
                                <!-- Data akan dimuat secara otomatis menggunakan polling -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Tautan Pagination -->
                    <div class="d-flex justify-content-start mt-3">
                        <nav aria-label="Navigasi Halaman">
                            <ul class="pagination pagination-sm" id="pagination-links">
                                <!-- Pagination links akan dimuat secara dinamis -->
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Menampilkan Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Kejadian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Foto Kejadian" class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk memuat data laporan
            function loadReports(page = 1) {
                $('#loading').show(); // Tampilkan spinner loading

                $.ajax({
                    url: "{{ route('api.reports.index') }}", // Pastikan URL API sesuai
                    method: 'GET',
                    data: {
                        page: page
                    },
                    success: function(response) {
                        $('#loading').hide(); // Sembunyikan spinner loading
                        $('#reports-table tbody').empty(); // Kosongkan tabel sebelum mengisi ulang

                        if (response.data.length === 0) {
                            $('#reports-table tbody').append(
                                '<tr><td colspan="4" class="text-center">Tidak ada data laporan.</td></tr>'
                                );
                        } else {
                            let reports = response.data;
                            reports.forEach(report => {
                                $('#reports-table tbody').append(`
                            <tr>
                                <td>${report.no}</td>
                                <td>
                                    ${report.foto_kejadian ? `<img src="${report.foto_kejadian}" alt="Foto Kejadian" class="img-thumbnail clickable-image" style="max-width: 100px;" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="${report.foto_kejadian}">` : 'Tidak Ada Foto'}
                                </td>
                                <td>${report.jenis_kejadian}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${report.id}">
                                        <i class="cil-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `);
                            });

                            // Handle pagination links
                            let pagination = '';
                            for (let i = 1; i <= response.last_page; i++) {
                                pagination += `<li class="page-item ${response.current_page === i ? 'active' : ''}">
                                      <a class="page-link" href="#" data-page="${i}">${i}</a>
                                  </li>`;
                            }
                            $('#pagination-links').html(pagination);
                        }
                    },
                    error: function(xhr) {
                        $('#loading').hide(); // Sembunyikan spinner loading
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memuat data!',
                        });
                    }
                });
            }

            // Polling untuk memuat data setiap 5 detik
            setInterval(function() {
                loadReports();
            }, 5000); // 5000 ms = 5 detik

            // Handle click pada pagination
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                loadReports(page);
            });

            // Menampilkan foto dalam modal
            $(document).on('click', '.clickable-image', function() {
                var imageSrc = $(this).data('src');
                $('#modalImage').attr('src', imageSrc);
            });

            // Menghapus laporan
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data ini tidak dapat dikembalikan setelah dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ route('api.reports.destroy', '') }}/' + id,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Dihapus!',
                                    response.message || 'Laporan berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    loadReports
                                (); // Reload data setelah penghapusan
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan!',
                                });
                            }
                        });
                    }
                });
            });

            // Load initial data
            loadReports();
        });
    </script>
@endpush
