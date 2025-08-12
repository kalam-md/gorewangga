@extends('layout.main')

@section('page-title', 'Manajemen Lapangan')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Manajemen Lapangan</h2>
                <p class="text-gray-600 mt-1">Kelola data lapangan olahraga</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('lapangan.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Lapangan
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Lapangan</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-map text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Lapangan Aktif</p>
                    <p class="text-2xl font-semibold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Futsal</p>
                    <p class="text-2xl font-semibold text-orange-600">{{ $stats['futsal'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-futbol text-orange-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Basket</p>
                    <p class="text-2xl font-semibold text-purple-600">{{ $stats['basket'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-basketball-ball text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lapangan Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Data Lapangan</h3>
                <div class="mt-4 md:mt-0 flex flex-wrap items-center gap-3">
                    <!-- Search Box -->
                    <form method="GET" class="flex items-center space-x-2">
                        <div class="relative">
                            <input type="text" 
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari lapangan..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        
                        <!-- Filter Jenis -->
                        <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="">Semua Jenis</option>
                            <option value="futsal" {{ request('jenis') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="basket" {{ request('jenis') == 'basket' ? 'selected' : '' }}>Basket</option>
                            <option value="badminton" {{ request('jenis') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                            <option value="tenis" {{ request('jenis') == 'tenis' ? 'selected' : '' }}>Tenis</option>
                        </select>

                        <!-- Filter Status -->
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-filter mr-1"></i>
                            Filter
                        </button>

                        @if(request()->hasAny(['search', 'jenis', 'status']))
                            <a href="{{ route('lapangan.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-times mr-1"></i>
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="lapanganTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lapangans as $lapangan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-12 w-12 flex-shrink-0">
                                    <img class="h-12 w-12 rounded-lg object-cover" 
                                         src="{{ $lapangan->gambar_utama }}" 
                                         alt="{{ $lapangan->nama_lapangan }}"
                                         onerror="this.src='https://placehold.co/48x48/e5e7eb/6b7280?text=No+Image'">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $lapangan->nama_lapangan }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ Str::limit($lapangan->deskripsi, 50) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($lapangan->jenis == 'futsal') bg-green-100 text-green-800
                                @elseif($lapangan->jenis == 'basket') bg-orange-100 text-orange-800
                                @elseif($lapangan->jenis == 'badminton') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800 @endif">
                                @if($lapangan->jenis == 'futsal')
                                    <i class="fas fa-futbol mr-1"></i>
                                @elseif($lapangan->jenis == 'basket')
                                    <i class="fas fa-basketball-ball mr-1"></i>
                                @elseif($lapangan->jenis == 'badminton')
                                    <i class="fas fa-table-tennis mr-1"></i>
                                @else
                                    <i class="fas fa-tennis-ball mr-1"></i>
                                @endif
                                {{ $lapangan->jenis_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $lapangan->formatted_harga }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleStatus({{ $lapangan->id }})" 
                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full transition-colors
                                    {{ $lapangan->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                <i class="fas {{ $lapangan->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ $lapangan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('lapangan.show', $lapangan) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </a>
                                <a href="{{ route('lapangan.edit', $lapangan) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded-md transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                <button onclick="confirmDelete({{ $lapangan->id }}, '{{ $lapangan->nama_lapangan }}')" 
                                        class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-map text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Tidak ada data lapangan</p>
                                <a href="{{ route('lapangan.create') }}" 
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Lapangan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lapangans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $lapangans->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</main>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
        </div>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus lapangan <span id="lapanganName" class="font-semibold"></span>? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(lapanganId) {
    fetch(`/lapangan/${lapanganId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to update UI
            window.location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status');
    });
}

function confirmDelete(lapanganId, lapanganName) {
    document.getElementById('lapanganName').textContent = lapanganName;
    document.getElementById('deleteForm').action = `/lapangan/${lapanganId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush