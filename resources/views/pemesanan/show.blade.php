@extends('layout.main')

@section('page-title', 'Detail Pemesanan')

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
                <h2 class="text-xl font-semibold text-gray-800">Detail Pemesanan</h2>
                <p class="text-gray-600 mt-1">Informasi lengkap pemesanan lapangan</p>
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">ID Pemesanan: #{{ $pemesanan->id }}</h3>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Pemesanan -->
            <div>
                <h4 class="text-md font-semibold text-gray-800 mb-4">Informasi Pemesanan</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Lapangan</p>
                        <div class="flex items-center mt-1">
                            <div class="h-10 w-10 flex-shrink-0">
                                <img class="h-10 w-10 rounded-lg object-cover" 
                                     src="{{ $pemesanan->lapangan->gambar_utama }}" 
                                     alt="{{ $pemesanan->lapangan->nama_lapangan }}"
                                     onerror="this.src='https://placehold.co/40x40/e5e7eb/6b7280?text=No+Image'">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $pemesanan->lapangan->nama_lapangan }}</p>
                                <p class="text-sm text-gray-500">{{ $pemesanan->lapangan->jenis_label }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">
                            {{ $pemesanan->tanggal_pemesanan->format('l, d F Y') }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Jam</p>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach(json_decode($pemesanan->jadwal) as $jam)
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">{{ $jam }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Durasi</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">
                            {{ count(json_decode($pemesanan->jadwal) ) }} jam
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Total Harga</p>
                        <p class="text-lg font-semibold text-teal-600 mt-1">
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($pemesanan->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($pemesanan->status == 'sukses') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($pemesanan->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div>
                <h4 class="text-md font-semibold text-gray-800 mb-4">Informasi Pembayaran</h4>
                
                <div class="space-y-4">
                    @if($pemesanan->pembayaran)
                        <div>
                            <p class="text-sm text-gray-500">Metode Pembayaran</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                {{ $pemesanan->pembayaran->metode ?? '-' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Status Pembayaran</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($pemesanan->pembayaran->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($pemesanan->pembayaran->status == 'valid') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($pemesanan->pembayaran->status) }}
                                </span>
                            </p>
                        </div>
                        
                        @if($pemesanan->pembayaran->tanggal_bayar)
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Pembayaran</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                {{ $pemesanan->pembayaran->tanggal_bayar->format('d M Y H:i') }}
                            </p>
                        </div>
                        @endif
                        
                        @if($pemesanan->pembayaran->bukti_transfer)
                        <div>
                            <p class="text-sm text-gray-500">Bukti Transfer</p>
                            <div class="mt-1">
                                <img src="{{ asset('storage/' . $pemesanan->pembayaran->bukti_transfer) }}" 
                                     alt="Bukti Transfer"
                                     class="h-32 rounded-lg border border-gray-200 cursor-pointer"
                                     onclick="window.open('{{ asset('storage/' . $pemesanan->pembayaran->bukti_transfer) }}', '_blank')">
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-2xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">Belum ada informasi pembayaran</p>
                        </div>
                    @endif
                </div>
                
                <!-- Update Status Form (for admin) -->
                @can('update', $pemesanan)
                <div class="mt-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Update Status</h4>
                    
                    <form action="{{ route('pemesanan.update', $pemesanan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="flex items-center space-x-4">
                            <select name="status" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                <option value="pending" {{ $pemesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="sukses" {{ $pemesanan->status == 'sukses' ? 'selected' : '' }}>Sukses</option>
                                <option value="gagal" {{ $pemesanan->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
                            </select>
                            
                            <button type="submit" 
                                    class="px-4 py-2 bg-teal-600 border border-transparent rounded-md font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
                @endcan
                
                <!-- Upload Bukti Transfer (for user) -->
                @if(auth()->user()->id === $pemesanan->user_id && $pemesanan->status === 'pending' && in_array($pemesanan->pembayaran->metode, ['Transfer Bank', 'E-Wallet']))
                <div class="mt-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Upload Bukti Transfer</h4>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div class="border-2 border-gray-300 border-dashed rounded-lg p-6">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="bukti_transfer" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                            <span>Upload bukti transfer</span>
                                            <input id="bukti_transfer" name="bukti_transfer" type="file" class="sr-only" accept="image/*" required>
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF sampai 2MB</p>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-teal-600 border border-transparent rounded-md font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Upload Bukti
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection