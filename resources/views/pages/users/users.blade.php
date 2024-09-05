@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Daftar Pengguna</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="cil-plus fs-6 me-2"></i> Tambah Pengguna
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                        <td>
                                            @if ($user->photo)
                                                <img src="{{ Storage::url($user->photo) }}" alt="Foto Pengguna"
                                                    class="img-thumbnail" style="max-width: 100px;">
                                            @else
                                                Tidak Ada Foto
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role == 'admin')
                                                <span class="badge bg-primary">Admin</span>
                                            @else
                                                <span class="badge bg-secondary">User</span>
                                            @endif
                                        <td>
                                            <button class="btn btn-sm btn-info detail-btn" data-id="{{ $user->id }}"
                                                data-bs-toggle="modal" data-bs-target="#detailModal">
                                                <i class="cil-zoom"></i> Detail
                                            </button>
                                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                                data-photo="{{ $user->photo }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                                <i class="cil-pencil"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}">
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
                                <!-- Tampilkan tombol sebelumnya -->
                                <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Sebelumnya">
                                        <span aria-hidden="true">&laquo; Sebelumnya</span>
                                    </a>
                                </li>

                                <!-- Tampilkan nomor halaman -->
                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <!-- Tampilkan tombol berikutnya -->
                                <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Berikutnya">
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

    <!-- Modal tambah pengguna -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="tambahForm" enctype="multipart/form-data" method="POST"
                action="{{ route('web.users.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required
                            placeholder="Masukkan Nama">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required
                            placeholder="Masukkan Email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Masukkan Kata Sandi">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="admin">Admin</option>
                            <option value="user" selected>User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto (Opsional)</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal edit pengguna -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="editForm" enctype="multipart/form-data" method="POST"
                action="{{ route('web.users.update', '') }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required
                            placeholder="Masukkan Nama">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required
                            placeholder="Masukkan Email">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="edit_password" name="password"
                            placeholder="Masukkan Kata Sandi Baru">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Foto (Opsional)</label>
                        <input type="file" class="form-control" id="edit_photo" name="photo">
                        <img id="edit_photo_preview" class="img-thumbnail mt-2" style="max-width: 100px; display: none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal detail pengguna -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama:</strong> <span id="detail_name"></span></p>
                    <p><strong>Email:</strong> <span id="detail_email"></span></p>
                    <p><strong>Role:</strong> <span id="detail_role"></span></p>
                    <p><strong>Foto:</strong> <br> <img id="detail_photo" src="" alt="Foto Pengguna"
                            class="img-fluid" style="display: none; max-width: 300px;"></p>
                    <!-- Ukuran gambar lebih besar -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Tambah pengguna
            $('#tambahForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('web.users.store') }}', // Pastikan route ini benar
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#tambahModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message || 'Pengguna berhasil ditambahkan.',
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Cek jika ada respons dari server
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
            });

            // Set data modal edit
            $('.edit-btn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let photo = $(this).data('photo');
                let role = $(this).data('role');

                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_email').val(email);
                $('#edit_role').val(role); // Set value for role

                if (photo) {
                    $('#edit_photo_preview').attr('src', "{{ Storage::url('') }}" + photo).show();
                } else {
                    $('#edit_photo_preview').hide();
                }
            });

            // Edit pengguna
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = $('#edit_id').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ url('users') }}/' + id,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#editModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message || 'Pengguna berhasil diperbarui.',
                        }).then(() => {
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
            });

            // Detail pengguna
            $('.detail-btn').on('click', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: '/users/' + id,
                    success: function(response) {
                        $('#detail_name').text(response.user.name);
                        $('#detail_email').text(response.user.email);
                        $('#detail_role').text(response.user.role); // Menampilkan role
                        if (response.user.photo) {
                            $('#detail_photo').attr('src', "{{ Storage::url('') }}" + response
                                .user.photo).show();
                        } else {
                            $('#detail_photo').hide();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan!',
                        });
                    }
                });
            });


            // Hapus pengguna
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
                            url: '{{ route('api.users.destroy', ':id') }}'.replace(':id',
                                id),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Dihapus!',
                                    response.message ||
                                    'Pengguna berhasil dihapus.',
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
