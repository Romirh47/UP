@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Daftar Actuators</h2>
                    <p id="totalCount" class="mb-3">Total Actuators: 0</p> <!-- Tambahkan elemen ini -->
                    <div class="d-flex justify-content-end mb-3">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ti ti-plus fs-6 me-2"></i> TAMBAH ACTUATOR
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Actuators</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Dibuat</th>
                                    <th scope="col">Diperbarui</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat di sini -->
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

    <!-- Modal tambah actuator -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="tambahForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Actuator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required
                            placeholder="Enter Name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal edit actuator -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Actuator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required
                            placeholder="Enter Name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" placeholder="Enter Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading animation -->
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
                return `${day} ${month} ${year} ${hours}:${minutes}`;
            }

            // Fungsi untuk memuat data actuator dan pagination
            function loadActuators(page = 1) {
                $('#loading').show(); // Tampilkan animasi loading
                $.ajax({
                    url: "{{ route('api.actuators.index') }}?page=" + page,
                    type: 'GET',
                    success: function(response) {
                        let rows = '';
                        response.data.forEach(function(actuator, index) {
                            rows += `<tr>
                    <td>${(response.from + index)}</td>
                    <td>${actuator.name}</td>
                    <td>${actuator.description || 'N/A'}</td>
                    <td>${formatDate(actuator.created_at)}</td>
                    <td>${formatDate(actuator.updated_at)}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${actuator.id}" data-name="${actuator.name}" data-description="${actuator.description}" data-bs-toggle="modal" data-bs-target="#editModal">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${actuator.id}">
                            Delete
                        </button>
                    </td>
                </tr>`;
                        });
                        $('#dataTable tbody').html(rows);

                        // Perbarui jumlah data actuator
                        $('#totalCount').text(`Total Actuators: ${response.total}`);

                        // Pagination
                        let pagination = '';
                        pagination += `<ul class="pagination">`;
                        if (!response.prev_page_url) {
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

                        if (!response.next_page_url) {
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
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data actuator.', 'error');
                    },
                    complete: function() {
                        $('#loading').hide(); // Sembunyikan animasi loading
                    }
                });
            }


            // Panggil fungsi loadActuators saat halaman pertama kali dimuat
            loadActuators();

            // Event delegation untuk pagination
            $('#paginationNav').on('click', '.page-link', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page) {
                    loadActuators(page);
                }
            });

            // Tambah actuator
            $('#tambahForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah reload halaman
                $('#loading').show(); // Menampilkan animasi loading

                $.ajax({
                    type: 'POST',
                    url: '{{ route('api.actuators.store') }}',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log('Respons tambah actuator:', response); // Debugging
                        $('#tambahModal').modal('hide'); // Menutup modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Actuator berhasil ditambahkan!',
                        }).then(() => {
                            loadActuators(); // Memuat ulang data actuators
                            // Reset form dan hapus pesan kesalahan
                            $('#tambahForm')[0].reset();
                            $('#tambahForm').find('.is-invalid').removeClass(
                                'is-invalid');
                            $('#tambahForm').find('.invalid-feedback').text('');
                        });
                    },
                    error: function(xhr) {
                        console.error('Error AJAX tambah actuator:', xhr); // Debugging
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON.errors.name ?
                                'Nama actuator sudah ada!' : 'Terjadi kesalahan!',
                        });
                    },
                    complete: function() {
                        $('#loading').hide(); // Menyembunyikan animasi loading
                    }
                });
            });

            // Edit actuator
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let description = $(this).data('description');
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_description').val(description);
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                let formData = $(this).serialize();
                $('#loading').show(); // Menampilkan animasi loading

                $.ajax({
                    type: 'PUT',
                    url: '{{ route('api.actuators.update', ':id') }}'.replace(':id', id),
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Respons edit actuator:', response); // Debugging
                        $('#editModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Actuator berhasil diperbarui!',
                        }).then(() => {
                            loadActuators(); // Memuat ulang data actuators
                        });
                    },
                    error: function(xhr) {
                        console.error('Error AJAX edit actuator:', xhr); // Debugging
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON.errors.name ?
                                'Nama actuator sudah ada!' : 'Terjadi kesalahan!',
                        });
                    },
                    complete: function() {
                        $('#loading').hide(); // Menyembunyikan animasi loading
                    }
                });
            });

            // Hapus actuator
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                console.log('ID yang akan dihapus:', id); // Debugging

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak dapat mengembalikan tindakan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#loading').show(); // Menampilkan animasi loading

                        $.ajax({
                            url: '{{ route('api.actuators.destroy', ':id') }}'.replace(
                                ':id', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log('Respons hapus actuator:',
                                response); // Debugging
                                Swal.fire(
                                    'Terhapus!',
                                    response.message ||
                                    'Data actuator berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    loadActuators
                                (); // Memuat ulang data actuators
                                });
                            },
                            error: function(xhr) {
                                console.error('Error AJAX hapus actuator:',
                                xhr); // Debugging
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: xhr.responseJSON.message ||
                                        'Terjadi kesalahan!',
                                });
                            },
                            complete: function() {
                                $('#loading').hide(); // Menyembunyikan animasi loading
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
