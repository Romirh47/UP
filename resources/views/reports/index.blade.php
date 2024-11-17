@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Daftar Laporan</h1>

    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createReportModal">
        Tambah Laporan
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Jenis Insiden</th>
                <th>Snapshot</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="reportsTableBody">
            @foreach($reports as $report)
            <tr data-id="{{ $report->id }}">
                <td>{{ $report->id }}</td>
                <td>{{ $report->incident_type }}</td>
                <td>
                    <img src="{{ asset('storage/' . $report->snapshot_path) }}" alt="Snapshot" width="100">
                </td>
                <td>
                    <button class="btn btn-warning edit-report" data-id="{{ $report->id }}" data-incident="{{ $report->incident_type }}" data-snapshot="{{ asset('storage/' . $report->snapshot_path) }}">Edit</button>
                    <button class="btn btn-danger delete-report" data-id="{{ $report->id }}">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal untuk Menambah Laporan -->
<div class="modal fade" id="createReportModal" tabindex="-1" role="dialog" aria-labelledby="createReportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="createReportForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReportModalLabel">Tambah Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="incident_type">Jenis Insiden</label>
                        <input type="text" class="form-control" name="incident_type" required>
                    </div>
                    <div class="form-group">
                        <label for="snapshot">Unggah Gambar Snapshot</label>
                        <input type="file" class="form-control" name="snapshot_path" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Mengedit Laporan -->
<div class="modal fade" id="editReportModal" tabindex="-1" role="dialog" aria-labelledby="editReportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editReportForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReportModalLabel">Edit Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editReportId">
                    <div class="form-group">
                        <label for="edit_incident_type">Jenis Insiden</label>
                        <input type="text" class="form-control" name="incident_type" id="editIncidentType" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_snapshot_path">Path Snapshot</label>
                        <input type="file" class="form-control" name="snapshot_path">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Menangani pengiriman formulir untuk menambah laporan
        $('#createReportForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: '{{ route('web.reports.store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#createReportModal').modal('hide');
                    $('#reportsTableBody').append(`
                        <tr data-id="${response.id}">
                            <td>${response.id}</td>
                            <td>${response.incident_type}</td>
                            <td><img src="/storage/${response.snapshot_path}" alt="Snapshot" width="100"></td>
                            <td>
                                <button class="btn btn-warning edit-report" data-id="${response.id}" data-incident="${response.incident_type}" data-snapshot="/storage/${response.snapshot_path}">Edit</button>
                                <button class="btn btn-danger delete-report" data-id="${response.id}">Hapus</button>
                            </td>
                        </tr>
                    `);
                    alert(response.success);
                }
            });
        });

        // Menangani pengisian formulir untuk mengedit laporan
        $(document).on('click', '.edit-report', function() {
            const id = $(this).data('id');
            const incident = $(this).data('incident');
            $('#editReportId').val(id);
            $('#editIncidentType').val(incident);
            $('#editReportModal').modal('show');
        });

        // Menangani pengiriman formulir untuk mengedit laporan
        $('#editReportForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#editReportId').val();
            const formData = new FormData(this);
            $.ajax({
                url: `{{ url('reports') }}/${id}`,
                type: 'PUT',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    const row = $(`tr[data-id="${id}"]`);
                    row.find('td:nth-child(2)').text($('#editIncidentType').val());
                    $('#editReportModal').modal('hide');
                    alert(response.success);
                }
            });
        });

        // Menangani penghapusan laporan
        $(document).on('click', '.delete-report', function() {
            const id = $(this).data('id');
            if(confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
                $.ajax({
                    url: `{{ url('reports') }}/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        $(`tr[data-id="${id}"]`).remove();
                        alert(response.success);
                    }
                });
            }
        });
    });
</script>
@endpush
