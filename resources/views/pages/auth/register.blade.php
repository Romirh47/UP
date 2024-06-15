<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
  </head>

  <body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
      data-sidebar-position="fixed" data-header-position="fixed">
      <div
        class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
          <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xxl-3">
              <div class="card mb-0">
                <div class="card-body">
                  <a href="{{ route('register') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                    <img src="../assets/images/logos/logo.svg" alt="">
                  </a>
                  <p class="text-center">Your IOT Panel</p>

                  <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                      <label for="exampleInputtext1" class="form-label">Nama</label>
                      <input type="text" class="form-control" id="exampleInputtext1" aria-describedby="textHelp" name="name" required placeholder="Masukan Nama">
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label">Alamat Email</label>
                      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" required placeholder="Masukan Email">
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label">Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword1" name="password" required placeholder="Masukan Password">
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputPassword2" class="form-label">Konfirmasi Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword2" name="password_confirmation" required placeholder="Ulangi password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Masuk</button>
                    <div class="d-flex align-items-center justify-content-center">
                      <p class="fs-4 mb-0 fw-bold">Sudah punya akun?</p>
                      <a class="text-primary fw-bold ms-2" href="{{route('login')}}">Masuk</a>
                    </div>
                  </form>

                  <!-- Alert Section -->
                  @if(session('status'))
                    <div class="alert alert-success">
                      {{ session('status') }}
                    </div>
                  @endif

                  @if(session('error'))
                    <div class="alert alert-danger">
                      {{ session('error') }}
                    </div>
                  @endif

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  </body>
