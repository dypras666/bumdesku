<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti {{ $transaction->transaction_type === 'income' ? 'Kas Masuk' : 'Kas Keluar' }} - {{ $transaction->transaction_code }}</title>
    <style>
        /* Optimized for dot matrix printer */
        @page {
            size: A4;
            margin: 0.5cm;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
            color: #000;
            background: #fff;
        }
        
        .receipt-container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            margin-bottom: 3px;
        }
        
        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            text-decoration: underline;
        }
        
        .receipt-body {
            margin: 20px 0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            border-bottom: 1px dotted #666;
            padding-bottom: 3px;
        }
        
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        
        .info-value {
            width: 60%;
            text-align: right;
        }
        
        .amount-section {
            border: 2px solid #000;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .amount-label {
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .amount-value {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .amount-words {
            font-size: 11px;
            font-style: italic;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        
        .description-section {
            margin: 20px 0;
            border: 1px solid #000;
            padding: 10px;
        }
        
        .description-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .description-text {
            min-height: 40px;
            word-wrap: break-word;
        }
        
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 50px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .signature-name {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 50px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        
        .print-info {
            font-size: 9px;
            color: #666;
        }
        
        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            .receipt-container {
                box-shadow: none;
            }
        }
        
        /* Dot matrix printer optimization */
        .dotmatrix-line {
            border-bottom: 1px dotted #000;
            margin: 5px 0;
        }
        
        .bold-text {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $companyInfo['name'] }}</div>
            @if($companyInfo['address'])
            <div class="company-info">{{ $companyInfo['address'] }}</div>
            @endif
            @if($companyInfo['phone'])
            <div class="company-info">Telp: {{ $companyInfo['phone'] }}</div>
            @endif
            @if($companyInfo['email'])
            <div class="company-info">Email: {{ $companyInfo['email'] }}</div>
            @endif
            <div class="receipt-title">
                BUKTI {{ $transaction->transaction_type === 'income' ? 'KAS MASUK' : 'KAS KELUAR' }}
            </div>
        </div>
        
        <!-- Receipt Body -->
        <div class="receipt-body">
            <div class="info-row">
                <span class="info-label">No. Bukti:</span>
                <span class="info-value bold-text">{{ $transaction->transaction_code }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span class="info-value">{{ $transaction->transaction_date->format('d F Y') }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Jenis Transaksi:</span>
                <span class="info-value bold-text">{{ $transaction->getTypeLabel() }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Akun:</span>
                <span class="info-value">{{ $transaction->account->account_code ?? '-' }} - {{ $transaction->account->account_name ?? '-' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Dibuat Oleh:</span>
                <span class="info-value">{{ $transaction->user->name ?? '-' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Disetujui Oleh:</span>
                <span class="info-value">{{ $transaction->approver->name ?? '-' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Tanggal Persetujuan:</span>
                <span class="info-value">{{ $transaction->approved_at ? $transaction->approved_at->format('d F Y H:i') : '-' }}</span>
            </div>
        </div>
        
        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">JUMLAH {{ $transaction->transaction_type === 'income' ? 'DITERIMA' : 'DIBAYARKAN' }}:</div>
            <div class="amount-value">{{ format_currency($transaction->amount) }}</div>
            <div class="amount-words">
                Terbilang: {{ terbilang_official($transaction->amount) }}
            </div>
        </div>
        
        <!-- Description Section -->
        <div class="description-section">
            <div class="description-label">KETERANGAN:</div>
            <div class="description-text">{{ $transaction->description }}</div>
        </div>
        
        @if($transaction->notes)
        <!-- Notes Section -->
        <div class="description-section">
            <div class="description-label">CATATAN:</div>
            <div class="description-text">{{ $transaction->notes }}</div>
        </div>
        @endif
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">{{ $transaction->transaction_type === 'income' ? 'YANG MENYETOR' : 'YANG MENERIMA' }}</div>
                <div class="signature-name">
                    {{ $transaction->transaction_type === 'income' ? '(________________)' : '(________________)' }}
                </div>
            </div>
            
            <div class="signature-box">
                <div class="signature-title">BENDAHARA</div>
                <div class="signature-name">
                    ({{ $transaction->approver->name ?? '________________' }})
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="dotmatrix-line"></div>
            <div class="print-info">
                Dicetak pada: {{ now()->format('d F Y H:i:s') }} | 
                Status: {{ $transaction->getStatusLabel() }} |
                Sistem: BUMDES Management
            </div>
        </div>
    </div>
    
    <!-- Print Button (hidden when printing) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Cetak Bukti
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
    
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
        
        // Print function
        function printReceipt() {
            window.print();
        }
        
        // Format for dot matrix printer
        window.addEventListener('beforeprint', function() {
            document.body.style.fontSize = '12px';
            document.body.style.lineHeight = '1.2';
        });
    </script>
</body>
</html>