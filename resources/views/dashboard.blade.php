@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Card Total Laporan -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="color: #28a745; text-align: center;">Total Laporan</h5>
                <p class="card-text" style="color: rgb(0, 0, 0); font-weight: bold; text-align: center;">{{ $totalReports }}</p>
            </div>
        </div>
    </div>

    <!-- Card Total Pengguna -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="color: #28a745; text-align: center;">Total Pengguna</h5>
                <p class="card-text" style="color: rgb(0, 0, 0); font-weight: bold; text-align: center;">{{ $totalAdmins + $totalUsers }}</p>
            </div>
        </div>
    </div>

    <!-- Card Distribusi Role Pengguna (Progress Bar) -->
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title" style="color: #28a745;">Distribusi Role Pengguna</h5>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: {{ $totalAdmins / ($totalAdmins + $totalUsers) * 100 }}%;" aria-valuenow="{{ $totalAdmins }}" aria-valuemin="0" aria-valuemax="{{ $totalAdmins + $totalUsers }}">
                        {{ round($totalAdmins / ($totalAdmins + $totalUsers) * 100, 2) }}% - {{ $totalAdmins }} Admin
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $totalUsers / ($totalAdmins + $totalUsers) * 100 }}%;" aria-valuenow="{{ $totalUsers }}" aria-valuemin="0" aria-valuemax="{{ $totalAdmins + $totalUsers }}">
                        {{ round($totalUsers / ($totalAdmins + $totalUsers) * 100, 2) }}% - {{ $totalUsers }} User
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribusi Jenis Kejadian (Donut Chart) -->
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title" style="color: #28a745;">Distribusi Jenis Kejadian</h5>
                <div style="width: 300px; margin: auto;">
                    <canvas id="jenisKejadianChart"></canvas>
                </div>
                @if(count($chartData) === 0)
                    <p class="text-center mt-3">Data tidak tersedia</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Laporan Terbaru (Carousel) -->
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title" style="color: #28a745;">Laporan Terbaru</h5>
                <div id="carouselReports" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @forelse($latestReports as $index => $report)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                @if($report->foto_kejadian)
                                    <img src="{{ asset('storage/' . $report->foto_kejadian) }}" class="d-block w-100" alt="Foto Kejadian">
                                @else
                                    <div class="text-center">
                                        <p>Belum ada data</p>
                                    </div>
                                @endif
                                <div class="carousel-caption d-none d-md-block">
                                    <strong>{{ $report->jenis_kejadian }}</strong><br>
                                    <span>{{ $report->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <div class="text-center">
                                    <p>Belum ada laporan terbaru</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselReports" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselReports" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    // Prepare data for Donut Chart (Jenis Kejadian)
    const chartData = @json($chartData);
    const donutLabels = chartData.length ? chartData.map(item => item.jenis_kejadian) : ['Tidak Ada Data'];
    const donutData = chartData.length ? chartData.map(item => item.count) : [0];

    const jenisCtx = document.getElementById('jenisKejadianChart').getContext('2d');
    new Chart(jenisCtx, {
        type: 'doughnut',
        data: {
            labels: donutLabels,
            datasets: [{
                data: donutData,
                backgroundColor: ['#5733FF', '#33FFBD', '#FF57A1', '#FFBD33', '#3357FF'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 14 },
                    formatter: (value, ctx) => {
                        let total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return ((value / total) * 100).toFixed(2) + '%';
                    },
                    anchor: 'center',
                    align: 'center'
                }
            }
        }
    });
</script>
@endsection
