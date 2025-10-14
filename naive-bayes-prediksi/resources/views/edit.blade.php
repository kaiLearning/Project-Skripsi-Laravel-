@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Data Penjualan</h2>

    <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="produk" value="{{ $penjualan->produk }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jumlah Terjual</label>
            <input type="number" name="jumlah_terjual" value="{{ $penjualan->jumlah_terjual }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" value="{{ $penjualan->kategori }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
