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
        .section-header {
            font-weight: bold;
            background-color: #e0e0e0;
        }
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .net-income {
            font-weight: bold;
            background-color: #d0d0d0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company_info['name'] ?? 'BUMDES' }}</div>
        <div class="report-title">{{ $title }}</div>
        <div class="period">Periode: {{ $periodStart->format('d F Y') }} s/d {{ $periodEnd->format('d F Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 70%;">Keterangan</th>
                <th style="width: 30%;" class="amount">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Revenue Section -->
            <tr class="section-header">
                <td>PENDAPATAN</td>
                <td class="amount"></td>
            </tr>
            @if(isset($reportData['revenues']) && is_iterable($reportData['revenues']))
                @foreach($reportData['revenues'] as $accountName => $amount)
                <tr>
                    <td>{{ $accountName }}</td>
                    <td class="amount">{{ number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
            <tr class="total-row">
                <td>Total Pendapatan</td>
                <td class="amount">{{ number_format(is_numeric($reportData['total_revenue'] ?? 0) ? $reportData['total_revenue'] : 0, 0, ',', '.') }}</td>
            </tr>

            <!-- Expense Section -->
            <tr class="section-header">
                <td>BEBAN</td>
                <td class="amount"></td>
            </tr>
            @if(isset($reportData['expenses']) && is_iterable($reportData['expenses']))
                @foreach($reportData['expenses'] as $accountName => $amount)
                <tr>
                    <td>{{ $accountName }}</td>
                    <td class="amount">{{ number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
            <tr class="total-row">
                <td>Total Beban</td>
                <td class="amount">{{ number_format(is_numeric($reportData['total_expenses'] ?? 0) ? $reportData['total_expenses'] : 0, 0, ',', '.') }}</td>
            </tr>

            <!-- Net Income -->
            <tr class="net-income">
                <td>LABA (RUGI) BERSIH</td>
                <td class="amount">{{ number_format(is_numeric($reportData['net_income'] ?? 0) ? $reportData['net_income'] : 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>