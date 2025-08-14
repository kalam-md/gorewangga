@extends('layout.main')

@section('page-title', 'Daftar Pemesanan')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Daftar Pemesanan</h2>
                <p class="text-gray-600 mt-1">Kelola semua pemesanan lapangan</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('pemesanan.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Pemesanan
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Pemesanan Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Data Pemesanan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pemesanans as $pemesanan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $pemesanan->user->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $pemesanan->tanggal_pemesanan->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $pemesanan->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $pemesanan->lapangan->nama_lapangan }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ ucfirst($pemesanan->lapangan->jenis) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach(json_decode($pemesanan->jadwal) as $jam)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-md">
                                        {{ \Carbon\Carbon::parse($jam)->format('H:i') }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full items-center
                                @if($pemesanan->status == 'sukses') bg-green-100 text-green-800
                                @elseif($pemesanan->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($pemesanan->status == 'sukses')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($pemesanan->status == 'pending')
                                    <i class="fas fa-clock mr-1"></i>
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>
                                @endif
                                {{ ucfirst($pemesanan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">
                                    {{ $pemesanan->pembayaran->metode ?? 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('pemesanan.show', $pemesanan) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </a>
                                
                                @if($pemesanan->status == 'pending' && $pemesanan->pembayaran->status == 'pending' && !$pemesanan->pembayaran->bukti_transfer)
                                    <button onclick="openUploadModal({{ $pemesanan->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                                        <i class="fas fa-upload mr-1"></i>
                                        Upload Bukti
                                    </button>
                                @endif
                                
                                @if($pemesanan->pembayaran->bukti_transfer && $pemesanan->pembayaran->status == 'pending' && auth()->user()->role == 'admin')
                                    <button onclick="openValidasiModal({{ $pemesanan->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md transition-colors">
                                        <i class="fas fa-check mr-1"></i>
                                        Validasi
                                    </button>
                                @endif
                                
                                @if($pemesanan->status == 'pending' && (auth()->user()->id == $pemesanan->user_id || auth()->user()->role == 'admin'))
                                    <button onclick="confirmDelete({{ $pemesanan->id }}, '{{ $pemesanan->lapangan->nama_lapangan }}')" 
                                            class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors">
                                        <i class="fas fa-times mr-1"></i>
                                        Batal
                                    </button>
                                @endif

                                <button onclick="cetakTiket({{ $pemesanan->id }})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs inline-flex items-center">
                                    <i class="fas fa-ticket-alt mr-1"></i>
                                    Cetak Tiket
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-calendar-alt text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Tidak ada data pemesanan</p>
                                <a href="{{ route('pemesanan.create') }}" 
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Buat Pemesanan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pemesanans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pemesanans->links() }}
        </div>
        @endif
    </div>
</main>

<!-- Upload Bukti Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-upload text-green-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Upload Bukti Transfer</h3>
        </div>
        
        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih File Bukti Transfer
                </label>
                <input type="file" 
                       id="bukti_transfer" 
                       name="bukti_transfer" 
                       accept="image/*" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB</p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeUploadModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                    <i class="fas fa-upload mr-1"></i>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Validasi Modal -->
<div id="validasiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-check text-purple-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Validasi Pembayaran</h3>
        </div>
        
        <p class="text-gray-600 mb-4">Pilih status pembayaran untuk pemesanan ini:</p>
        
        <form id="validasiForm" method="POST">
            @csrf
            <div class="mb-4">
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="status_pembayaran" value="valid" class="mr-2" required>
                        <span class="text-green-600">Valid - Pembayaran diterima</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status_pembayaran" value="invalid" class="mr-2" required>
                        <span class="text-red-600">Invalid - Pembayaran ditolak</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeValidasiModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors">
                    <i class="fas fa-check mr-1"></i>
                    Validasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Batalkan</h3>
        </div>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin membatalkan pemesanan <span id="lapanganName" class="font-semibold"></span>? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                    Ya, Batalkan
                </button>
            </form>
        </div>
    </div>
</div>
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

function openUploadModal(pemesananId) {
    document.getElementById('uploadForm').action = `/pemesanan/${pemesananId}/upload-bukti`;
    document.getElementById('uploadModal').classList.remove('hidden');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadForm').reset();
}

function openValidasiModal(pemesananId) {
    document.getElementById('validasiForm').action = `/pemesanan/${pemesananId}/validasi-pembayaran`;
    document.getElementById('validasiModal').classList.remove('hidden');
}

function closeValidasiModal() {
    document.getElementById('validasiModal').classList.add('hidden');
    document.getElementById('validasiForm').reset();
}

function confirmDelete(pemesananId, lapanganName) {
    document.getElementById('lapanganName').textContent = lapanganName;
    document.getElementById('deleteForm').action = `/pemesanan/${pemesananId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'uploadModal') closeUploadModal();
    if (e.target.id === 'validasiModal') closeValidasiModal();
    if (e.target.id === 'deleteModal') closeDeleteModal();
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUploadModal();
        closeValidasiModal();
        closeDeleteModal();
    }
});
</script>
@endpush