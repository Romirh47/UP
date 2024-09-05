@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Data Settings</h2>
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Display total number of settings -->
                        <div class="fw-bold">Total Data: {{ $settings->total() }}</div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ti ti-plus fs-6 me-2"></i> TAMBAH DATA SETTING
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nama Sensor</th>
                                    <th scope="col">Nama Aktuator</th>
                                    <th scope="col">Nilai Minimum</th>
                                    <th scope="col">Nilai Maksimum</th>
                                    <th scope="col">Aksi Aktuator</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settings as $index => $setting)
                                    <tr>
                                        <td>{{ $index + 1 + ($settings->currentPage() - 1) * $settings->perPage() }}</td>
                                        <td>{{ $setting->id }}</td>
                                        <td>{{ $setting->sensor->name }}</td>
                                        <td>{{ $setting->actuator->name }}</td>
                                        <td>{{ $setting->min_value }}</td>
                                        <td>{{ $setting->max_value }}</td>
                                        <td>{{ $setting->actuator_action == 1 ? 'ON' : 'OFF' }}</td>
                                        <td>
                                            <!-- Edit button triggers the correct modal -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $setting->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('web.settings.destroy', $setting->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                @if ($settings->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $settings->previousPageUrl() }}"
                                            aria-disabled="false">Previous</a>
                                    </li>
                                @endif

                                @foreach ($settings->getUrlRange(1, $settings->lastPage()) as $page => $url)
                                    <li class="page-item {{ $settings->currentPage() == $page ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($settings->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $settings->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" aria-disabled="true">Next</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data Setting -->
    <div class="modal fade @if ($errors->any()) show @endif" id="tambahModal" tabindex="-1"
        aria-labelledby="tambahModalLabel" aria-hidden="true"
        @if ($errors->any()) style="display: block;" @endif>
        <div class="modal-dialog">
            <form id="tambahForm" action="{{ route('web.settings.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Setting</h5>
                    <!-- Simbol 'x' untuk menutup modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tampilkan pesan kesalahan global jika ada -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Input Fields -->
                    <div class="mb-3">
                        <label for="sensor_id" class="form-label">Sensor</label>
                        <select class="form-select" id="sensor_id" name="sensor_id" required>
                            <option value="" disabled selected>Pilih Sensor</option>
                            @foreach ($sensors as $sensor)
                                <option value="{{ $sensor->id }}"
                                    {{ old('sensor_id') == $sensor->id ? 'selected' : '' }}>{{ $sensor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="actuator_id" class="form-label">Aktuator</label>
                        <select class="form-select" id="actuator_id" name="actuator_id" required>
                            <option value="" disabled selected>Pilih Aktuator</option>
                            @foreach ($actuators as $actuator)
                                <option value="{{ $actuator->id }}"
                                    {{ old('actuator_id') == $actuator->id ? 'selected' : '' }}>{{ $actuator->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="min_value" class="form-label">Nilai Minimum</label>
                        <input type="number" step="0.01" class="form-control @error('min_value') is-invalid @enderror"
                            id="min_value" name="min_value" placeholder="Masukkan nilai minimum"
                            value="{{ old('min_value') }}" required>
                        @error('min_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="max_value" class="form-label">Nilai Maksimum</label>
                        <input type="number" step="0.01" class="form-control @error('max_value') is-invalid @enderror"
                            id="max_value" name="max_value" placeholder="Masukkan nilai maksimum"
                            value="{{ old('max_value') }}" required>
                        @error('max_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="actuator_action" class="form-label">Aksi Aktuator</label>
                        <select class="form-select @error('actuator_action') is-invalid @enderror" id="actuator_action"
                            name="actuator_action" required>
                            <option value="" disabled selected>Pilih Aksi Aktuator</option>
                            <option value="1" {{ old('actuator_action') == '1' ? 'selected' : '' }}>ON</option>
                            <option value="0" {{ old('actuator_action') == '0' ? 'selected' : '' }}>OFF</option>
                        </select>
                        @error('actuator_action')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Tombol Batal untuk menutup modal -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Data Setting -->
    @foreach ($settings as $setting)
        <div class="modal fade @if (session('edit_id') == $setting->id && $errors->any()) show @endif" id="editModal{{ $setting->id }}"
            tabindex="-1" aria-labelledby="editModalLabel{{ $setting->id }}" aria-hidden="true"
            @if (session('edit_id') == $setting->id && $errors->any()) style="display: block;" @endif>
            <div class="modal-dialog">
                <form action="{{ route('web.settings.update', $setting->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $setting->id }}">Edit Data Setting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tampilkan pesan kesalahan global jika ada -->
                        @if (session('edit_id') == $setting->id && $errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form Fields -->
                        <div class="mb-3">
                            <label for="sensor_id" class="form-label">Sensor</label>
                            <select class="form-select @error('sensor_id') is-invalid @enderror" id="sensor_id"
                                name="sensor_id" required>
                                <option value="" disabled>Pilih Sensor</option>
                                @foreach ($sensors as $sensor)
                                    <option value="{{ $sensor->id }}"
                                        {{ $sensor->id == old('sensor_id', $setting->sensor_id) ? 'selected' : '' }}>
                                        {{ $sensor->name }}</option>
                                @endforeach
                            </select>
                            @error('sensor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="actuator_id" class="form-label">Aktuator</label>
                            <select class="form-select @error('actuator_id') is-invalid @enderror" id="actuator_id"
                                name="actuator_id" required>
                                <option value="" disabled>Pilih Aktuator</option>
                                @foreach ($actuators as $actuator)
                                    <option value="{{ $actuator->id }}"
                                        {{ $actuator->id == old('actuator_id', $setting->actuator_id) ? 'selected' : '' }}>
                                        {{ $actuator->name }}</option>
                                @endforeach
                            </select>
                            @error('actuator_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="min_value" class="form-label">Nilai Minimum</label>
                            <input type="number" step="0.01"
                                class="form-control @error('min_value') is-invalid @enderror" id="min_value"
                                name="min_value" value="{{ old('min_value', $setting->min_value) }}"
                                placeholder="Masukkan nilai minimum" required>
                            @error('min_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="max_value" class="form-label">Nilai Maksimum</label>
                            <input type="number" step="0.01"
                                class="form-control @error('max_value') is-invalid @enderror" id="max_value"
                                name="max_value" value="{{ old('max_value', $setting->max_value) }}"
                                placeholder="Masukkan nilai maksimum" required>
                            @error('max_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="actuator_action" class="form-label">Aksi Aktuator</label>
                            <select class="form-select @error('actuator_action') is-invalid @enderror"
                                id="actuator_action" name="actuator_action" required>
                                <option value="" disabled>Pilih Aksi Aktuator</option>
                                <option value="1"
                                    {{ old('actuator_action', $setting->actuator_action) == 1 ? 'selected' : '' }}>ON
                                </option>
                                <option value="0"
                                    {{ old('actuator_action', $setting->actuator_action) == 0 ? 'selected' : '' }}>OFF
                                </option>
                            </select>
                            @error('actuator_action')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection
