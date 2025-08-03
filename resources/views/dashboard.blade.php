@extends('adminlte::page')

@section('title', 'Dashboard ' . company_info('name'))

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Dashboard {{ company_info('name') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                </li>
                <li class="breadcrumb-item active">
                    <span class="badge badge-primary">{{ Auth::user()->getRoleName() }}</span>
                </li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_transactions'] }}</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ format_currency($stats['total_income']) }}</h3>
                    <p>Total Pemasukan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ format_currency($stats['total_expenses']) }}</h3>
                    <p>Total Pengeluaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ format_currency($stats['cash_balance']) }}</h3>
                    <p>Saldo Kas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Keuangan Bulanan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="monthlyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Perusahaan
                    </h3>
                </div>
                <div class="card-body">
                    <x-company-info 
                        :show-logo="true" 
                        :show-address="true" 
                        :show-contact="true" 
                        size="md" 
                    />
                    <hr>
                    <div class="progress-group">
                        <span class="progress-text">Transaksi Bulan Ini</span>
                        <span class="float-right"><b>{{ $stats['total_transactions'] }}</b>/100</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: {{ min(($stats['total_transactions'] / 100) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        Transaksi Terbaru
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-01-15</td>
                                <td><span class="badge badge-success">Pemasukan</span></td>
                                <td>Penjualan produk desa</td>
                                <td><span class="text-success font-weight-bold">{{ format_currency(2500000) }}</span></td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-14</td>
                                <td><span class="badge badge-danger">Pengeluaran</span></td>
                                <td>Pembelian bahan baku</td>
                                <td><span class="text-danger font-weight-bold">{{ format_currency(1200000) }}</span></td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-13</td>
                                <td><span class="badge badge-success">Pemasukan</span></td>
                                <td>Jasa konsultasi</td>
                                <td><span class="text-success font-weight-bold">{{ format_currency(800000) }}</span></td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-12</td>
                                <td><span class="badge badge-danger">Pengeluaran</span></td>
                                <td>Biaya operasional</td>
                                <td><span class="text-danger font-weight-bold">{{ format_currency(500000) }}</span></td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .icon {
            top: 10px;
        }
        .progress-group {
            margin-bottom: 15px;
        }
    </style>
@stop

@section('js')
    <script>
        // Chart configuration for AdminLTE
        $(function () {
            var ctx = document.getElementById('monthlyChart').getContext('2d');
            var monthlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Pemasukan',
                        data: [12000000, 19000000, 15000000, 25000000, 22000000, 30000000],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Pengeluaran',
                        data: [8000000, 12000000, 10000000, 18000000, 15000000, 20000000],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    </script>
@stop