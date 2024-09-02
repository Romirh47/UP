@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Data Actuator</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <div id="totalData" class="me-3"></div> <!-- Elemen untuk menampilkan jumlah data -->
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
            function loadData(page = 1) {
                $('#loading').show();
                $.ajax({
                    url: "{{ route('api.actuator_values.index') }}?page=" + page,
                    type: 'GET',
                    success: function(response) {
                        $('#loading').hide();
                        let rows = '';
                        if (response.data && Array.isArray(response.data)) {
                            response.data.forEach(function(actuatorValue, index) {
                                rows += `<tr>
                                    <td>${(response.from + index)}</td>
                                    <td>${actuatorValue.actuator ? actuatorValue.actuator.name : 'Tidak ada'}</td>
                                    <td>${actuatorValue.value === 1 ? 'On' : 'Off'}</td>
                                    <td>${formatDate(actuatorValue.created_at)}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${actuatorValue.id}">Hapus</button>
                                    </td>
                                </tr>`;
                            });
                            $('#actuatorTable tbody').html(rows);

                            // Update jumlah data
                            $('#totalData').text(`Jumlah Data: ${response.total}`);
                        } else {
                            $('#actuatorTable tbody').html(
                                '<tr><td colspan="5" class="text-center">Tidak ada data ditemukan</td></tr>'
                            );

                            // Update jumlah data
                            $('#totalData').text('Jumlah Data: 0');
                        }

                        // Pagination
                        let pagination = '';
                        pagination += `<ul class="pagination">`;
                        if (response.prev_page_url) {
                            pagination +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}">Previous</a></li>`;
                        } else {
                            pagination +=
                                `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
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

                        if (response.next_page_url) {
                            pagination +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}">Next</a></li>`;
                        } else {
                            pagination +=
                                `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                        }
                        pagination += `</ul>`;

                        $('#paginationNav').html(pagination);
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        console.error('Error:', xhr.responseText);
                        Swal.fire('Terjadi kesalahan', 'Tidak dapat memuat data actuator.', 'error');
                    }
                });
            }

            loadData();

            $('#actuatorTable').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data actuator yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('api.actuator_values.destroy', '') }}/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Terhapus!',
                                    'Data actuator berhasil dihapus.', 'success');
                                loadData();
                            },
                            error: function(xhr) {
                                console.error('Error:', xhr.responseText);
                                Swal.fire('Terjadi kesalahan',
                                    'Tidak dapat menghapus data actuator.', 'error');
                            }
                        });
                    }
                });
            });

            // Pagination click handler
            $('#paginationNav').on('click', 'a.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                if (page) {
                    loadData(page);
                }
            });

            function formatDate(dateStr) {
                let date = new Date(dateStr);
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            }
        });
    </script>
@endpush
