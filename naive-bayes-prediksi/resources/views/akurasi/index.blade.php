@extends('layouts.app')

@section('content')
<style>
  body {
    background: linear-gradient(135deg, #74ebd5, #ACB6E5);
    min-height: 100vh;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
  }

  .akurasi-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
    padding: 20px;
    width: 100%;
    animation: fadeIn .8s ease-in-out
  }

  .akurasi-header {
    font-size: 32px;
    font-weight: 700;
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px
  }

  .btn-primary {
    background: linear-gradient(to right, #74ebd5, #ACB6E5);
    border: none;
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 600;
    color: #fff;
    transition: .3s ease
  }

  .btn-primary:hover {
    background: linear-gradient(to right, #60d9c9, #9fb4e1)
  }

  .btn-outline-primary {
    border-radius: 25px;
    padding: 10px 18px;
    font-weight: 600
  }

  .table {
    border-radius: 10px;
    overflow: hidden
  }

  .table thead {
    background-color: #007bff;
    color: #fff;
    font-weight: 600
  }

  .table th,
  .table td {
    vertical-align: middle;
    text-align: center;
    padding: 12px
  }

  .table-hover tbody tr:hover {
    background: #f7f9fb
  }

  .alert {
    border-radius: 12px
  }

  .badge-soft {
    padding: .35rem .6rem;
    border-radius: 12px;
    font-weight: 600
  }

  .badge-kunci {
    background: rgba(13, 110, 253, .12);
    color: #0d6efd
  }

  .table-success {
    background: #e9f7ef !important
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px)
    }

    to {
      opacity: 1;
      transform: translateY(0)
    }
  }
</style>

