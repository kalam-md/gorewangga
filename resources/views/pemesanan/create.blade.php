@extends('layout.main')

@section('page-title', 'Buat Pemesanan')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('pemesanan.index') }}" 
               class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Buat Pemesanan Baru</h2>
                <p class="text-gray-600 mt-1">Isi formulir untuk memesan lapangan</p>
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
        <form action="{{ route('pemesanan.store') }}" method="POST" id="pemesananForm">
            @csrf
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Pemesanan</h3>
            </div>

            <div class="p-6 space-y-6">
                <!-- Lapangan -->
                <div>
                    <label for="lapangan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Lapangan <span class="text-red-500">*</span>
                    </label>
                    <select id="lapangan_id" 
                            name="lapangan_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('lapangan_id') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Lapangan</option>
                        @foreach($lapangans as $lapangan)
                            <option value="{{ $lapangan->id }}" 
                                data-harga="{{ $lapangan->harga_per_jam }}"
                                {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>
                                {{ $lapangan->nama_lapangan }} ({{ ucfirst($lapangan->jenis) }}) - Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam
                            </option>
                        @endforeach
                    </select>
                    @error('lapangan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pemesanan -->
                <div>
                    <label for="tanggal_pemesanan" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Pemesanan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="tanggal_pemesanan" 
                           name="tanggal_pemesanan" 
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('tanggal_pemesanan') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('tanggal_pemesanan') border-red-500 @enderror"
                           required>
                    @error('tanggal_pemesanan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jadwal Tersedia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Jam <span class="text-red-500">*</span>
                    </label>
                    <div id="jadwalContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach($jadwals as $jadwal)
                            <div class="relative">
                                <input type="checkbox" 
                                    id="jadwal_{{ $jadwal->id }}" 
                                    name="jadwal[]" 
                                    value="{{ $jadwal->id }}"
                                    class="hidden peer jadwal-checkbox"
                                    {{ in_array($jadwal->id, old('jadwal', [])) ? 'checked' : '' }}>
                                <label for="jadwal_{{ $jadwal->id }}" 
                                    class="block p-3 border border-gray-300 rounded-lg cursor-pointer peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-700 hover:bg-gray-50 transition-colors text-center">
                                    {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('jadwal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Pilih satu atau lebih jam yang tersedia</p>
                </div>

                <!-- Total Harga -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Total Harga:</span>
                        <span id="totalHarga" class="text-xl font-semibold text-teal-600">Rp 0</span>
                    </div>
                    <input type="hidden" id="harga_per_jam" value="0">
                </div>

                <!-- Pembayaran -->
                <div>
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select id="metode_pembayaran" 
                            name="metode_pembayaran" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('metode_pembayaran') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="E-Wallet" {{ old('metode_pembayaran') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                    </select>
                    @error('metode_pembayaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('pemesanan.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-teal-600 border border-transparent rounded-md font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Buat Pemesanan
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lapanganSelect = document.getElementById('lapangan_id');
    const tanggalInput = document.getElementById('tanggal_pemesanan');
    const jadwalContainer = document.getElementById('jadwalContainer');
    const hargaPerJamInput = document.getElementById('harga_per_jam');
    const totalHargaSpan = document.getElementById('totalHarga');
    const jadwalCheckboxes = document.querySelectorAll('.jadwal-checkbox');
    
    // Update harga per jam when lapangan changes
    lapanganSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga') || 0;
        hargaPerJamInput.value = harga;
        calculateTotal();
        
        // Reset jadwal availability
        if (tanggalInput.value) {
            checkJadwalAvailability();
        }
    });
    
    // Check available schedules when date changes
    tanggalInput.addEventListener('change', function() {
        if (lapanganSelect.value) {
            checkJadwalAvailability();
        }
    });
    
    // Calculate total when jadwal is selected
    jadwalCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });
    
    // Check jadwal availability
    function checkJadwalAvailability() {
        const lapanganId = lapanganSelect.value;
        const tanggal = tanggalInput.value;
        
        if (lapanganId && tanggal) {
            // Show loading state
            jadwalContainer.style.opacity = '0.5';
            
            fetch(`{{ route('pemesanan.check-jadwal') }}?lapangan_id=${lapanganId}&tanggal=${tanggal}`)
                .then(response => response.json())
                .then(data => {
                    // Reset all checkboxes first
                    jadwalCheckboxes.forEach(checkbox => {
                        checkbox.disabled = false;
                        checkbox.checked = false;
                        const label = checkbox.nextElementSibling;
                        label.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                        label.classList.add('cursor-pointer');
                    });
                    
                    // Disable booked schedules
                    data.booked.forEach(jadwalId => {
                        const checkbox = document.getElementById(`jadwal_${jadwalId}`);
                        if (checkbox) {
                            checkbox.disabled = true;
                            const label = checkbox.nextElementSibling;
                            label.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                            label.classList.remove('cursor-pointer');
                        }
                    });
                    
                    jadwalContainer.style.opacity = '1';
                    calculateTotal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    jadwalContainer.style.opacity = '1';
                    alert('Gagal mengecek ketersediaan jadwal');
                });
        }
    }
    
    // Calculate total price
    function calculateTotal() {
        const hargaPerJam = parseFloat(hargaPerJamInput.value) || 0;
        const selectedJadwals = document.querySelectorAll('.jadwal-checkbox:checked:not(:disabled)').length;
        const total = hargaPerJam * selectedJadwals;
        
        totalHargaSpan.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }
    
    // Initialize calculation if there are old inputs
    if (lapanganSelect.value) {
        const selectedOption = lapanganSelect.options[lapanganSelect.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga') || 0;
        hargaPerJamInput.value = harga;
        
        if (tanggalInput.value) {
            checkJadwalAvailability();
        } else {
            calculateTotal();
        }
    }
});
</script>
@endpush