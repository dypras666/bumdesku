@extends('adminlte::page')

@section('title', 'Dashboard ' . company_info('name'))

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Dashboard {{ company_info('name') }}</h1>
            <p class="text-muted">Periode: {{ $stats['period_name'] }}</p>
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
    <!-- Period Selection -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar mr-1"></i>
                        Pilih Periode
                    </h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard') }}" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="month" class="mr-2">Bulan:</label>
                            <select name="month" id="month" class="form-control">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $stats['selected_month'] == $i ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create(null, $i, 1)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mr-3">
                            <label for="year" class="mr-2">Tahun:</label>
                            <select name="year" id="year" class="form-control">
                                @for($year = 2020; $year <= 2030; $year++)
                                    <option value="{{ $year }}" {{ $stats['selected_year'] == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <!-- Baris Pertama: Saldo Awal -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h4>{{ format_currency($stats['initial_cash_balance']) }}</h4>
                    <p>Saldo Awal Kas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h4>{{ format_currency($stats['current_modal_balance']) }}</h4>
                    <p>Modal Awal BUMDES</p>
                    <small class="text-light">Saldo Awal: {{ format_currency($stats['initial_modal_balance']) }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-university"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_transactions'] }}</h3>
                    <p>Transaksi {{ $stats['period_name'] }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Baris Kedua: Transaksi dan Saldo Akhir -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h4>{{ format_currency($stats['total_income']) }}</h4>
                    <p>Pemasukan {{ $stats['period_name'] }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h4>{{ format_currency($stats['total_expenses']) }}</h4>
                    <p>Pengeluaran {{ $stats['period_name'] }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h4>{{ format_currency($stats['final_cash_balance']) }}</h4>
                    <p>Saldo Kas {{ $stats['period_name'] }}</p>
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
                        Informasi 
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
                        <span class="progress-text">Transaksi {{ $stats['period_name'] }}</span>
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
                        Transaksi {{ $stats['period_name'] }}
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
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge {{ $transaction->transaction_type === 'income' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $transaction->getTypeLabel() }}
                                    </span>
                                </td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    <span class="{{ $transaction->transaction_type === 'income' ? 'text-success' : 'text-danger' }} font-weight-bold">
                                        {{ format_currency($transaction->amount) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $transaction->getStatusBadgeClass() }}">
                                        {{ $transaction->getStatusLabel() }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada transaksi</td>
                            </tr>
                            @endforelse
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
        $(function () {
            // Chart keuangan bulanan
            var ctx = document.getElementById('monthlyChart').getContext('2d');
            var monthlyData = @json($monthlyData);
            
            var monthlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyData.labels,
                    datasets: [{
                        label: 'Pemasukan',
                        data: monthlyData.income,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }, {
                        label: 'Pengeluaran',
                        data: monthlyData.expenses,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Grafik Keuangan Bulanan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop