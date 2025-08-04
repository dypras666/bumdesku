@extends('adminlte::page')

@section('title', 'Preview Laporan Keuangan Tahunan ' . $year)

@section('content_header')
    <h1>Preview Laporan Keuangan Tahunan {{ $year }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Preview Laporan Keuangan Tahunan {{ $year }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('financial-reports.annual') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <form action="{{ route('financial-reports.annual.generate') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="hidden" name="cover_title" value="{{ $cover_title }}">
                            <input type="hidden" name="accountability_text" value="{{ $accountability_text }}">
                            @if($pages)
                                @foreach($pages as $index => $page)
                                    <input type="hidden" name="pages[{{ $index }}][title]" value="{{ $page['title'] ?? '' }}">
                                    <input type="hidden" name="pages[{{ $index }}][content]" value="{{ $page['content'] ?? '' }}">
                                    <input type="hidden" name="pages[{{ $index }}][type]" value="{{ $page['type'] ?? 'content' }}">
                                    <input type="hidden" name="pages[{{ $index }}][show_in_toc]" value="{{ $page['show_in_toc'] ?? '1' }}">
                                    <input type="hidden" name="pages[{{ $index }}][new_page]" value="{{ $page['new_page'] ?? '1' }}">
                                @endforeach
                            @endif
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="preview-container" style="background: white; padding: 20px; border: 1px solid #ddd;">
                        
                        <!-- Cover Page Preview -->
                        <div class="cover-page text-center mb-5" style="page-break-after: always;">
                            @if($company_info['logo'])
                                <img src="{{ asset('storage/' . $company_info['logo']) }}" 
                                     alt="Logo" style="max-height: 100px; margin-bottom: 20px;">
                            @endif
                            
                            <h1 class="mb-4">{{ $cover_title }}</h1>
                            
                            <div class="company-info mb-4">
                                <h3>{{ $company_info['name'] ?? 'Nama Perusahaan' }}</h3>
                                <p>{{ $company_info['address'] ?? 'Alamat Perusahaan' }}</p>
                                <p>{{ $company_info['phone'] ?? 'No. Telepon' }} | {{ $company_info['email'] ?? 'Email' }}</p>
                            </div>
                            
                            <div class="year-info">
                                <h2>Tahun {{ $year }}</h2>
                            </div>
                        </div>

                        <!-- Table of Contents Preview -->
                        <div class="table-of-contents mb-5" style="page-break-after: always;">
                            <h2 class="mb-4">Daftar Isi</h2>
                            <ol>
                                <li>Lembar Pertanggungjawaban</li>
                                @if($pages)
                                    @foreach($pages as $index => $page)
                                        @if(!empty($page['title']))
                                            <li>{{ $page['title'] }}</li>
                                        @endif
                                    @endforeach
                                @endif
                                <li>Laporan Laba Rugi</li>
                                <li>Neraca (Balance Sheet)</li>
                                <li>Laporan Arus Kas</li>
                                <li>Buku Besar (General Ledger)</li>
                                <li>Neraca Saldo (Trial Balance)</li>
                            </ol>
                        </div>

                        <!-- Accountability Sheet Preview -->
                        @if($accountability_text)
                        <div class="accountability-sheet mb-5" style="page-break-after: always;">
                            <h2 class="mb-4">Lembar Pertanggungjawaban</h2>
                            <div class="accountability-content">
                                {!! nl2br(e($accountability_text)) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Custom Pages Preview -->
                        @if($pages)
                            @foreach($pages as $index => $page)
                                @if(!empty($page['title']) || !empty($page['content']))
                                <div class="custom-page mb-5" style="page-break-after: always;">
                                    @if(!empty($page['title']))
                                        <h2 class="mb-4">{{ $page['title'] }}</h2>
                                    @endif
                                    @if(!empty($page['content']))
                                        <div class="page-content">
                                            {!! $page['content'] !!}
                                        </div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        @endif

                        <!-- Financial Reports Summary -->
                        <div class="reports-summary mb-5">
                            <h2 class="mb-4">Ringkasan Laporan Keuangan</h2>
                            
                            <!-- Income Statement Summary -->
                            <div class="report-section mb-4">
                                <h3>Laporan Laba Rugi</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>Total Pendapatan</strong></td>
                                            <td class="text-right">{{ format_currency($income_statement['total_revenue'] ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Beban</strong></td>
                                            <td class="text-right">{{ format_currency($income_statement['total_expenses'] ?? 0) }}</td>
                                        </tr>
                                        <tr class="table-info">
                                            <td><strong>Laba/Rugi Bersih</strong></td>
                                            <td class="text-right"><strong>{{ format_currency($income_statement['net_income'] ?? 0) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Balance Sheet Summary -->
                            <div class="report-section mb-4">
                                <h3>Neraca</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>Total Aset</strong></td>
                                            <td class="text-right">{{ format_currency($balance_sheet['total_assets'] ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Kewajiban</strong></td>
                                            <td class="text-right">{{ format_currency($balance_sheet['total_liabilities'] ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Ekuitas</strong></td>
                                            <td class="text-right">{{ format_currency($balance_sheet['total_equity'] ?? 0) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Cash Flow Summary -->
                            <div class="report-section mb-4">
                                <h3>Laporan Arus Kas</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>Kas Awal Periode</strong></td>
                                            <td class="text-right">{{ format_currency($cash_flow['beginning_cash'] ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Arus Kas Operasi</strong></td>
                                            <td class="text-right">{{ format_currency($cash_flow['net_operating_cash'] ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Arus Kas Investasi</strong></td>
                                            <td class="text-right">{{ format_currency($cash_flow['net_investing_cash'] ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Arus Kas Pendanaan</strong></td>
                                            <td class="text-right">{{ format_currency($cash_flow['net_financing_cash'] ?? 0) }}</td>
                                        </tr>
                                        <tr class="table-info">
                                            <td><strong>Kas Akhir Periode</strong></td>
                                            <td class="text-right"><strong>{{ format_currency($cash_flow['ending_cash'] ?? 0) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Generation Info -->
                        <div class="generation-info text-center mt-5 pt-4 border-top">
                            <small class="text-muted">
                                Laporan ini dibuat pada {{ $generated_at->format('d F Y H:i:s') }} 
                                oleh {{ $generated_by->name ?? 'System' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.preview-container {
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
}

.cover-page h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 2rem;
}

.cover-page h2 {
    font-size: 2rem;
    color: #007bff;
}

.cover-page h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.table-of-contents ol {
    font-size: 1.1rem;
    line-height: 2;
}

.report-section h3 {
    color: #007bff;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

@media print {
    .card-header, .card-footer {
        display: none !important;
    }
    
    .preview-container {
        border: none !important;
        padding: 0 !important;
    }
}
</style>
@endpush