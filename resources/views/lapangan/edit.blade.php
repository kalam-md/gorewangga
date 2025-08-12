@extends('layout.main')

@section('page-title', 'Edit Lapangan')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('lapangan.show', $lapangan) }}" 
               class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Edit Lapangan</h2>
                <p class="text-gray-600 mt-1">Perbarui informasi {{ $lapangan->nama_lapangan }}</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form action="{{ route('lapangan.update', $lapangan) }}" method="POST" enctype="multipart/form-data" id="lapanganForm">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Lapangan</h3>
            </div>

            <div class="p-6 space-y-6">
                <!-- Nama Lapangan -->
                <div>
                    <label for="nama_lapangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lapangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_lapangan" 
                           name="nama_lapangan" 
                           value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('nama_lapangan') border-red-500 @enderror"
                           placeholder="Contoh: Lapangan Futsal A"
                           required>
                    @error('nama_lapangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Lapangan -->
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Lapangan <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis" 
                            name="jenis" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('jenis') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Jenis Lapangan</option>
                        <option value="futsal" {{ old('jenis', $lapangan->jenis) == 'futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="basket" {{ old('jenis', $lapangan->jenis) == 'basket' ? 'selected' : '' }}>Basket</option>
                        <option value="badminton" {{ old('jenis', $lapangan->jenis) == 'badminton' ? 'selected' : '' }}>Badminton</option>
                        <option value="tenis" {{ old('jenis', $lapangan->jenis) == 'tenis' ? 'selected' : '' }}>Tenis</option>
                    </select>
                    @error('jenis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga per Jam -->
                <div>
                    <label for="harga_per_jam" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga per Jam <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                        <input type="number" 
                               id="harga_per_jam" 
                               name="harga_per_jam" 
                               value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}"
                               min="0"
                               step="1000"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('harga_per_jam') border-red-500 @enderror"
                               placeholder="100000"
                               required>
                    </div>
                    @error('harga_per_jam')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Masukkan harga dalam Rupiah (tanpa titik atau koma)</p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Lapangan
                    </label>
                    <textarea id="deskripsi" 
                              name="deskripsi" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi detail tentang lapangan, fasilitas yang tersedia, dll.">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Saat Ini -->
                @if($lapangan->gambar && count($lapangan->gambar) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Saat Ini
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4">
                        @foreach($lapangan->gambar as $index => $imagePath)
                        <div class="relative group">
                            <img src="{{ Storage::url($imagePath) }}" 
                                 alt="Gambar {{ $index + 1 }}"
                                 class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <div class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                {{ $index + 1 }}
                            </div>
                            <button type="button" 
                                    onclick="removeExistingImage('{{ $imagePath }}', this)"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-times"></i>
                            </button>
                            <input type="hidden" name="existing_images[]" value="{{ $imagePath }}" class="existing-image-input">
                        </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-500">Klik tombol X untuk menghapus gambar</p>
                </div>
                @endif

                <!-- Upload Gambar Baru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tambah Gambar Baru
                    </label>
                    <div class="border-2 border-gray-300 border-dashed rounded-lg p-6">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                    <span>Upload gambar</span>
                                    <input id="gambar" name="gambar[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewNewImages(this)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF sampai 2MB (maksimal 5 file total)</p>
                        </div>
                    </div>
                    @error('gambar.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Preview Gambar Baru -->
                    <div id="newImagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 hidden"></div>
                </div>

                <!-- Status -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $lapangan->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Lapangan aktif dan dapat dipesan
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('lapangan.show', $lapangan) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-teal-600 border border-transparent rounded-md font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Perbarui Lapangan
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
function removeExistingImage(imagePath, button) {
    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        const container = button.closest('.relative');
        const input = container.querySelector('.existing-image-input');
        
        // Remove the input so it won't be submitted
        input.remove();
        
        // Hide the image container
        container.style.display = 'none';
    }
}

function previewNewImages(input) {
    const preview = document.getElementById('newImagePreview');
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        
        // Get current existing images count
        const existingImagesCount = document.querySelectorAll('.existing-image-input').length;
        const totalAllowed = 5 - existingImagesCount;
        
        // Limit new files based on existing images
        const files = Array.from(input.files).slice(0, totalAllowed);
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" 
                             class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        <button type="button" 
                                onclick="removeNewPreviewImage(this, ${index})"
                                class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                            Baru ${index + 1}
                        </div>
                    `;
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        if (input.files.length > totalAllowed) {
            alert(`Maksimal ${totalAllowed} gambar baru yang dapat ditambahkan (sudah ada ${existingImagesCount} gambar)`);
        }
    } else {
        preview.classList.add('hidden');
    }
}

function removeNewPreviewImage(button, index) {
    const input = document.getElementById('gambar');
    const dt = new DataTransfer();
    
    // Rebuild file list without the removed file
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) {
            dt.items.add(input.files[i]);
        }
    }
    
    input.files = dt.files;
    previewNewImages(input);
}

// Format harga input
document.getElementById('harga_per_jam').addEventListener('input', function(e) {
    // Remove non-numeric characters
    let value = e.target.value.replace(/[^\d]/g, '');
    e.target.value = value;
});

// Form validation
document.getElementById('lapanganForm').addEventListener('submit', function(e) {
    const namaLapangan = document.getElementById('nama_lapangan').value.trim();
    const jenis = document.getElementById('jenis').value;
    const harga = document.getElementById('harga_per_jam').value;
    
    if (!namaLapangan) {
        e.preventDefault();
        alert('Nama lapangan harus diisi');
        document.getElementById('nama_lapangan').focus();
        return;
    }
    
    if (!jenis) {
        e.preventDefault();
        alert('Jenis lapangan harus dipilih');
        document.getElementById('jenis').focus();
        return;
    }
    
    if (!harga || harga <= 0) {
        e.preventDefault();
        alert('Harga per jam harus diisi dan lebih dari 0');
        document.getElementById('harga_per_jam').focus();
        return;
    }
});
</script>
@endpush