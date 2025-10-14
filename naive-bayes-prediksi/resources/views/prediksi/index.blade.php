@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .prediksi-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 30px;
        animation: fadeIn 0.6s ease-in-out;
    }

    .prediksi-header {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
    }

    .form-select {
        border-radius: 15px;
        padding: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }

    .table th,
    .table td {
        padding: 15px;
        text-align: center;
        vertical-align: middle;
    }

    .btn-primary {
        background: linear-gradient(to right, #74ebd5, #ACB6E5);
        border: none;
        border-radius: 25px;
        font-weight: bold;
        padding: 10px 24px;
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #60d9c9, #9fb4e1);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="prediksi-card">
                <div class="prediksi-header">📊 Prediksi Penjualan</div>

                <form method="GET" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-auto">
                            <label for="bulan" class="form-label">Pilih Bulan:</label>
                            <select name="bulan" id="bulan" class="form-select" onchange="this.form.submit()">
                                @foreach ($bulanOptions as $value => $label)
                                <option value="{{ $value }}" {{ $bulan == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Total Jumlah</th>
                            <th>Total Harga</th>
                            <th>Prediksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = ($paginatedData->currentPage() - 1) * $paginatedData->perPage() + 1;
                        @endphp
                        @forelse ($penjualansGroupedByProduk as $key => $data)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $data['produk']->nama_produk }}</td>
                            <td>{{ $data['total_jumlah'] }}</td>
                            <td>{{ $data['produk']->harga * $data['total_jumlah'] }}</td>
                            <td>{{ $data['prediksi'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penjualan di bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-4">
                    <nav>
                        <ul class="pagination justify-content-center">
                            @if ($paginatedData->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-disabled="true">Sebelumnya</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $paginatedData->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                            </li>
                            @endif

                            @foreach ($paginatedData->getUrlRange(1, $paginatedData->lastPage()) as $page => $url)
                            <li class="page-item {{ $paginatedData->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endforeach

                            @if ($paginatedData->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $paginatedData->nextPageUrl() }}" rel="next">Selanjutnya</a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-disabled="true">Selanjutnya</span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>



            </div>
        </div>
    </div>
</div>
@endsection