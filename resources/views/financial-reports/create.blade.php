@extends('adminlte::page')

@section('title', 'Buat Laporan Keuangan')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Buat Laporan Keuangan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Daftar Laporan Keuangan</a></li>
                <li class="breadcrumb-item active">Buat Laporan</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Laporan Keuangan</h3>
                </div>
                <form action="{{ route('financial-reports.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="report_type">Jenis Laporan <span class="text-danger">*</span></label>
                            <select name="report_type" id="report_type" class="form-control @error('report_type') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Laporan</option>
                                <option value="income_statement" {{ old('report_type') == 'income_statement' ? 'selected' : '' }}>
                                    Laporan Laba Rugi
                                </option>
                                <option value="balance_sheet" {{ old('report_type') == 'balance_sheet' ? 'selected' : '' }}>
                                    Neraca
                                </option>
                                <option value="cash_flow" {{ old('report_type') == 'cash_flow' ? 'selected' : '' }}>
                                    Arus Kas
                                </option>
                                <option value="trial_balance" {{ old('report_type') == 'trial_balance' ? 'selected' : '' }}>
                                    Neraca Saldo
                                </option>
                                <option value="general_ledger" {{ old('report_type') == 'general_ledger' ? 'selected' : '' }}>
                                    Buku Besar
                                </option>
                            </select>
                            @error('report_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="report_title">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" name="report_title" id="report_title" 
                                   class="form-control @error('report_title') is-invalid @enderror" 
                                   value="{{ old('report_title') }}" 
                                   placeholder="Masukkan judul laporan..." required>
                            @error('report_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Judul akan otomatis diisi berdasarkan jenis laporan yang dipilih
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_start">Periode Mulai <span class="text-danger">*</span></label>
                                    <input type="date" name="period_start" id="period_start" 
                                           class="form-control @error('period_start') is-invalid @enderror" 
                                           value="{{ old('period_start', date('Y-m-01')) }}" required>
                                    @error('period_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_end">Periode Akhir <span class="text-danger">*</span></label>
                                    <input type="date" name="period_end" id="period_end" 
                                           class="form-control @error('period_end') is-invalid @enderror" 
                                           value="{{ old('period_end', date('Y-m-t')) }}" required>
                                    @error('period_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="account_selection" style="display: none;">
                            <label for="account_id">Pilih Akun</label>
                            <select name="account_id" id="account_id" class="form-control">
                                <option value="">Semua Akun</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->account_code }} - {{ $account->account_name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Khusus untuk laporan buku besar, pilih akun yang ingin ditampilkan
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Catatan tambahan untuk laporan...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>
                                <option value="generated" {{ old('status') == 'generated' ? 'selected' : '' }}>
                                    Generate Langsung
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Pilih "Draft" untuk menyimpan tanpa generate data, atau "Generate Langsung" untuk langsung membuat laporan
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Laporan
                        </button>
                        <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Panduan!</h5>
                        <ul class="mb-0">
                            <li><strong>Kode Laporan:</strong> Akan dibuat otomatis</li>
                            <li><strong>Periode:</strong> Tentukan rentang tanggal laporan</li>
                            <li><strong>Status Draft:</strong> Laporan disimpan tanpa data</li>
                            <li><strong>Generate Langsung:</strong> Laporan dibuat dengan data</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                        <p class="mb-0">
                            Pastikan periode yang dipilih sudah memiliki data transaksi yang diposting ke buku besar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jenis Laporan</h3>
                </div>
                <div class="card-body">
                    <div id="report_description">
                        <p class="text-muted">Pilih jenis laporan untuk melihat deskripsi</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('reports.income-statement') }}" class="btn btn-block btn-outline-primary btn-sm mb-2">
                        <i class="fas fa-chart-line"></i> Laporan Laba Rugi
                    </a>
                    <a href="{{ route('reports.balance-sheet') }}" class="btn btn-block btn-outline-success btn-sm mb-2">
                        <i class="fas fa-balance-scale"></i> Neraca
                    </a>
                    <a href="{{ route('reports.cash-flow') }}" class="btn btn-block btn-outline-info btn-sm mb-2">
                        <i class="fas fa-money-bill-wave"></i> Arus Kas
                    </a>
                    <a href="{{ route('trial-balance') }}" class="btn btn-block btn-outline-warning btn-sm">
                        <i class="fas fa-calculator"></i> Neraca Saldo
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .alert ul {
            padding-left: 20px;
        }
        .alert li {
            margin-bottom: 5px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            const reportDescriptions = {
                'income_statement': {
                    title: 'Laporan Laba Rugi',
                    description: 'Menampilkan pendapatan, beban, dan laba/rugi perusahaan dalam periode tertentu.'
                },
                'balance_sheet': {
                    title: 'Neraca',
                    description: 'Menampilkan posisi keuangan perusahaan meliputi aset, kewajiban, dan ekuitas pada tanggal tertentu.'
                },
                'cash_flow': {
                    title: 'Arus Kas',
                    description: 'Menampilkan arus masuk dan keluar kas dari aktivitas operasi, investasi, dan pendanaan.'
                },
                'trial_balance': {
                    title: 'Neraca Saldo',
                    description: 'Menampilkan saldo debit dan kredit semua akun untuk memastikan keseimbangan pembukuan.'
                },
                'general_ledger': {
                    title: 'Buku Besar',
                    description: 'Menampilkan detail transaksi untuk akun tertentu dalam periode yang dipilih.'
                }
            };

            // Update report title and description when report type changes
            $('#report_type').change(function() {
                const reportType = $(this).val();
                const titleField = $('#report_title');
                const descriptionDiv = $('#report_description');
                const accountSelection = $('#account_selection');

                if (reportType && reportDescriptions[reportType]) {
                    const report = reportDescriptions[reportType];
                    
                    // Auto-fill title if empty
                    if (titleField.val() === '') {
                        titleField.val(report.title + ' - ' + getCurrentPeriodLabel());
                    }
                    
                    // Update description
                    descriptionDiv.html(`
                        <h5>${report.title}</h5>
                        <p class="text-muted">${report.description}</p>
                    `);

                    // Show account selection for general ledger
                    if (reportType === 'general_ledger') {
                        accountSelection.show();
                    } else {
                        accountSelection.hide();
                    }
                } else {
                    descriptionDiv.html('<p class="text-muted">Pilih jenis laporan untuk melihat deskripsi</p>');
                    accountSelection.hide();
                }
            });

            // Update title when period changes
            $('#period_start, #period_end').change(function() {
                const reportType = $('#report_type').val();
                const titleField = $('#report_title');
                
                if (reportType && reportDescriptions[reportType] && titleField.val().includes(' - ')) {
                    const baseTitle = reportDescriptions[reportType].title;
                    titleField.val(baseTitle + ' - ' + getCurrentPeriodLabel());
                }
            });

            function getCurrentPeriodLabel() {
                const startDate = $('#period_start').val();
                const endDate = $('#period_end').val();
                
                if (startDate && endDate) {
                    const start = new Date(startDate).toLocaleDateString('id-ID');
                    const end = new Date(endDate).toLocaleDateString('id-ID');
                    return `${start} s/d ${end}`;
                }
                
                return 'Periode';
            }

            // Trigger change event on page load if report type is already selected
            if ($('#report_type').val()) {
                $('#report_type').trigger('change');
            }
        });
    </script>
@stop