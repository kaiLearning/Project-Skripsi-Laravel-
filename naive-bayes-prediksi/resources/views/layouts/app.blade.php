<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Sembako Kaka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar {
            /* padding: 0.25rem 1rem; */
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            border-bottom: 2px solid #74ebd5;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 24px;
            color: #ffffff !important;
        }

        .nav-link {
            font-size: 16px;
            font-weight: 500;
            color: #ffffff !important;
            padding-left: 15px;
            padding-right: 15px;
        }

        .nav-link:hover {
            color: #fff !important;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .navbar-toggler-icon {
            background-color: #ffffff;
        }

        .nav-item.active .nav-link,
        .nav-item:hover .nav-link {
            background-color: rgba(116, 235, 213, 0.2);
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>

<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/home') }}">Toko Sembako Kaka</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('prediksi.index') }}">Prediksi Penjualan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penjualan.index') }}">Penjualan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('master-produk.index') }}">Master Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rule.index') }}">Rule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('akurasi.index') }}">Akurasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/logout') }}">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth
    <main class="py-2">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>