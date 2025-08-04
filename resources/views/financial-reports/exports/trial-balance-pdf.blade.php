<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .period {
            font-size: 12px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .amount {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .balance-check {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .balance-status {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .balanced {
            color: #28a745;
        }
        .unbalanced {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company_info['name'] ?? 'BUMDES' }}</div>
        <div class="report-title">{{ $title }}</div>
        <div class="period">Per {{ $asOfDate->format('d F Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Kode Akun</th>
                <th style="width: 45%;">Nama Akun</th>
                <th style="width: 20%;" class="amount">Debit (Rp)</th>
                <th style="width: 20%;" class="amount">Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['accounts'] as $account)
            <tr>
                <td>{{ $account['account_code'] ?? $account['kode_akun'] ?? '' }}</td>
                <td>{{ $account['account_name'] ?? $account['nama_akun'] ?? '' }}</td>
                <td class="amount">{{ $account['debit'] > 0 ? number_format($account['debit'], 0, ',', '.') : '-' }}</td>
                <td class="amount">{{ $account['credit'] > 0 ? number_format($account['credit'], 0, ',', '.') : '-' }}</td>
            </tr>
            @endforeach
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td class="amount">{{ number_format($reportData['total_debit'], 0, ',', '.') }}</td>
                <td class="amount">{{ number_format($reportData['total_credit'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Balance Check Section -->
    <div class="balance-check">
        <div class="balance-status {{ $reportData['is_balanced'] ? 'balanced' : 'unbalanced' }}">
            Status Neraca: {{ $reportData['is_balanced'] ? 'SEIMBANG' : 'TIDAK SEIMBANG' }}
        </div>
        
        @if(!$reportData['is_balanced'])
        <div>
            <strong>Selisih:</strong> Rp {{ number_format(abs($reportData['difference']), 0, ',', '.') }}
            @if($reportData['difference'] > 0)
                (Debit lebih besar)
            @else
                (Kredit lebih besar)
            @endif
        </div>
        <div style="margin-top: 10px; font-size: 11px; color: #666;">
            <strong>Catatan:</strong> Neraca saldo harus seimbang (total debit = total kredit). 
            Jika tidak seimbang, periksa kembali pencatatan transaksi dan posting ke buku besar.
        </div>
        @else
        <div style="font-size: 11px; color: #666;">
            Neraca saldo telah seimbang dengan benar.
        </div>
        @endif
    </div>
</body>
</html>