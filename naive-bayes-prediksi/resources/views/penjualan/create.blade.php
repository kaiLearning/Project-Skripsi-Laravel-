@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .create-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 30px;
        animation: fadeIn 0.8s ease-in-out;
    }

    .create-header {
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

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="create-card">
                <div class="create-header">➕ Tambah Penjualan</div>

                <form action="{{ route('penjualan.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Produk</label>
                        <select class="form-control" id="produk_id" name="produk_id" required>
                            <option value="">Pilih Produk</option>
                            @foreach ($masterProduk as $produk)
                                <option value="{{ $produk->id }}" 
                                        data-harga="{{ $produk->harga }}" 
                                        data-stok="{{ $produk->stok }}">
                                    {{ $produk->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required min="1" oninput="updateTotalHarga()">
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga per Produk</label>
                        <input type="text" class="form-control" id="harga" name="harga" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                    </div>

                    <div class="mb-4">
                        <label for="stok" class="form-label">Stok Tersedia</label>
                        <input type="text" class="form-control" id="stok" name="stok" readonly>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Penjualan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('produk_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var harga = selectedOption.getAttribute('data-harga');
        var stok = selectedOption.getAttribute('data-stok');
        
        document.getElementById('harga').value = harga;
        document.getElementById('stok').value = stok;
        updateTotalHarga();
    });

    function updateTotalHarga() {
        var jumlah = document.getElementById('jumlah').value;
        var harga = document.getElementById('harga').value;
        var totalHarga = jumlah * harga;
        document.getElementById('total_harga').value = totalHarga;
    }
</script>
@endsection