<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="akurasi-card">
        <div class="akurasi-header">📊 Evaluasi Akurasi (Confusion Matrix)</div>

        @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="d-flex flex-wrap align-items-end gap-2 mb-3">
          <form id="filterForm" class="d-flex flex-wrap align-items-end gap-2" method="get" action="{{ route('akurasi.index') }}">
            <div><input id="fStart" type="date" name="start" class="form-control" value="{{ request('start') }}" placeholder="Tanggal awal"></div>
            <div><input id="fEnd" type="date" name="end" class="form-control" value="{{ request('end') }}" placeholder="Tanggal akhir"></div>
            <div>
              <select id="fProduk" name="produk" class="form-select">
                <option value="">— Semua Produk —</option>
                @foreach($produkOptions ?? [] as $p)
                <option value="{{ $p->id }}" {{ (string)request('produk') === (string)$p->id ? 'selected' : '' }}>
                  {{ $p->nama_produk }}
                </option>
                @endforeach
              </select>
            </div>
            <div>
              <input id="fLabels" type="text" name="labels" class="form-control"
                value="{{ request('labels') }}" placeholder="Urutan label (mis: Rendah,Tinggi)">
            </div>
            <div>
              <button class="btn btn-primary" title="Tampilkan laporan sesuai filter">Tampilkan Laporan</button>
            </div>
          </form>

          <form id="buildForm" method="post" action="{{ route('akurasi.build') }}">
            @csrf
            <input id="bStart" type="hidden" name="start" value="{{ request('start') }}">
            <input id="bEnd" type="hidden" name="end" value="{{ request('end') }}">
            <input id="bProduk" type="hidden" name="produk" value="{{ request('produk') }}">
            <input id="bLabels" type="hidden" name="labels" value="{{ request('labels') }}">
            <button id="btnBuild" class="btn btn-outline-primary"
              title="Bangun aktual dari penjualans + rules untuk periode ini">
              Hitung Aktual Periode Ini
            </button>
          </form>
        </div>

        @php
        $labels = $labels ?? [];
        $matrix = $matrix ?? [];
        $support = $support ?? [];
        $perClass = $perClass ?? [];
        $macro = $macro ?? ['precision'=>0,'recall'=>0,'f1'=>0];
        $micro = $micro ?? ['precision'=>0,'recall'=>0,'f1'=>0];
        $accuracy = $accuracy ?? 0;
        $total = $total ?? 0;
        @endphp

        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">Ringkasan</h5>
            @if($total > 0)
            <p class="mb-1">Total sampel: <span class="badge-soft badge-kunci">{{ $total }}</span></p>
            <p class="mb-1">Akurasi: <strong>{{ number_format($accuracy*100,2) }}%</strong></p>
            <p class="mb-1">
              Rata-rata per Kelas (Macro) — Ketepatan: <strong>{{ number_format($macro['precision']*100,2) }}%</strong>,
              Terdeteksi: <strong>{{ number_format($macro['recall']*100,2) }}%</strong>,
              Skor Gabungan: <strong>{{ number_format($macro['f1']*100,2) }}%</strong>
            </p>
            <p class="mb-0">
              Rata-rata Keseluruhan (Micro) — Ketepatan: <strong>{{ number_format($micro['precision']*100,2) }}%</strong>,
              Terdeteksi: <strong>{{ number_format($micro['recall']*100,2) }}%</strong>,
              Skor Gabungan: <strong>{{ number_format($micro['f1']*100,2) }}%</strong>
            </p>
            @else
            <p class="text-muted mb-0">
              Belum ada data. Pilih tanggal lalu klik <em>Tampilkan Laporan</em>.
              Jika baru input penjualan/prediksi, klik <em>Hitung Aktual Periode Ini</em> untuk membangun data aktual.
            </p>
            @endif
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-body table-responsive">
            <h5 class="card-title mb-3">Confusion Matrix</h5>
            @if($total > 0 && !empty($labels))
            <table class="table table-bordered table-hover align-middle text-center">
              <thead>
                <tr>
                  <th>Data Nyata \ Prediksi Sistem</th>
                  @foreach($labels as $pred) <th>{{ $pred }}</th> @endforeach
                  <th>Jumlah Data Aktual</th>
                </tr>
              </thead>
              <tbody>
                @foreach($matrix as $i => $row)
                <tr>
                  <th class="text-start">{{ $labels[$i] }}</th>
                  @foreach($row as $j => $val)
                  <td class="{{ $i === $j ? 'table-success' : '' }}">{{ $val }}</td>
                  @endforeach
                  <td class="bg-light">{{ $support[$labels[$i]] ?? 0 }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <p class="text-muted mb-0">Matrix belum tersedia.</p>
            @endif
          </div>
        </div>

        <div class="card">
          <div class="card-body table-responsive">
            <h5 class="card-title mb-3">Metrik Per Kelas</h5>
            @if($total > 0 && !empty($perClass))
            <table class="table table-striped table-hover text-center">
              <thead>
                <tr>
                  <th>Kelas</th>
                  <th>Prediksi Benar<br>(Positif)</th>
                  <th>Prediksi Salah<br>(Positif)</th>
                  <th>Terlewat<br>(Negatif Salah)</th>
                  <th>Benar Negatif</th>
                  <th>Ketepatan Prediksi</th>
                  <th>Tingkat Terdeteksi</th>
                  <th>Skor Gabungan (F1)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($perClass as $label => $m)
                <tr>
                  <td class="text-start">{{ $label }}</td>
                  <td>{{ $m['tp'] }}</td>
                  <td>{{ $m['fp'] }}</td>
                  <td>{{ $m['fn'] }}</td>
                  <td>{{ $m['tn'] }}</td>
                  <td>{{ number_format($m['precision']*100,2) }}%</td>
                  <td>{{ number_format($m['recall']*100,2) }}%</td>
                  <td>{{ number_format($m['f1']*100,2) }}%</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <p class="text-muted mb-0">Belum ada metrik per kelas.</p>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  const $ = (id) => document.getElementById(id);

  function syncBuild() {
    $('bStart').value = $('fStart').value;
    $('bEnd').value = $('fEnd').value;
    $('bProduk').value = $('fProduk').value;
    $('bLabels').value = $('fLabels').value;
  }
  ['fStart', 'fEnd', 'fProduk', 'fLabels'].forEach(id => {
    const el = $(id);
    if (el) el.addEventListener('change', syncBuild);
  });
  const btn = $('btnBuild');
  if (btn) {
    btn.addEventListener('click', function(e) {
      syncBuild();
      if (!$('bStart').value || !$('bEnd').value) {
        e.preventDefault();
        alert('Pilih tanggal awal & akhir dulu.');
        return;
      }
    });
  }
  syncBuild();
</script>
@endsection