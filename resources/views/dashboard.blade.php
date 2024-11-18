@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Card Total Laporan -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title" style="color: #28a745; text-align: center;">Total Laporan</h3>
                <h3 class="card-text" id="totalReports" style="color: rgb(0, 0, 0); font-weight: bold; text-align: center;">Loading...</h3>
            </div>
        </div>
    </div>

    <!-- Card Total Pengguna -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title" style="color: #28a745; text-align: center;">Total Pengguna</h3>
                <h3 class="card-text" id="totalUsers" style="color: rgb(0, 0, 0); font-weight: bold; text-align: center;">Loading...</h3>
            </div>
        </div>
    </div>

    <!-- Card Distribusi Role Pengguna (Progress Bar) -->
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title" style="color: #28a745;">Distribusi Role Pengguna</h5>
                <div class="progress mb-3">
                    <div class="progress-bar" id="adminProgress" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0% - 0 Admin
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" id="userProgress" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0% - 0 User
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
                <p id="chartMessage" class="text-center mt-3">Data sedang dimuat...</p>
            </div>
        </div>
    </div>

    <!-- Laporan Terbaru (Carousel) -->
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title" style="color: #28a745;">Laporan Terbaru</h5>
                <div id="carouselReports" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselInner">
                        <!-- Laporan akan dimuat disini -->
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    function loadData() {
        $.ajax({
            url: "{{ route('api.dashboard.index') }}",
            method: "GET",
            success: function (data) {
                // Update total laporan
                $('#totalReports').text(data.totalReports);
                $('#totalUsers').text(data.totalAdmins + data.totalUsers);

                // Update Progress Bars
                let totalUsers = data.totalAdmins + data.totalUsers;
                $('#adminProgress').css('width', (data.totalAdmins / totalUsers * 100) + '%')
                    .text(`${(data.totalAdmins / totalUsers * 100).toFixed(2)}% - ${data.totalAdmins} Admin`);
                $('#userProgress').css('width', (data.totalUsers / totalUsers * 100) + '%')
                    .text(`${(data.totalUsers / totalUsers * 100).toFixed(2)}% - ${data.totalUsers} User`);

                // Memuat Chart (Jenis Kejadian)
                const chartLabels = data.chartData.map(item => item.jenis_kejadian);
                const chartData = data.chartData.map(item => item.count);

                const ctx = document.getElementById('jenisKejadianChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
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

                // Memuat laporan terbaru
                const latestReports = data.latestReports;
                if (latestReports.length > 0) {
                    let carouselItems = '';
                    latestReports.forEach((report, index) => {
                        carouselItems += `
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="${report.foto_kejadian ? '/storage/' + report.foto_kejadian : '#'}" class="d-block w-100" alt="Foto Kejadian">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>${report.jenis_kejadian}</h5>
                                    <p>${new Date(report.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                        `;
                    });
                    $('#carouselInner').html(carouselItems);
                } else {
                    $('#carouselInner').html('<div class="carousel-item active"><div class="text-center"><p>Belum ada laporan terbaru</p></div></div>');
                }

                // Menghilangkan pesan loading untuk chart
                $('#chartMessage').text('');
            },
            error: function (xhr, status, error) {
                console.error('Error loading data: ', error);
                alert('Gagal memuat data.');
            }
        });
    }

    $(document).ready(function () {
        // Memuat data pertama kali
        loadData();

        // Memuat data setiap 5 detik
        setInterval(loadData, 3000);
    });
</script>
@endpush
