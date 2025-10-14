@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .produk-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 10px 30px;
        animation: fadeIn 0.8s ease-in-out;
    }

    .produk-header {
        font-size: 32px;
        font-weight: 700;
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
    }

    .btn-primary {
        background: linear-gradient(to right, #74ebd5, #ACB6E5);
        border: none;
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: 600;
        color: #fff;
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #60d9c9, #9fb4e1);
    }

    .btn-warning,
    .btn-danger,
    .btn-success {
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 14px;
    }

    .alert {
        border-radius: 12px;
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background-color: #007bff;
        color: white;
        font-weight: 600;
    }

    .table th,
    .table td {
        vertical-align: middle;
        text-align: center;
        padding: 12px;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table .out-of-stock {
        background-color: #f8d7da;
        color: #721c24;
    }

    .pagination {
        justify-content: center;
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

<div class="container-fluid mt-2 mb-2">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="produk-card">
                <div class="produk-header">🛒 Master Produk</div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card-body">
                    <div class="mb-4 text-end">
                        <a href="{{ route('master-produk.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Produk Baru
                        </a>
                    </div>

                    <form action="{{ route('master-produk.index') }}" method="GET" class="mb-4">
                        <div class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="🔍 Cari produk..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="bi bi-search"></i> 
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = ($masterProduk->currentPage() - 1) * $masterProduk->perPage() + 1;
                                @endphp
                                @forelse ($masterProduk as $produk)
                                <tr class="{{ $produk->stok == 0 ? 'out-of-stock' : '' }}">
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ optional($produk->kategori)->nama_kategori ?? '-' }}</td>
                                    <td>Rp{{ number_format($produk->harga, 0, ',', '.') }}</td>
                                    <td>{{ $produk->stok }}</td>
                                    <td>
                                        <a href="{{ route('master-produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> 
                                        </a>
                                        <form action="{{ route('master-produk.destroy', $produk->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> 
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data produk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <nav>
                            <ul class="pagination justify-content-center">
                                @if ($masterProduk->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-disabled="true">Sebelumnya</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $masterProduk->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                                </li>
                                @endif

                                @foreach ($masterProduk->getUrlRange(1, $masterProduk->lastPage()) as $page => $url)
                                <li class="page-item {{ $masterProduk->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                                @endforeach

                                @if ($masterProduk->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $masterProduk->nextPageUrl() }}" rel="next">Selanjutnya</a>
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
</div>
@endsection
