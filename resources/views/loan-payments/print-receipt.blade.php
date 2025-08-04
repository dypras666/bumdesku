<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - {{ $loanPayment->payment_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-logo {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }
        .company-address {
            font-size: 11px;
            color: #666;
        }
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-transform: uppercase;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            width: 48%;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 40%;
            font-weight: bold;
        }
        .payment-details {
            margin: 20px 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
        }
        .details-table th,
        .details-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .details-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 30%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        .notes {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if(company_info('logo'))
            <img src="{{ asset('storage/' . company_info('logo')) }}" alt="Logo" class="company-logo">
        @endif
        <div class="company-name">{{ company_info('name', 'BUMDES') }}</div>
        <div class="company-address">
            {{ company_info('address', 'Alamat Perusahaan') }}<br>
            Telp: {{ company_info('phone', '-') }} | Email: {{ company_info('email', '-') }}
        </div>
    </div>

    <!-- Receipt Title -->
    <div class="receipt-title">Bukti Pembayaran Pinjaman</div>

    <!-- Receipt Information -->
    <div class="receipt-info">
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td>Kode Pembayaran:</td>
                    <td>{{ $loanPayment->payment_code }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pembayaran:</td>
                    <td>{{ $loanPayment->formatted_payment_date }}</td>
                </tr>
                <tr>
                    <td>Metode Pembayaran:</td>
                    <td>{{ $loanPayment->payment_method_name }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><strong>{{ $loanPayment->status_name }}</strong></td>
                </tr>
            </table>
        </div>
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td>Kode Pinjaman:</td>
                    <td>{{ $loanPayment->loan->loan_code }}</td>
                </tr>
                <tr>
                    <td>Nama Peminjam:</td>
                    <td>{{ $loanPayment->loan->borrower_name }}</td>
                </tr>
                <tr>
                    <td>Jenis Pinjaman:</td>
                    <td>{{ $loanPayment->loan->loan_type_name }}</td>
                </tr>
                <tr>
                    <td>Cicilan Ke:</td>
                    <td>{{ $loanPayment->installment_number }} dari {{ $loanPayment->loan->loan_term_months }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="payment-details">
        <table class="details-table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th style="width: 150px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @if($loanPayment->principal_amount > 0)
                <tr>
                    <td>Pokok Pinjaman</td>
                    <td class="amount">{{ number_format($loanPayment->principal_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                
                @if($loanPayment->interest_amount > 0)
                <tr>
                    <td>
                        @if($loanPayment->loan->loan_type == 'bagi_hasil')
                            Bagi Hasil ({{ $loanPayment->loan->formatted_profit_sharing_percentage }})
                        @else
                            Bunga ({{ $loanPayment->loan->interest_rate }}% per tahun)
                        @endif
                    </td>
                    <td class="amount">{{ number_format($loanPayment->interest_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                
                @if($loanPayment->penalty_amount > 0)
                <tr>
                    <td>Denda Keterlambatan</td>
                    <td class="amount">{{ number_format($loanPayment->penalty_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                
                <tr class="total-row">
                    <td><strong>Total Pembayaran</strong></td>
                    <td class="amount"><strong>Rp {{ number_format($loanPayment->payment_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Loan Summary -->
    <div class="payment-details">
        <h4>Ringkasan Pinjaman</h4>
        <table class="details-table">
            <tbody>
                <tr>
                    <td><strong>Total Pinjaman</strong></td>
                    <td class="amount">{{ $loanPayment->loan->formatted_loan_amount }}</td>
                </tr>
                <tr>
                    <td><strong>Total Sudah Dibayar</strong></td>
                    <td class="amount">{{ $loanPayment->loan->formatted_total_paid }}</td>
                </tr>
                <tr>
                    <td><strong>Sisa Pinjaman</strong></td>
                    <td class="amount">{{ $loanPayment->loan->formatted_remaining_balance }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($loanPayment->notes)
    <!-- Notes -->
    <div class="notes">
        <strong>Catatan:</strong><br>
        {{ $loanPayment->notes }}
    </div>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Penerima</div>
            <div class="signature-line">
                {{ $loanPayment->loan->borrower_name }}
            </div>
        </div>
        <div class="signature-box">
            <div>Petugas</div>
            <div class="signature-line">
                {{ $loanPayment->creator->name ?? '-' }}
            </div>
        </div>
        <div class="signature-box">
            <div>Menyetujui</div>
            <div class="signature-line">
                {{ $loanPayment->approver->name ?? '-' }}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Bukti pembayaran ini dicetak pada {{ now()->format('d/m/Y H:i:s') }}<br>
        Dokumen ini sah tanpa tanda tangan basah
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>