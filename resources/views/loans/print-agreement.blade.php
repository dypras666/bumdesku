<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perjanjian Pinjaman - {{ $loan->loan_code }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 16pt;
            margin: 5px 0;
        }
        .header p {
            margin: 5px 0;
            font-size: 11pt;
        }
        .content {
            margin: 20px 0;
        }
        .section {
            margin: 20px 0;
        }
        .section h3 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .party-info {
            margin: 15px 0;
            padding-left: 20px;
        }
        .terms {
            margin: 20px 0;
        }
        .terms ol {
            padding-left: 20px;
        }
        .terms li {
            margin: 10px 0;
            text-align: justify;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 80px;
            padding-top: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .amount {
            font-weight: bold;
        }
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <div class="header">
        <h1>{{ company_info('name') ?? 'BUMDES SEJAHTERA' }}</h1>
        <h2>SURAT PERJANJIAN PINJAMAN</h2>
        <p>{{ company_info('address') ?? 'Alamat Perusahaan' }}</p>
        <p>Telp: {{ company_info('phone') ?? '(021) 1234567' }} | Email: {{ company_info('email') ?? 'info@bumdes.com' }}</p>
    </div>

    <div class="content">
        <div class="section">
            <p style="text-align: center; font-weight: bold; font-size: 14pt;">
                NOMOR: {{ $loan->loan_code }}
            </p>
        </div>

        <div class="section">
            <p style="text-align: justify;">
                Pada hari ini, {{ \Carbon\Carbon::parse($loan->loan_date)->locale('id')->translatedFormat('l, d F Y') }}, 
                telah dibuat dan ditandatangani Surat Perjanjian Pinjaman antara:
            </p>
        </div>

        <div class="section">
            <h3>PIHAK PERTAMA (PEMBERI PINJAMAN)</h3>
            <div class="party-info">
                <p><strong>Nama:</strong> {{ company_info('name') ?? 'BUMDES SEJAHTERA' }}</p>
                <p><strong>Alamat:</strong> {{ company_info('address') ?? 'Alamat Perusahaan' }}</p>
                <p><strong>Diwakili oleh:</strong> {{ $loan->approver->name ?? 'Direktur BUMDES' }}</p>
                <p>Selanjutnya disebut sebagai <strong>"PEMBERI PINJAMAN"</strong></p>
            </div>
        </div>

        <div class="section">
            <h3>PIHAK KEDUA (PENERIMA PINJAMAN)</h3>
            <div class="party-info">
                <p><strong>Nama:</strong> {{ $loan->borrower_name }}</p>
                <p><strong>Alamat:</strong> {{ $loan->borrower_address ?? '-' }}</p>
                <p><strong>No. Telepon:</strong> {{ $loan->borrower_phone }}</p>
                <p><strong>No. KTP:</strong> {{ $loan->borrower_id_number ?? '-' }}</p>
                <p>Selanjutnya disebut sebagai <strong>"PENERIMA PINJAMAN"</strong></p>
            </div>
        </div>

        <div class="section">
            <h3>KETENTUAN PINJAMAN</h3>
            <table class="table">
                <tr>
                    <td><strong>Jenis Pinjaman</strong></td>
                    <td>
                        @if($loan->loan_type === 'bunga')
                            Pinjaman dengan Bunga
                        @elseif($loan->loan_type === 'bagi_hasil')
                            Pinjaman Bagi Hasil
                        @else
                            Pinjaman Tanpa Bunga
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Jumlah Pinjaman</strong></td>
                    <td class="amount">{{ format_currency($loan->loan_amount) }}</td>
                </tr>
                @if($loan->loan_type === 'bunga' && $loan->interest_rate > 0)
                <tr>
                    <td><strong>Tingkat Bunga</strong></td>
                    <td>{{ number_format($loan->interest_rate, 2) }}% per tahun</td>
                </tr>
                @endif
                @if($loan->loan_type === 'bagi_hasil')
                <tr>
                    <td><strong>Persentase Bagi Hasil</strong></td>
                    <td>{{ number_format($loan->profit_sharing_percentage, 2) }}%</td>
                </tr>
                <tr>
                    <td><strong>Estimasi Keuntungan</strong></td>
                    <td class="amount">{{ format_currency($loan->expected_profit) }}</td>
                </tr>
                @endif
                @if($loan->admin_fee > 0)
                <tr>
                    <td><strong>Biaya Administrasi</strong></td>
                    <td class="amount">{{ format_currency($loan->admin_fee) }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Jangka Waktu</strong></td>
                    <td>{{ $loan->loan_term_months }} bulan</td>
                </tr>
                <tr>
                    <td><strong>Cicilan per Bulan</strong></td>
                    <td class="amount">{{ format_currency($loan->monthly_payment) }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Mulai</strong></td>
                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Jatuh Tempo</strong></td>
                    <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d F Y') }}</td>
                </tr>
            </table>
        </div>

        @if($loan->loan_type === 'bagi_hasil' && $loan->business_description)
        <div class="section">
            <h3>DESKRIPSI USAHA</h3>
            <p style="text-align: justify;">{{ $loan->business_description }}</p>
        </div>
        @endif

        <div class="section">
            <h3>SYARAT DAN KETENTUAN</h3>
            <div class="terms">
                <ol>
                    <li>PENERIMA PINJAMAN wajib menggunakan dana pinjaman sesuai dengan tujuan yang telah disepakati.</li>
                    <li>Pembayaran cicilan dilakukan setiap bulan sesuai dengan jadwal yang telah ditentukan.</li>
                    <li>Keterlambatan pembayaran akan dikenakan denda sesuai dengan ketentuan yang berlaku.</li>
                    @if($loan->loan_type === 'bagi_hasil')
                    <li>PENERIMA PINJAMAN wajib melaporkan perkembangan usaha secara berkala kepada PEMBERI PINJAMAN.</li>
                    <li>Pembagian hasil usaha akan dilakukan sesuai dengan persentase yang telah disepakati.</li>
                    @endif
                    <li>PENERIMA PINJAMAN dapat melakukan pelunasan dipercepat tanpa dikenakan penalti.</li>
                    <li>Apabila terjadi perselisihan, akan diselesaikan secara musyawarah mufakat.</li>
                    <li>Perjanjian ini berlaku sejak ditandatangani hingga seluruh kewajiban diselesaikan.</li>
                </ol>
            </div>
        </div>

        @if($loan->notes)
        <div class="section">
            <h3>CATATAN KHUSUS</h3>
            <p style="text-align: justify;">{{ $loan->notes }}</p>
        </div>
        @endif

        <div class="section">
            <p style="text-align: justify;">
                Demikian surat perjanjian ini dibuat dengan sebenar-benarnya dan ditandatangani oleh kedua belah pihak 
                dalam keadaan sehat jasmani dan rohani serta tanpa ada paksaan dari pihak manapun.
            </p>
        </div>

        <div class="signature">
            <div class="signature-box">
                <p><strong>PENERIMA PINJAMAN</strong></p>
                <div class="signature-line">
                    <p><strong>{{ $loan->borrower_name }}</strong></p>
                </div>
            </div>
            <div class="signature-box">
                <p><strong>PEMBERI PINJAMAN</strong></p>
                <div class="signature-line">
                    <p><strong>{{ $loan->approver->name ?? 'Direktur BUMDES' }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>