@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .edit-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 30px;
        animation: fadeIn 0.6s ease-in-out;
    }

    .edit-header {
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
            <div class="edit-card">
                <div class="edit-header">✏️ Edit Rule Penilaian</div>

                <form action="{{ route('rule.update', $rule->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="kategori_produk" class="form-label">Kategori Produk</label>
                        <select name="kategori_produk" class="form-control" required>
                            <option value="">-- Pilih Kategori Produk --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ $rule->kategori_produk == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="min_jumlah" class="form-label">Jumlah Minimal Terjual</label>
                        <input type="number" name="min_jumlah" class="form-control" value="{{ old('min_jumlah', $rule->min_jumlah) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="min_harga" class="form-label">Harga Minimal Penghasilan</label>
                        <input type="number" name="min_harga" class="form-control" value="{{ old('min_harga', $rule->min_harga) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategori_prediksi" class="form-label">Kategori Prediksi</label>
                        <select name="kategori_prediksi" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Tinggi" {{ $rule->kategori_prediksi === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Rendah" {{ $rule->kategori_prediksi === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="{{ route('rule.index') }}" class="btn btn-secondary ms-3">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
