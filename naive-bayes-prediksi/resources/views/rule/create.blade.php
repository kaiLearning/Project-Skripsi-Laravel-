@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .add-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 30px;
        animation: fadeIn 0.6s ease-in-out;
    }

    .add-header {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
    }

    .form-control {
        border-radius: 15px;
        padding: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-success {
        background: linear-gradient(to right, #74ebd5, #ACB6E5);
        border: none;
        border-radius: 25px;
        font-weight: bold;
        padding: 10px 24px;
        transition: 0.3s ease;
    }

    .btn-success:hover {
        background: linear-gradient(to right, #60d9c9, #9fb4e1);
    }

    .btn-secondary {
        border-radius: 25px;
        padding: 10px 24px;
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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="add-card">
                <div class="add-header">➕ Tambah Rule Penilaian</div>

                <form action="{{ route('rule.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="kategori_produk" class="form-label">Kategori Produk</label>
                        <select name="kategori_produk" class="form-control" required>
                            <option value="">-- Pilih Kategori Produk --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="min_jumlah" class="form-label">Jumlah Minimal Terjual</label>
                        <input type="number" name="min_jumlah" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="min_harga" class="form-label">Harga Minimal Penghasilan</label>
                        <input type="number" name="min_harga" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategori_prediksi" class="form-label">Kategori Prediksi</label>
                        <select name="kategori_prediksi" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Rendah">Rendah</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="{{ route('rule.index') }}" class="btn btn-secondary ms-3">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
