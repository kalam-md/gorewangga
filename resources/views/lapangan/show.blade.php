@extends('layout.main')

@section('page-title', 'Detail Lapangan')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('lapangan.index') }}" 
                   class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $lapangan->nama_lapangan }}</h2>
                    <p class="text-gray-600 mt-1">Detail informasi lapangan</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->role === 'petugas')
                <a href="{{ route('lapangan.edit', $lapangan) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                @endif
                <button onclick="toggleStatus({{ $lapangan->id }})" 
                        class="inline-flex items-center px-4 py-2 font-medium rounded-lg transition-colors
                        {{ $lapangan->is_active ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                    <i class="fas {{ $lapangan->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                    {{ $lapangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Lapangan Info Card -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Image Gallery -->
                @if($lapangan->gambar && count($lapangan->gambar) > 0)
                <div class="relative">
                    <div id="imageCarousel" class="relative h-96 overflow-hidden">
                        @foreach($lapangan->gambar_urls as $index => $url)
                        <div class="carousel-item {{ $index === 0 ? 'active' : 'hidden' }} absolute inset-0">
                            <img src="{{ $url }}" 
                                 alt="{{ $lapangan->nama_lapangan }} - {{ $index + 1 }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                    
                    @if(count($lapangan->gambar) > 1)
                    <!-- Carousel Controls -->
                    <button onclick="previousImage()" 
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="nextImage()" 
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <!-- Indicators -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        @foreach($lapangan->gambar as $index => $image)
                        <button onclick="goToImage({{ $index }})" 
                                class="w-2 h-2 rounded-full transition-colors {{ $index === 0 ? 'bg-white' : 'bg-white bg-opacity-50' }}"
                                data-index="{{ $index }}"></button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @else
                <div class="h-64 bg-gray-200 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Tidak ada gambar</p>
                    </div>
                </div>
                @endif

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $lapangan->nama_lapangan }}</h3>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full 
                                    @if($lapangan->jenis == 'futsal') bg-green-100 text-green-800
                                    @elseif($lapangan->jenis == 'basket') bg-orange-100 text-orange-800
                                    @elseif($lapangan->jenis == 'badminton') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    @if($lapangan->jenis == 'futsal')
                                        <i class="fas fa-futbol mr-2"></i>
                                    @elseif($lapangan->jenis == 'basket')
                                        <i class="fas fa-basketball-ball mr-2"></i>
                                    @elseif($lapangan->jenis == 'badminton')
                                        <i class="fas fa-table-tennis mr-2"></i>
                                    @else
                                        <i class="fas fa-tennis-ball mr-2"></i>
                                    @endif
                                    {{ $lapangan->jenis_label }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full 
                                    {{ $lapangan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $lapangan->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                    {{ $lapangan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-teal-600">{{ $lapangan->formatted_harga }}</p>
                            <p class="text-sm text-gray-500">per jam</p>
                        </div>
                    </div>

                    @if($lapangan->deskripsi)
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h4>
                        <p class="text-gray-700 leading-relaxed">{{ $lapangan->deskripsi }}</p>
                    </div>
                    @endif

                    <!-- Information Grid -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID Lapangan</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">#{{ str_pad($lapangan->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $lapangan->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            @if(auth()->user()->role === 'petugas')
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('lapangan.edit', $lapangan) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Lapangan
                    </a>
                    <button onclick="toggleStatus({{ $lapangan->id }})" 
                            class="w-full flex items-center justify-center px-4 py-2 rounded-lg transition-colors
                            {{ $lapangan->is_active ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                        <i class="fas {{ $lapangan->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                        {{ $lapangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                    <button onclick="confirmDelete({{ $lapangan->id }}, '{{ $lapangan->nama_lapangan }}')" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Lapangan
                    </button>
                </div>
            </div>
            @endif
        </div>
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
let currentImageIndex = 0;
const totalImages = {{ count($lapangan->gambar ?? []) }};

function showImage(index) {
    const items = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('[data-index]');
    
    items.forEach((item, i) => {
        if (i === index) {
            item.classList.remove('hidden');
            item.classList.add('active');
        } else {
            item.classList.add('hidden');
            item.classList.remove('active');
        }
    });
    
    indicators.forEach((indicator, i) => {
        if (i === index) {
            indicator.classList.remove('bg-opacity-50');
            indicator.classList.add('bg-white');
        } else {
            indicator.classList.add('bg-opacity-50');
            indicator.classList.remove('bg-white');
        }
    });
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % totalImages;
    showImage(currentImageIndex);
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
    showImage(currentImageIndex);
}

function goToImage(index) {
    currentImageIndex = index;
    showImage(currentImageIndex);
}

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

// Auto slide carousel (optional)
if (totalImages > 1) {
    setInterval(() => {
        nextImage();
    }, 5000);
}

// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
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