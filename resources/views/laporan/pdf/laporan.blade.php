<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemesanan Lapangan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .report-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .report-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 3px;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #0d9488;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        td {
            font-size: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-sukses {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-gagal {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-valid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-invalid {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .footer {
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                font-size: 10px;
            }
            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="logo">ARENA SPORT</div>
        <div class="subtitle">LAPORAN PEMESANAN LAPANGAN</div>
    </div>

    <!-- Meta Informasi Laporan -->
    <div class="report-info">
        <div class="report-meta">
            <div>
                <strong>Periode:</strong> 
                @if($stats['periode']['dari'] !== 'Semua' || $stats['periode']['sampai'] !== 'Semua')
                    {{ $stats['periode']['dari'] }} s/d {{ $stats['periode']['sampai'] }}
                @else
                    Semua Data
                @endif
            </div>
            <div><strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i:s') }} WIB</div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_pemesanan']) }}</div>
            <div class="stat-label">Total Pemesanan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($pemesanans->where('status', 'sukses')->count()) }}</div>
            <div class="stat-label">Sukses</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($pemesanans->where('status', 'pending')->count()) }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 15%;">Penyewa</th>
                <th style="width: 15%;">Lapangan</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 12%;">Jadwal</th>
                <th style="width: 12%;">Total</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 8%;">Bayar</th>
                <th style="width: 16%;">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesanans as $index => $pemesanan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="font-weight: 500;">{{ $pemesanan->user->nama_lengkap }}</div>
                    <div style="color: #666; font-size: 9px;">{{ $pemesanan->user->no_telp }}</div>
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $pemesanan->lapangan->nama_lapangan }}</div>
                    <div style="color: #666; font-size: 9px;">{{ $pemesanan->lapangan->jenis_label }}</div>
                </td>
                <td>{{ $pemesanan->tanggal_pemesanan->format('d/m/Y') }}</td>
                <td>
                    @if($pemesanan->jadwal_formatted && count($pemesanan->jadwal_formatted) > 0)
                        @php
                            $jadwalArray = $pemesanan->jadwal_formatted;
                            $jamMulai = reset($jadwalArray);
                            $jamSelesai = end($jadwalArray);
                        @endphp
                        <div style="font-weight: 500;">
                            {{ $jamMulai }} - {{ $jamSelesai }}
                        </div>
                        <div style="color: #666; font-size: 8px;">
                            {{ count($jadwalArray) }} jam
                        </div>
                    @else
                        -
                    @endif
                </td>
                <td style="font-weight: 500;">{{ $pemesanan->total_harga_formatted }}</td>
                <td>
                    <span class="status-badge status-{{ $pemesanan->status }}">
                        {{ $pemesanan->status }}
                    </span>
                </td>
                <td>
                    @if($pemesanan->pembayaran)
                        <span class="status-badge status-{{ $pemesanan->pembayaran->status }}">
                            {{ $pemesanan->pembayaran->status }}
                        </span>
                    @else
                        <span style="color: #999; font-size: 8px;">-</span>
                    @endif
                </td>
                <td>
                    @if($pemesanan->pembayaran && $pemesanan->pembayaran->metode)
                        <div>{{ $pemesanan->pembayaran->metode_formatted }}</div>
                        @if($pemesanan->pembayaran->tanggal_bayar)
                            <div style="color: #666; font-size: 8px;">
                                {{ $pemesanan->pembayaran->tanggal_bayar->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; color: #999; padding: 20px;">
                    Tidak ada data pemesanan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary Footer -->
    @if($pemesanans->count() > 0)
    <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span><strong>Total Pemesanan dalam Laporan:</strong></span>
            <span><strong>{{ $pemesanans->count() }}</strong></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span><strong>Pemesanan Sukses:</strong></span>
            <span><strong>{{ $pemesanans->where('status', 'sukses')->count() }}</strong></span>
        </div>
        <div style="display: flex; justify-content: space-between; border-top: 1px solid #ddd; padding-top: 5px; margin-top: 10px;">
            <span><strong>Total Pendapatan Terkonfirmasi:</strong></span>
            <span><strong>Rp {{ number_format($pemesanans->where('status', 'sukses')->filter(function($p) { return $p->pembayaran && $p->pembayaran->status === 'valid'; })->sum('total_harga'), 0, ',', '.') }}</strong></span>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>Arena Sport - Sistem Pemesanan Lapangan</div>
        <div style="margin-top: 3px;">
            Laporan ini digenerate otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }} WIB
        </div>
    </div>
</body>
</html>