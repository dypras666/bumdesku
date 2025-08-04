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
        .subsection-header {
            font-weight: bold;
            background-color: #f0f0f0;
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
                <th style="width: 70%;">Keterangan</th>
                <th style="width: 30%;" class="amount">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Assets Section -->
            <tr class="section-header">
                <td>ASET</td>
                <td class="amount"></td>
            </tr>
            
            <!-- Assets -->
            @if(isset($reportData['assets']) && $reportData['assets']->count() > 0)
                @foreach($reportData['assets'] as $accountName => $amount)
                <tr>
                    <td>{{ $accountName }}</td>
                    <td class="amount">{{ number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
            
            <!-- Total Assets -->
            <tr class="total-row">
                <td>TOTAL ASET</td>
                <td class="amount">{{ number_format(is_numeric($reportData['total_assets'] ?? 0) ? $reportData['total_assets'] : 0, 0, ',', '.') }}</td>
            </tr>

            <!-- Liabilities Section -->
            <tr class="section-header">
                <td>KEWAJIBAN</td>
                <td class="amount"></td>
            </tr>
            @if(isset($reportData['liabilities']) && $reportData['liabilities']->count() > 0)
                @foreach($reportData['liabilities'] as $accountName => $amount)
                <tr>
                    <td>{{ $accountName }}</td>
                    <td class="amount">{{ number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
            <tr class="total-row">
                <td>Total Kewajiban</td>
                <td class="amount">{{ number_format(is_numeric($reportData['total_liabilities'] ?? 0) ? $reportData['total_liabilities'] : 0, 0, ',', '.') }}</td>
            </tr>

            <!-- Equity Section -->
            <tr class="section-header">
                <td>EKUITAS</td>
                <td class="amount"></td>
            </tr>
            @if(isset($reportData['equity']) && $reportData['equity']->count() > 0)
                @foreach($reportData['equity'] as $accountName => $amount)
                <tr>
                    <td>{{ $accountName }}</td>
                    <td class="amount">{{ number_format(is_numeric($amount) ? $amount : 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
            <tr class="total-row">
                <td>Total Ekuitas</td>
                <td class="amount">{{ number_format(is_numeric($reportData['total_equity'] ?? 0) ? $reportData['total_equity'] : 0, 0, ',', '.') }}</td>
            </tr>

            <!-- Total Liabilities and Equity -->
            <tr class="total-row">
                <td>TOTAL KEWAJIBAN DAN EKUITAS</td>
                <td class="amount">{{ number_format(is_numeric($reportData['total_liabilities'] ?? 0) && is_numeric($reportData['total_equity'] ?? 0) ? ($reportData['total_liabilities'] + $reportData['total_equity']) : 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>