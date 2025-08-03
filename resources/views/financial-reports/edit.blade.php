@extends('adminlte::page')

@section('title', 'Edit Laporan Keuangan')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit Laporan Keuangan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Daftar Laporan Keuangan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('financial-reports.show', $report) }}">Detail Laporan</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Laporan Keuangan</h3>
                    <div class="card-tools">
                        <span class="badge {{ $report->getStatusBadgeClass() }} badge-lg">
                            {{ $report->getStatusLabel() }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('financial-reports.update', $report) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($report->status === 'finalized')
                            <div class="alert alert-warning">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                                Laporan ini sudah difinalisasi. Hanya beberapa field yang dapat diubah.
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="report_code">Kode Laporan</label>
                            <input type="text" id="report_code" class="form-control" 
                                   value="{{ $report->report_code }}" readonly>
                            <small class="form-text text-muted">Kode laporan tidak dapat diubah</small>
                        </div>

                        <div class="form-group">
                            <label for="report_type">Jenis Laporan <span class="text-danger">*</span></label>
                            <select name="report_type" id="report_type" 
                                    class="form-control @error('report_type') is-invalid @enderror" 
                                    {{ $report->status === 'finalized' ? 'disabled' : '' }} required>
                                <option value="">Pilih Jenis Laporan</option>
                                <option value="income_statement" {{ old('report_type', $report->report_type) == 'income_statement' ? 'selected' : '' }}>
                                    Laporan Laba Rugi
                                </option>
                                <option value="balance_sheet" {{ old('report_type', $report->report_type) == 'balance_sheet' ? 'selected' : '' }}>
                                    Neraca
                                </option>
                                <option value="cash_flow" {{ old('report_type', $report->report_type) == 'cash_flow' ? 'selected' : '' }}>
                                    Arus Kas
                                </option>
                                <option value="trial_balance" {{ old('report_type', $report->report_type) == 'trial_balance' ? 'selected' : '' }}>
                                    Neraca Saldo
                                </option>
                                <option value="general_ledger" {{ old('report_type', $report->report_type) == 'general_ledger' ? 'selected' : '' }}>
                                    Buku Besar
                                </option>
                            </select>
                            @if($report->status === 'finalized')
                                <input type="hidden" name="report_type" value="{{ $report->report_type }}">
                            @endif
                            @error('report_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="report_title">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" name="report_title" id="report_title" 
                                   class="form-control @error('report_title') is-invalid @enderror" 
                                   value="{{ old('report_title', $report->report_title) }}" 
                                   placeholder="Masukkan judul laporan..." required>
                            @error('report_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_start">Periode Mulai <span class="text-danger">*</span></label>
                                    <input type="date" name="period_start" id="period_start" 
                                           class="form-control @error('period_start') is-invalid @enderror" 
                                           value="{{ old('period_start', $report->period_start->format('Y-m-d')) }}" 
                                           {{ $report->status === 'finalized' ? 'readonly' : '' }} required>
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
                                           value="{{ old('period_end', $report->period_end->format('Y-m-d')) }}" 
                                           {{ $report->status === 'finalized' ? 'readonly' : '' }} required>
                                    @error('period_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $report->report_type === 'general_ledger' ? '' : 'd-none' }}" id="account_selection">
                            <label for="account_id">Pilih Akun</label>
                            <select name="account_id" id="account_id" class="form-control" 
                                    {{ $report->status === 'finalized' ? 'disabled' : '' }}>
                                <option value="">Semua Akun</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" 
                                            {{ old('account_id', $report->report_parameters['account_id'] ?? '') == $account->id ? 'selected' : '' }}>
                                        {{ $account->account_code }} - {{ $account->account_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($report->status === 'finalized')
                                <input type="hidden" name="account_id" value="{{ $report->report_parameters['account_id'] ?? '' }}">
                            @endif
                            <small class="form-text text-muted">
                                Khusus untuk laporan buku besar, pilih akun yang ingin ditampilkan
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Catatan tambahan untuk laporan...">{{ old('notes', $report->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($report->status !== 'finalized')
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status', $report->status) == 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>
                                    <option value="generated" {{ old('status', $report->status) == 'generated' ? 'selected' : '' }}>
                                        Generate Data
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Ubah ke "Generate Data" untuk memperbarui data laporan
                                </small>
                            </div>
                        @else
                            <input type="hidden" name="status" value="{{ $report->status }}">
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('financial-reports.show', $report) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        
                        @if($report->status !== 'finalized')
                            <button type="submit" name="regenerate" value="1" class="btn btn-success">
                                <i class="fas fa-sync"></i> Simpan & Regenerate
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Laporan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Kode:</strong></td>
                            <td>{{ $report->report_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $report->getStatusBadgeClass() }}">
                                    {{ $report->getStatusLabel() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diperbarui:</strong></td>
                            <td>{{ $report->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($report->generated_at)
                            <tr>
                                <td><strong>Generate:</strong></td>
                                <td>{{ $report->generated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endif
                        @if($report->finalized_at)
                            <tr>
                                <td><strong>Final:</strong></td>
                                <td>{{ $report->finalized_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Panduan Edit</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Tips!</h5>
                        <ul class="mb-0">
                            <li><strong>Draft:</strong> Dapat diubah semua field</li>
                            <li><strong>Generated:</strong> Dapat diubah dan di-regenerate</li>
                            <li><strong>Finalized:</strong> Hanya catatan yang dapat diubah</li>
                        </ul>
                    </div>
                    
                    @if($report->status === 'finalized')
                        <div class="alert alert-warning">
                            <h5><i class="icon fas fa-lock"></i> Terkunci!</h5>
                            <p class="mb-0">
                                Laporan yang sudah final tidak dapat diubah strukturnya untuk menjaga integritas data.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Lainnya</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('financial-reports.show', $report) }}" class="btn btn-block btn-info btn-sm mb-2">
                        <i class="fas fa-eye"></i> Lihat Laporan
                    </a>
                    
                    @if($report->status === 'generated')
                        <form action="{{ route('reports.finalize', $report) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-block btn-success btn-sm" 
                                    onclick="return confirm('Yakin ingin memfinalisasi laporan ini?')">
                                <i class="fas fa-check"></i> Finalisasi
                            </button>
                        </form>
                    @endif
                    
                    @if($report->status !== 'finalized')
                        <form action="{{ route('financial-reports.destroy', $report) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-block btn-danger btn-sm" 
                                    onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jenis Laporan</h3>
                </div>
                <div class="card-body">
                    <div id="report_description">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .table-borderless td {
            border: none;
            padding: 0.25rem 0.5rem;
        }
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

            // Update description when report type changes
            $('#report_type').change(function() {
                const reportType = $(this).val();
                const descriptionDiv = $('#report_description');
                const accountSelection = $('#account_selection');

                if (reportType && reportDescriptions[reportType]) {
                    const report = reportDescriptions[reportType];
                    
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

            // Trigger change event on page load
            $('#report_type').trigger('change');

            // Confirm status change to generated
            $('#status').change(function() {
                if ($(this).val() === 'generated' && '{{ $report->status }}' === 'draft') {
                    if (!confirm('Mengubah status ke "Generate Data" akan membuat ulang data laporan. Lanjutkan?')) {
                        $(this).val('draft');
                    }
                }
            });
        });
    </script>
@stop