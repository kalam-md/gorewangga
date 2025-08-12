@extends('layout.main')

@section('page-title', 'Laporan Pemesanan')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Laporan Pemesanan</h2>
                <p class="text-gray-600 mt-1">Kelola dan pantau semua data pemesanan lapangan</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="cetakLaporan()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Cetak Laporan PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Total Pemesanan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_pemesanan']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Sukses</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['pemesanan_sukses']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['pemesanan_pending']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-money-bill-wave text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-teal-100 rounded-lg">
                    <i class="fas fa-calendar-day text-teal-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Hari Ini</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['pemesanan_hari_ini']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian</h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nama penyewa atau lapangan..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pemesanan</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sukses" {{ request('status') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                        <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                    <select name="status_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status_bayar') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="valid" {{ request('status_bayar') == 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="invalid" {{ request('status_bayar') == 'invalid' ? 'selected' : '' }}>Invalid</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Lapangan</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Jenis</option>
                        <option value="futsal" {{ request('jenis') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="basket" {{ request('jenis') == 'basket' ? 'selected' : '' }}>Basket</option>
                        <option value="badminton" {{ request('jenis') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                        <option value="tenis" {{ request('jenis') == 'tenis' ? 'selected' : '' }}>Tenis</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pemesanans as $index => $pemesanan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pemesanans->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-gray-900">{{ $pemesanan->user->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $pemesanan->user->no_telp }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-gray-900">{{ $pemesanan->lapangan->nama_lapangan }}</div>
                                <div class="text-sm text-gray-500">{{ $pemesanan->lapangan->jenis_label }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pemesanan->tanggal_pemesanan->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($pemesanan->jadwal_formatted)
                                {{ implode(', ', $pemesanan->jadwal_formatted) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $pemesanan->total_harga_formatted }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pemesanan->status_badge['class'] }}">
                                <i class="fas {{ $pemesanan->status_badge['icon'] }} mr-1"></i>
                                {{ $pemesanan->status_badge['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pemesanan->pembayaran)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pemesanan->pembayaran->status_badge['class'] }}">
                                    <i class="fas {{ $pemesanan->pembayaran->status_badge['icon'] }} mr-1"></i>
                                    {{ $pemesanan->pembayaran->status_badge['text'] }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="cetakTiket({{ $pemesanan->id }})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs inline-flex items-center">
                                <i class="fas fa-ticket-alt mr-1"></i>
                                Cetak Tiket
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                <p>Tidak ada data pemesanan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pemesanans->hasPages())
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $pemesanans->withQueryString()->links() }}
        </div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
function cetakTiket(id) {
    // Buat form hidden untuk mengirim request POST
    let form = document.createElement('form');
    form.method = 'GET';
    form.action = `/laporan/cetak-pdf/${id}`;
    form.target = '_blank';
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function cetakLaporan() {
    // Ambil semua parameter filter yang sedang aktif
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);
    
    // Buat URL untuk cetak laporan dengan filter yang sama
    let cetakUrl = '/laporan/cetak-pdf';
    if (params.toString()) {
        cetakUrl += '?' + params.toString();
    }
    
    // Buka di tab baru
    window.open(cetakUrl, '_blank');
}
</script>
@endpush