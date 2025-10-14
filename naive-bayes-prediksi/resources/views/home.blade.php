@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 40px 30px;
        animation: fadeIn 0.8s ease-in-out;
    }

    .dashboard-header {
        font-size: 32px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    .form-select {
        border-radius: 12px;
    }

    .form-select:focus {
        border-color: #74ebd5;
        box-shadow: 0 0 0 0.2rem rgba(116, 235, 213, 0.25);
    }

    canvas {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
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

<div class="container-fluid mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="dashboard-card">
                <div class="dashboard-header">📊 Grafik Penjualan Toko Sembako Kaka</div>

                <form id="filterForm" method="GET" action="{{ route('home') }}" class="mb-4">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="bulan" class="form-label fw-semibold">Pilih Bulan</label>
                            <select id="bulan" name="bulan" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Pilih Bulan --</option>
                                @foreach($bulanOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $value == $selectedBulan ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <canvas id="penjualanChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('penjualanChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Jumlah Penjualan',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Tren Penjualan - {{ $bulanOptions[$selectedBulan] ?? "Semua Bulan" }}',
                    font: {
                        size: 18
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Terjual',
                        font: {
                            size: 14
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: '{{ $bulanOptions[$selectedBulan] ?? "Bulan" }}',
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
