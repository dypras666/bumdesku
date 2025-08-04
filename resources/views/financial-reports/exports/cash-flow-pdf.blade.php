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
        .net-cash {
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
            <!-- Operating Activities -->
            <tr class="section-header">
                <td>ARUS KAS DARI AKTIVITAS OPERASI</td>
                <td class="amount"></td>
            </tr>
            @foreach($reportData['operating_activities'] as $activity)
            <tr>
                <td>{{ $activity['description'] }}</td>
                <td class="amount">{{ number_format($activity['amount'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>Kas Bersih dari Aktivitas Operasi</td>
                <td class="amount">{{ number_format($reportData['net_operating_cash'], 0, ',', '.') }}</td>
            </tr>

            <!-- Investing Activities -->
            <tr class="section-header">
                <td>ARUS KAS DARI AKTIVITAS INVESTASI</td>
                <td class="amount"></td>
            </tr>
            @foreach($reportData['investing_activities'] as $activity)
            <tr>
                <td>{{ $activity['description'] }}</td>
                <td class="amount">{{ number_format($activity['amount'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>Kas Bersih dari Aktivitas Investasi</td>
                <td class="amount">{{ number_format($reportData['net_investing_cash'], 0, ',', '.') }}</td>
            </tr>

            <!-- Financing Activities -->
            <tr class="section-header">
                <td>ARUS KAS DARI AKTIVITAS PENDANAAN</td>
                <td class="amount"></td>
            </tr>
            @foreach($reportData['financing_activities'] as $activity)
            <tr>
                <td>{{ $activity['description'] }}</td>
                <td class="amount">{{ number_format($activity['amount'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>Kas Bersih dari Aktivitas Pendanaan</td>
                <td class="amount">{{ number_format($reportData['net_financing_cash'], 0, ',', '.') }}</td>
            </tr>

            <!-- Net Cash Flow -->
            <tr class="net-cash">
                <td>KENAIKAN (PENURUNAN) BERSIH KAS</td>
                <td class="amount">{{ number_format($reportData['net_cash_change'], 0, ',', '.') }}</td>
            </tr>

            <!-- Cash Beginning and Ending -->
            <tr>
                <td>Kas Awal Periode</td>
                <td class="amount">{{ number_format($reportData['beginning_cash'], 0, ',', '.') }}</td>
            </tr>
            <tr class="net-cash">
                <td>KAS AKHIR PERIODE</td>
                <td class="amount">{{ number_format($reportData['ending_cash'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>