<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Tahunan {{ $year }}</title>
    <style>
        @page {
            size: letter;
            margin: 1in;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .cover-page {
            text-align: center;
            padding-top: 2in;
        }
        
        .cover-page img {
            max-height: 1.5in;
            margin-bottom: 0.5in;
        }
        
        .cover-page h1 {
            font-size: 24pt;
            font-weight: bold;
            margin: 0.5in 0;
            text-transform: uppercase;
        }
        
        .cover-page h2 {
            font-size: 20pt;
            margin: 0.3in 0;
            color: #333;
        }
        
        .cover-page h3 {
            font-size: 16pt;
            margin: 0.2in 0;
        }
        
        .cover-page .year-info {
            margin-top: 1in;
        }
        
        .cover-page .year-info h2 {
            font-size: 28pt;
            font-weight: bold;
            color: #000;
        }
        
        h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0.5in 0 0.3in 0;
            text-align: center;
            text-transform: uppercase;
        }
        
        h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0.4in 0 0.2in 0;
            color: #333;
            border-bottom: 2pt solid #333;
            padding-bottom: 0.1in;
        }
        
        h3 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0.3in 0 0.15in 0;
            color: #555;
        }
        
        .toc {
            margin: 0.3in 0;
        }
        
        .toc ol {
            list-style-type: decimal;
            padding-left: 0.5in;
        }
        
        .toc li {
            margin: 0.1in 0;
            line-height: 1.8;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.2in 0;
            font-size: 11pt;
        }
        
        table th,
        table td {
            border: 1pt solid #333;
            padding: 0.1in;
            text-align: left;
            vertical-align: top;
        }
        
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-weight-bold {
            font-weight: bold;
        }
        
        .table-summary {
            background-color: #f8f9fa;
        }
        
        .table-summary td {
            font-weight: bold;
        }
        
        .accountability-content {
            text-align: justify;
            margin: 0.3in 0;
            line-height: 1.8;
        }
        
        .chapter-content {
            text-align: justify;
            margin: 0.3in 0;
        }
        
        .chapter-content p {
            margin: 0.15in 0;
        }
        
        .chapter-content ul,
        .chapter-content ol {
            margin: 0.15in 0;
            padding-left: 0.5in;
        }
        
        .chapter-content li {
            margin: 0.05in 0;
        }
        
        .report-section {
            margin: 0.4in 0;
        }
        
        .signature-section {
            margin-top: 1in;
            text-align: right;
        }
        
        .signature-box {
            display: inline-block;
            text-align: center;
            margin: 0.5in;
        }
        
        .signature-line {
            border-bottom: 1pt solid #000;
            width: 2in;
            margin: 1in auto 0.1in auto;
        }
        
        .footer-info {
            position: fixed;
            bottom: 0.5in;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10pt;
            color: #666;
        }
        
        .page-number {
            position: fixed;
            bottom: 0.3in;
            right: 0.5in;
            font-size: 10pt;
        }
        
        /* Specific styles for financial tables */
        .financial-table {
            font-size: 10pt;
        }
        
        .financial-table th {
            background-color: #e9ecef;
            font-size: 9pt;
        }
        
        .financial-table .account-name {
            width: 60%;
        }
        
        .financial-table .amount {
            width: 20%;
            text-align: right;
        }
        
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .subtotal-row {
            background-color: #f1f3f4;
            font-weight: bold;
        }
        
        /* Table of Contents Styles */
        .toc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.3in;
        }
        
        .toc-table td {
            padding: 0.1in 0;
            vertical-align: bottom;
            border: none;
        }
        
        .toc-title {
            width: 70%;
            font-size: 12pt;
            text-align: left;
        }
        
        .toc-dots {
            width: 25%;
            text-align: center;
            font-size: 10pt;
            color: #666;
            overflow: hidden;
        }
        
        .toc-page {
            width: 5%;
            text-align: right;
            font-size: 12pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="cover-page page-break">
        @if($company_info['logo'])
            <img src="{{ public_path('storage/' . $company_info['logo']) }}" alt="Logo">
        @endif
        
        <h1>{{ $cover_title }}</h1>
        
        <div class="company-info">
            <h3>{{ $company_info['name'] ?? 'Nama Perusahaan' }}</h3>
            <p>{{ $company_info['address'] ?? 'Alamat Perusahaan' }}</p>
            <p>{{ $company_info['phone'] ?? 'No. Telepon' }} | {{ $company_info['email'] ?? 'Email' }}</p>
        </div>
        
        <div class="year-info">
            <h2>{{ $year }}</h2>
        </div>
    </div>

    <!-- Table of Contents -->
    <div class="page-break">
        <h1>Daftar Isi</h1>
        <div class="toc">
            <table class="toc-table">
                <tbody>
                    @php $pageNumber = 3; @endphp
                    @if($accountability_text)
                        <tr>
                            <td class="toc-title">Lembar Pertanggungjawaban</td>
                            <td class="toc-dots">.....................................</td>
                            <td class="toc-page">{{ $pageNumber++ }}</td>
                        </tr>
                    @endif
                    @if($pages)
                        @foreach($pages as $index => $page)
                            @if(!empty($page['title']))
                                <tr>
                                    <td class="toc-title">{{ $page['title'] }}</td>
                                    <td class="toc-dots">.....................................</td>
                                    <td class="toc-page">{{ $pageNumber++ }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    <tr>
                        <td class="toc-title">Laporan Laba Rugi</td>
                        <td class="toc-dots">.....................................</td>
                        <td class="toc-page">{{ $pageNumber++ }}</td>
                    </tr>
                    <tr>
                        <td class="toc-title">Neraca (Balance Sheet)</td>
                        <td class="toc-dots">.....................................</td>
                        <td class="toc-page">{{ $pageNumber++ }}</td>
                    </tr>
                    <tr>
                        <td class="toc-title">Laporan Arus Kas</td>
                        <td class="toc-dots">.....................................</td>
                        <td class="toc-page">{{ $pageNumber++ }}</td>
                    </tr>
                    <tr>
                        <td class="toc-title">Buku Besar (General Ledger)</td>
                        <td class="toc-dots">.....................................</td>
                        <td class="toc-page">{{ $pageNumber++ }}</td>
                    </tr>
                    <tr>
                        <td class="toc-title">Neraca Saldo (Trial Balance)</td>
                        <td class="toc-dots">.....................................</td>
                        <td class="toc-page">{{ $pageNumber++ }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Accountability Sheet -->
    @if($accountability_text)
    <div class="page-break">
        <h1>Lembar Pertanggungjawaban</h1>
        <div class="accountability-content">
            {!! $accountability_text !!}
        </div>
        
        <div class="signature-section">
            <div class="signature-box">
                <p>{{ $company_info['city'] ?? 'Kota' }}, {{ date('d F Y') }}</p>
                <p>Pimpinan {{ $company_info['name'] ?? 'Perusahaan' }}</p>
                <div class="signature-line"></div>
                <p>{{ $company_info['director'] ?? 'Nama Pimpinan' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Custom Pages -->
    @if($pages)
        @foreach($pages as $index => $page)
            @if(!empty($page['title']) || !empty($page['content']))
            <div class="page-break">
                @if(!empty($page['title']))
                    <h1>{{ $page['title'] }}</h1>
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

    <!-- Income Statement -->
    <div class="page-break">
        <h1>Laporan Laba Rugi</h1>
        <h3>Periode: {{ date('d F Y', strtotime($period_start)) }} - {{ date('d F Y', strtotime($period_end)) }}</h3>
        
        <table class="financial-table">
            <thead>
                <tr>
                    <th class="account-name">Keterangan</th>
                    <th class="amount">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="subtotal-row">
                    <td><strong>PENDAPATAN</strong></td>
                    <td></td>
                </tr>
                @if(isset($income_statement['revenue']) && $income_statement['revenue']->count() > 0)
                    @foreach($income_statement['revenue'] as $account => $amount)
                        <tr>
                            <td>{{ $account }}</td>
                            <td class="text-right">{{ format_currency($amount) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Total Pendapatan</strong></td>
                    <td class="text-right"><strong>{{ format_currency($income_statement['total_revenue'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="subtotal-row">
                    <td><strong>BEBAN</strong></td>
                    <td></td>
                </tr>
                @if(isset($income_statement['expenses']) && $income_statement['expenses']->count() > 0)
                    @foreach($income_statement['expenses'] as $account => $amount)
                        <tr>
                            <td>{{ $account }}</td>
                            <td class="text-right">{{ format_currency($amount) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Total Beban</strong></td>
                    <td class="text-right"><strong>{{ format_currency($income_statement['total_expenses'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="table-summary">
                    <td><strong>LABA/RUGI BERSIH</strong></td>
                    <td class="text-right"><strong>{{ format_currency($income_statement['net_income'] ?? 0) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Balance Sheet -->
    <div class="page-break">
        <h1>Neraca (Balance Sheet)</h1>
        <h3>Per {{ date('d F Y', strtotime($as_of_date)) }}</h3>
        
        <table class="financial-table">
            <thead>
                <tr>
                    <th class="account-name">Keterangan</th>
                    <th class="amount">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="subtotal-row">
                    <td><strong>ASET</strong></td>
                    <td></td>
                </tr>
                @if(isset($balance_sheet['assets']) && $balance_sheet['assets']->count() > 0)
                    @foreach($balance_sheet['assets'] as $account => $amount)
                        <tr>
                            <td>{{ $account }}</td>
                            <td class="text-right">{{ format_currency($amount) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Total Aset</strong></td>
                    <td class="text-right"><strong>{{ format_currency($balance_sheet['total_assets'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="subtotal-row">
                    <td><strong>KEWAJIBAN</strong></td>
                    <td></td>
                </tr>
                @if(isset($balance_sheet['liabilities']) && $balance_sheet['liabilities']->count() > 0)
                    @foreach($balance_sheet['liabilities'] as $account => $amount)
                        <tr>
                            <td>{{ $account }}</td>
                            <td class="text-right">{{ format_currency($amount) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Total Kewajiban</strong></td>
                    <td class="text-right"><strong>{{ format_currency($balance_sheet['total_liabilities'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="subtotal-row">
                    <td><strong>EKUITAS</strong></td>
                    <td></td>
                </tr>
                @if(isset($balance_sheet['equity']) && $balance_sheet['equity']->count() > 0)
                    @foreach($balance_sheet['equity'] as $account => $amount)
                        <tr>
                            <td>{{ $account }}</td>
                            <td class="text-right">{{ format_currency($amount) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Total Ekuitas</strong></td>
                    <td class="text-right"><strong>{{ format_currency($balance_sheet['total_equity'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="table-summary">
                    <td><strong>TOTAL KEWAJIBAN + EKUITAS</strong></td>
                    <td class="text-right"><strong>{{ format_currency(($balance_sheet['total_liabilities'] ?? 0) + ($balance_sheet['total_equity'] ?? 0)) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Cash Flow Statement -->
    <div class="page-break">
        <h1>Laporan Arus Kas</h1>
        <h3>Periode: {{ date('d F Y', strtotime($period_start)) }} - {{ date('d F Y', strtotime($period_end)) }}</h3>
        
        <table class="financial-table">
            <thead>
                <tr>
                    <th class="account-name">Keterangan</th>
                    <th class="amount">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Kas Awal Periode</strong></td>
                    <td class="text-right">{{ format_currency($cash_flow['beginning_cash'] ?? 0) }}</td>
                </tr>
                
                <tr class="subtotal-row">
                    <td><strong>ARUS KAS DARI AKTIVITAS OPERASI</strong></td>
                    <td></td>
                </tr>
                @if(isset($cash_flow['operating_activities']) && $cash_flow['operating_activities']->count() > 0)
                    @foreach($cash_flow['operating_activities'] as $activity)
                        <tr>
                            <td>{{ $activity['description'] }}</td>
                            <td class="text-right">{{ format_currency($activity['amount']) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Arus Kas Bersih dari Aktivitas Operasi</strong></td>
                    <td class="text-right"><strong>{{ format_currency($cash_flow['net_operating_cash'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="subtotal-row">
                    <td><strong>ARUS KAS DARI AKTIVITAS INVESTASI</strong></td>
                    <td></td>
                </tr>
                @if(isset($cash_flow['investing_activities']) && $cash_flow['investing_activities']->count() > 0)
                    @foreach($cash_flow['investing_activities'] as $activity)
                        <tr>
                            <td>{{ $activity['description'] }}</td>
                            <td class="text-right">{{ format_currency($activity['amount']) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Arus Kas Bersih dari Aktivitas Investasi</strong></td>
                    <td class="text-right"><strong>{{ format_currency($cash_flow['net_investing_cash'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="subtotal-row">
                    <td><strong>ARUS KAS DARI AKTIVITAS PENDANAAN</strong></td>
                    <td></td>
                </tr>
                @if(isset($cash_flow['financing_activities']) && $cash_flow['financing_activities']->count() > 0)
                    @foreach($cash_flow['financing_activities'] as $activity)
                        <tr>
                            <td>{{ $activity['description'] }}</td>
                            <td class="text-right">{{ format_currency($activity['amount']) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td><strong>Arus Kas Bersih dari Aktivitas Pendanaan</strong></td>
                    <td class="text-right"><strong>{{ format_currency($cash_flow['net_financing_cash'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="table-summary">
                    <td><strong>Kenaikan/Penurunan Kas Bersih</strong></td>
                    <td class="text-right"><strong>{{ format_currency($cash_flow['net_cash_change'] ?? 0) }}</strong></td>
                </tr>
                
                <tr class="table-summary">
                    <td><strong>Kas Akhir Periode</strong></td>
                    <td class="text-right"><strong>{{ format_currency($cash_flow['ending_cash'] ?? 0) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Trial Balance -->
    <div class="page-break">
        <h1>Neraca Saldo (Trial Balance)</h1>
        <h3>Per {{ date('d F Y', strtotime($as_of_date)) }}</h3>
        
        <table class="financial-table">
            <thead>
                <tr>
                    <th>Kode Akun</th>
                    <th class="account-name">Nama Akun</th>
                    <th class="amount">Debit (Rp)</th>
                    <th class="amount">Kredit (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                @endphp
                @if(isset($trial_balance) && $trial_balance->count() > 0)
                    @foreach($trial_balance as $account)
                        @php
                            $totalDebit += $account['debit'];
                            $totalCredit += $account['credit'];
                        @endphp
                        <tr>
                            <td>{{ $account['account_code'] }}</td>
                            <td>{{ $account['account_name'] }}</td>
                            <td class="text-right">{{ $account['debit'] > 0 ? format_currency($account['debit']) : '-' }}</td>
                            <td class="text-right">{{ $account['credit'] > 0 ? format_currency($account['credit']) : '-' }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td colspan="2"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ format_currency($totalDebit) }}</strong></td>
                    <td class="text-right"><strong>{{ format_currency($totalCredit) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- General Ledger Summary -->
    <div class="page-break">
        <h1>Ringkasan Buku Besar</h1>
        <h3>Periode: {{ date('d F Y', strtotime($period_start)) }} - {{ date('d F Y', strtotime($period_end)) }}</h3>
        
        @if(isset($general_ledger) && $general_ledger->count() > 0)
            @foreach($general_ledger as $accountName => $entries)
                <div class="report-section">
                    <h3>{{ $accountName }}</h3>
                    <table class="financial-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="amount">Debit</th>
                                <th class="amount">Kredit</th>
                                <th class="amount">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $runningBalance = 0; @endphp
                            @foreach($entries as $entry)
                                @php
                                    $runningBalance += $entry->debit - $entry->credit;
                                @endphp
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($entry->posting_date)) }}</td>
                                    <td>{{ $entry->description }}</td>
                                    <td class="text-right">{{ $entry->debit > 0 ? format_currency($entry->debit) : '-' }}</td>
                                    <td class="text-right">{{ $entry->credit > 0 ? format_currency($entry->credit) : '-' }}</td>
                                    <td class="text-right">{{ format_currency($runningBalance) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Footer -->
    <div class="footer-info">
        Laporan ini dibuat pada {{ $generated_at->format('d F Y H:i:s') }} oleh {{ $generated_by->name ?? 'System' }}
    </div>
</body>
</html>