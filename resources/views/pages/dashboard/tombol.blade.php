<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Aktuator</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS for Switch -->
    <style>
        /* Style untuk switch */
        .custom-switch .custom-control-label {
            font-size: 1.2rem; /* Ukuran teks label */
            position: relative;
            text-align: left; /* Posisi teks di kiri */
            padding-left: 3rem; /* Padding kiri untuk menghindari tumpang tindih dengan switch */
            z-index: 2; /* Mengatur lapisan teks di atas switch */
        }
        .custom-switch .custom-control-label::before {
            width: 3rem; /* Lebar background switch */
            height: 1.5rem; /* Tinggi background switch */
            border-radius: 1rem; /* Rounded corners */
            z-index: 0; /* Mengatur lapisan belakang */
        }
        .custom-switch .custom-control-label::after {
            width: 2rem; /* Lebar pegangan switch */
            height: 2rem; /* Tinggi pegangan switch */
            border-radius: 50%; /* Rounded pegangan */
            z-index: 1; /* Mengatur lapisan depan */
        }
        .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #28a745; /* Warna latar belakang ketika aktif */
        }
        .custom-switch .custom-control-input:checked ~ .custom-control-label::after {
            background-color: white; /* Warna pegangan ketika aktif */
        }
        .card {
            margin-top: 2rem; /* Jarak atas card */
            border-radius: 1rem; /* Rounded corners card */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Shadow card */
            padding: 1rem; /* Padding dalam card */
            display: flex; /* Menggunakan flexbox untuk layout */
            justify-content: space-between; /* Menyeimbangkan ruang antara elemen */
            align-items: center; /* Menyusun elemen secara vertikal */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="aktuator1Switch" checked>
                        <label class="custom-control-label" for="aktuator1Switch">pompa</label>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="aktuator2Switch">
                        <label class="custom-control-label" for="aktuator2Switch">lampu</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional for certain components) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
