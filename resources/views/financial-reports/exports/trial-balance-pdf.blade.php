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
            @php
                $totalDebit = 0;
                $totalKredit = 0;
            @endphp
            
            @foreach($reportData['accounts'] as $account)
            @php
                $totalDebit += $account['debit'];
                $totalKredit += $account['kredit'];
            @endphp
            <tr>
                <td>{{ $account['kode_akun'] }}</td>
                <td>{{ $account['nama_akun'] }}</td>
                <td class="amount">{{ $account['debit'] > 0 ? number_format($account['debit'], 0, ',', '.') : '-' }}</td>
                <td class="amount">{{ $account['kredit'] > 0 ? number_format($account['kredit'], 0, ',', '.') : '-' }}</td>
            </tr>
            @endforeach
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td class="amount">{{ number_format($totalDebit, 0, ',', '.') }}</td>
                <td class="amount">{{ number_format($totalKredit, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>