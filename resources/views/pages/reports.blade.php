@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Daftar Laporan</h2>

                    <!-- Tombol Hapus Semua -->
                    <div class="mb-3">
                        <button class="btn btn-danger btn-sm" id="delete-all-btn">
                            <i class="cil-trash"></i> Hapus Semua
                        </button>
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
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                                        </td>
                                        <td>
                                            @if ($report->foto_kejadian)
                                                <img src="{{ Storage::url($report->foto_kejadian) }}" alt="Foto Kejadian"
                                                    class="img-thumbnail" style="max-width: 100px;">
                                            @else
                                                Tidak Ada Foto
                                            @endif
                                        </td>
                                        <td>{{ $report->jenis_kejadian }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $report->id }}">
                                                <i class="cil-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tautan Pagination -->
                    <div class="d-flex justify-content-start mt-3">
                        <nav aria-label="Navigasi Halaman">
                            <ul class="pagination pagination-sm">
                                <li class="page-item {{ $reports->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $reports->previousPageUrl() }}" aria-label="Sebelumnya">
                                        <span aria-hidden="true">&laquo; Sebelumnya</span>
                                    </a>
                                </li>

                                @for ($i = 1; $i <= $reports->lastPage(); $i++)
                                    <li class="page-item {{ $reports->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $reports->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ !$reports->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $reports->nextPageUrl() }}" aria-label="Berikutnya">
                                        <span aria-hidden="true">Berikutnya &raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Hapus laporan individu
            $('.delete-btn').on('click', function() {
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
                            url: '{{ url('api/reports/:id') }}'.replace(':id', id),
                            success: function(response) {
                                Swal.fire(
                                    'Dihapus!',
                                    response.message || 'Laporan berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                var errorMessage = 'Terjadi kesalahan!';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: errorMessage,
                                });
                            }
                        });
                    }
                });
            });

            // Hapus semua laporan
            $('#delete-all-btn').on('click', function() {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Semua data akan dihapus dan tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus semua!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ url('api/reports/destroyAll') }}',
                            success: function(response) {
                                Swal.fire(
                                    'Dihapus!',
                                    response.message ||
                                    'Semua laporan berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                var errorMessage = 'Terjadi kesalahan!';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: errorMessage,
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
