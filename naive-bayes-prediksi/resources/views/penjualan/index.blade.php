@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .penjualan-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 20px;
        width: 100%;
        animation: fadeIn 0.8s ease-in-out;
    }

    .penjualan-header {
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
            <div class="penjualan-card">
                <div class="penjualan-header">🛒 Daftar Penjualan</div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-4 text-end">
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Penjualan
                    </a>
                </div>

                <div class="mb-4">
                    <form method="GET" action="{{ route('penjualan.index') }}" class="row g-2">
                        <div class="col-md-3">
                            <select name="bulan" class="form-control">
                                <option value="">-- Pilih Bulan --</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="tahun" class="form-control">
                                <option value="">-- Pilih Tahun --</option>
                                @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                
                @if ($penjualans->isEmpty())
                    <div class="alert alert-info">
                        Silakan pilih bulan dan tahun terlebih dahulu untuk menampilkan data penjualan.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Waktu Penjualan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = ($penjualans->currentPage() - 1) * $penjualans->perPage() + 1;
                                @endphp

                                @foreach ($penjualans as $penjualan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $penjualan->produk->nama_produk }}</td>
                                        <td>{{ $penjualan->jumlah }}</td>
                                        <td>Rp{{ number_format($penjualan->total_harga, 2, ',', '.') }}</td>
                                        <td>{{ $penjualan->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus penjualan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $penjualans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
