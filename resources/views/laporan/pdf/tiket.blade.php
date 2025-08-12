<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket Pemesanan #{{ $pemesanan->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .tiket {
            max-width: 400px;
            margin: 0 auto;
            border: 2px dashed #333;
            padding: 20px;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .tiket-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .tiket-number {
            font-size: 16px;
            font-weight: bold;
            color: #0d9488;
        }
        
        .detail-section {
            margin-bottom: 15px;
        }
        
        .section-title {
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            align-items: flex-start;
        }
        
        .detail-label {
            font-weight: 500;
            color: #666;
            width: 40%;
        }
        
        .detail-value {
            font-weight: 600;
            color: #333;
            width: 58%;
            text-align: right;
        }
        
        .jadwal-highlight {
            background: #0d9488;
            color: white;
            padding: 8px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .total-section {
            background: #f0fdfa;
            border: 1px solid #0d9488;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
            color: #0d9488;
        }
        
        .status-section {
            text-align: center;
            margin: 15px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .status-sukses {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .footer {
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .qr-section {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 8px;
            border-radius: 3px;
            margin: 10px 0;
            font-size: 10px;
            color: #856404;
        }
        
        .print-info {
            text-align: center;
            margin-top: 15px;
            font-size: 9px;
            color: #999;
        }
        
        @media print {
            body {
                margin: 0;
                font-size: 11px;
            }
            .tiket {
                border: 2px dashed #000;
                max-width: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="tiket">
        <!-- Header -->
        <div class="header">
            <div class="logo">ARENA SPORT</div>
            <div class="subtitle">Sistem Pemesanan Lapangan</div>
        </div>

        <!-- Tiket Info -->
        <div class="tiket-info">
            <div class="tiket-number">TIKET #{{ str_pad($pemesanan->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div style="font-size: 10px; margin-top: 3px;">{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>

        <!-- Detail Penyewa -->
        <div class="detail-section">
            <div class="section-title">Informasi Penyewa</div>
            <div class="detail-row">
                <span class="detail-label">Nama:</span>
                <span class="detail-value">{{ $pemesanan->user->nama_lengkap }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">No. Telepon:</span>
                <span class="detail-value">{{ $pemesanan->user->no_telp }}</span>
            </div>
        </div>

        <!-- Detail Lapangan -->
        <div class="detail-section">
            <div class="section-title">Detail Lapangan</div>
            <div class="detail-row">
                <span class="detail-label">Lapangan:</span>
                <span class="detail-value">{{ $pemesanan->lapangan->nama_lapangan }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Jenis:</span>
                <span class="detail-value">{{ $pemesanan->lapangan->jenis_label }}</span>
            </div>
        </div>

        <!-- Jadwal Booking -->
        <div class="detail-section">
            <div class="section-title">Jadwal Pemesanan</div>
            <div class="detail-row">
                <span class="detail-label">Tanggal:</span>
                <span class="detail-value">{{ $pemesanan->tanggal_pemesanan->format('l, d F Y') }}</span>
            </div>
            @if($pemesanan->jadwal_formatted && count($pemesanan->jadwal_formatted) > 0)
            @php
                $jadwalArray = $pemesanan->jadwal_formatted;
                $jamMulai = reset($jadwalArray);
                $jamSelesai = end($jadwalArray);
            @endphp
            <div class="jadwal-highlight">
                {{ $jamMulai }} - {{ $jamSelesai }}
                ({{ count($jadwalArray) }} Jam)
            </div>
            @endif
        </div>

        <!-- Total Pembayaran -->
        <div class="total-section">
            <div class="total-row">
                <span>TOTAL PEMBAYARAN:</span>
                <span>{{ $pemesanan->total_harga_formatted }}</span>
            </div>
        </div>

        <!-- Status -->
        <div class="status-section">
            <div class="status-badge status-{{ $pemesanan->status }}">
                Pemesanan {{ ucfirst($pemesanan->status) }}
            </div>
            @if($pemesanan->pembayaran)
            <div class="status-badge status-{{ $pemesanan->pembayaran->status }}" style="margin-left: 5px;">
                Pembayaran {{ ucfirst($pemesanan->pembayaran->status) }}
            </div>
            @endif
        </div>

        <!-- Informasi Pembayaran -->
        @if($pemesanan->pembayaran)
        <div class="detail-section">
            <div class="section-title">Informasi Pembayaran</div>
            <div class="detail-row">
                <span class="detail-label">Metode:</span>
                <span class="detail-value">{{ $pemesanan->pembayaran->metode_formatted }}</span>
            </div>
            @if($pemesanan->pembayaran->tanggal_bayar)
            <div class="detail-row">
                <span class="detail-label">Tanggal Bayar:</span>
                <span class="detail-value">{{ $pemesanan->pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</span>
            </div>
            @endif
        </div>
        @endif

        <!-- QR Code Section (Optional) -->
        <div class="qr-section">
            <div style="font-weight: bold; margin-bottom: 5px;">Kode Verifikasi:</div>
            <div style="font-family: monospace; font-size: 14px; font-weight: bold; letter-spacing: 1px;">
                {{ strtoupper(substr(md5($pemesanan->id . $pemesanan->created_at), 0, 8)) }}
            </div>
        </div>

        <!-- Important Notes -->
        <div class="important-note">
            <strong>PENTING:</strong> Tunjukkan tiket ini saat datang ke lapangan. Simpan tiket sebagai bukti pemesanan yang sah.
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>Terima kasih telah menggunakan layanan kami!</div>
            <div style="margin-top: 5px;">
                Hubungi kami: admin@arenasport.com | +62 812 3456 7890
            </div>
        </div>

        <!-- Print Info -->
        <div class="print-info">
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} WIB
        </div>
    </div>
</body>
</html>