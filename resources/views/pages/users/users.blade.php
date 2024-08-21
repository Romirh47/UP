@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Users List</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="cil-plus fs-6 me-2"></i> Add User
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                        <td>
                                            @if ($user->photo)
                                                <img src="{{ Storage::url($user->photo) }}" alt="User Photo"
                                                    class="img-thumbnail" style="max-width: 100px;">
                                            @else
                                                No Photo
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
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
                                                <i class="cil-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-start mt-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                <!-- Display previous button -->
                                <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo; Previous</span>
                                    </a>
                                </li>

                                <!-- Display page numbers -->
                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <!-- Display next button -->
                                <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">Next &raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal tambah user -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="tambahForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required
                            placeholder="Enter Name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required
                            placeholder="Enter Email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Enter Password">
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo (Optional)</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal edit user -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
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
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required
                            placeholder="Enter Email">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password"
                            placeholder="Enter New Password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Photo (Optional)</label>
                        <input type="file" class="form-control" id="edit_photo" name="photo">
                        <img id="edit_photo_preview" class="img-thumbnail mt-2" style="max-width: 100px; display: none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal detail user -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="detail_name"></span></p>
                    <p><strong>Email:</strong> <span id="detail_email"></span></p>
                    <p><strong>Photo:</strong> <br> <img id="detail_photo" src="" alt="User Photo"
                            class="img-fluid" style="display: none; max-width: 100px;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Tambah user
            $('#tambahForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('api.users.store') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#tambahModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                });
            });

            // Set data edit modal
            $('.edit-btn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let photo = $(this).data('photo');

                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_email').val(email);

                if (photo) {
                    $('#edit_photo_preview').attr('src', "{{ Storage::url('') }}" + photo).show();
                } else {
                    $('#edit_photo_preview').hide();
                }
            });

            // Edit user
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
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                });
            });

            // Detail user
            $('.detail-btn').on('click', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: '/users/' + id,
                    success: function(response) {
                        $('#detail_name').text(response.user.name);
                        $('#detail_email').text(response.user.email);
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
                            text: 'Something went wrong!',
                        });
                    }
                });
            });

            // Hapus user
            $('.delete-btn').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
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
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: xhr.responseJSON.message ||
                                        'Something went wrong!',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
