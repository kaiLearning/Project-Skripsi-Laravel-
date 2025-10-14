@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .kategori-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 10px 30px;
        animation: fadeIn 0.8s ease-in-out;
    }

    .kategori-header {
        font-size: 30px;
        font-weight: bold;
        color: #333;
        text-align: center;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 30px;
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
        font-weight: bold;
    }

    .table td,
    .table th {
        text-align: center;
        vertical-align: middle;
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
            <div class="kategori-card">
                <div class="kategori-header">📂 Daftar Kategori Produk</div>

                <div class="mb-4 text-end">
                    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('kategori.index') }}" method="GET" class="search-form">
                        <input type="text" name="search" class="form-control" placeholder="🔍 Cari nama kategori..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = ($kategoris->currentPage() - 1) * $kategoris->perPage() + 1;
                                @endphp

                                @forelse ($kategoris as $kategori)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $kategori->nama_kategori }}</td>
                                    <td>
                                        <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm" title="Edit Kategori">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Hapus Kategori">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">Belum ada kategori tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <nav>
                            <ul class="pagination justify-content-center">
                                @if ($kategoris->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-disabled="true">Sebelumnya</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $kategoris->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                                </li>
                                @endif

                                @foreach ($kategoris->getUrlRange(1, $kategoris->lastPage()) as $page => $url)
                                <li class="page-item {{ $kategoris->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                                @endforeach

                                @if ($kategoris->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $kategoris->nextPageUrl() }}" rel="next">Selanjutnya</a>
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