@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .rule-card {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 10px 30px;
        animation: fadeIn 0.8s ease-in-out;
    }

    .rule-header {
        font-size: 32px;
        font-weight: 700;
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
    }

    .form-control {
        border-radius: 10px;
        transition: 0.3s ease;
    }

    .form-control:focus {
        border-color: #74ebd5;
        box-shadow: 0 0 0 0.2rem rgba(116, 235, 213, 0.25);
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
    .btn-danger {
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 14px;
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

    .alert {
        border-radius: 12px;
    }

    .pagination {
        justify-content: center;
    }

    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 30px;
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
            <div class="rule-card">
                <div class="rule-header">📋 Daftar Rule</div>

                <div class="mb-4 text-end">
                    <a href="{{ route('rule.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Rule
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('rule.index') }}" method="GET" class="search-form">
                        <input type="text" name="search" class="form-control" placeholder="🔍 Cari berdasarkan kategori, jumlah, atau harga" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori Produk</th>
                                    <th>Jumlah Minimal Terjual</th>
                                    <th>Harga Minimal Penghasilan</th>
                                    <th>Kategori Prediksi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = ($rules->currentPage() - 1) * $rules->perPage() + 1;
                                @endphp
                                @forelse ($rules as $rule)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $rule->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $rule->min_jumlah }}</td>
                                    <td>Rp{{ number_format($rule->min_harga, 0, ',', '.') }}</td>
                                    <td>{{ $rule->kategori_prediksi }}</td>
                                    <td>
                                        <a href="{{ route('rule.edit', $rule->id) }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('rule.destroy', $rule->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus rule ini?')">
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
                                    <td colspan="6" class="text-center text-muted">Tidak ada data rule.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <nav>
                            <ul class="pagination justify-content-center">
                                @if ($rules->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-disabled="true">Sebelumnya</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $rules->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                                </li>
                                @endif

                                @foreach ($rules->getUrlRange(1, $rules->lastPage()) as $page => $url)
                                <li class="page-item {{ $rules->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                                @endforeach
                                @if ($rules->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $rules->nextPageUrl() }}" rel="next">Selanjutnya</a>
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