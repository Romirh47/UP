@extends('pages.dashboard.index')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah">
                            <i class="ti ti-plus fs-6 me-2"></i>
                            TAMBAH VALUE
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($temperatures as $temperature)
                                    <tr>
                                        <td>{{ $temperature->id }}</td>
                                        <td>{{ $temperature->value }}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal"
                                               data-bs-target="#edit" data-userid="{{ $temperature->id }}"
                                               data-username="{{ $temperature->value }}"
                                               data-useremail="{{ $temperature->value }}">Edit</a>

                                            <form action="{{ route('temperatures.destroy', $temperature->id) }}" method="POST"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('apakah kamu yakin untuk menghapus pengguna ini ?')">Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal tambah value -->
    <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('temperatures.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah value</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" class="form-control" id="value" name="value" required
                               placeholder="Masukkan Value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal edit users-->
 <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('temperatures.update', $temperature->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Value</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="nama" class="form-label">Value</label>
                    <input type="number" class="form-control" id="value" name="value" required
                        placeholder="masukan perubahan" value="{{ $temperature->value }}">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
