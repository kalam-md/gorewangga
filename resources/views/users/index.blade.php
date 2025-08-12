@extends('layout.main')

@section('page-title', 'Manajemen Pengguna')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Manajemen Pengguna</h2>
        <p class="text-gray-600 mt-1">Kelola data pengguna sistem</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pengguna</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $users->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Penyewa</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $users->where('role', 'penyewa')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Petugas</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $users->where('role', 'petugas')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Data Pengguna</h3>
                <div class="mt-4 md:mt-0 flex items-center space-x-3">
                    <!-- Search Box -->
                    <div class="relative">
                        <input type="text" 
                               placeholder="Cari pengguna..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                               id="searchInput">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <!-- Filter Role -->
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" id="roleFilter">
                        <option value="">Semua Role</option>
                        <option value="penyewa">Penyewa</option>
                        <option value="petugas">Petugas</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 user-row" data-role="{{ $user->role }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full" src="https://placehold.co/40" alt="{{ $user->nama_lengkap }}">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->nama_lengkap }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->no_telp }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role === 'petugas')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-user-tie mr-1"></i>
                                    Petugas
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-user mr-1"></i>
                                    Penyewa
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showUserDetail({{ $user->id }})" 
                                    class="inline-flex items-center px-3 py-1 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded-md transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Tidak ada data pengguna</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</main>

<!-- Modal Detail User -->
<div id="userDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Pengguna</h3>
            <button onclick="closeUserDetail()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <!-- Loading State -->
            <div id="modalLoading" class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600"></div>
                <span class="ml-3 text-gray-600">Memuat data...</span>
            </div>
            
            <!-- User Detail Content -->
            <div id="userDetailContent" class="hidden">
                <div class="flex items-center mb-6">
                    <img id="userAvatar" class="w-20 h-20 rounded-full" src="https://placehold.co/80" alt="User Avatar">
                    <div class="ml-6">
                        <h4 id="userName" class="text-xl font-semibold text-gray-900"></h4>
                        <p id="userEmail" class="text-gray-600"></p>
                        <span id="userRole" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-2"></span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Informasi Pribadi</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Pengguna</label>
                                <p id="userId" class="mt-1 text-sm text-gray-900"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username</label>
                                <p id="userUsername" class="mt-1 text-sm text-gray-900"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <p id="userPhone" class="mt-1 text-sm text-gray-900"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Informasi Akun</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Daftar</label>
                                <p id="userCreatedAt" class="mt-1 text-sm text-gray-900"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                                <p id="userUpdatedAt" class="mt-1 text-sm text-gray-900"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end px-6 py-4 border-t border-gray-200">
            <button onclick="closeUserDetail()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showUserDetail(userId) {
    const modal = document.getElementById('userDetailModal');
    const loading = document.getElementById('modalLoading');
    const content = document.getElementById('userDetailContent');
    
    // Show modal and loading
    modal.classList.remove('hidden');
    loading.classList.remove('hidden');
    content.classList.add('hidden');
    
    // Fetch user data
    fetch(`/pengguna/${userId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading and show content
        loading.classList.add('hidden');
        content.classList.remove('hidden');
        
        // Populate modal with user data
        document.getElementById('userId').textContent = `#${String(data.id).padStart(4, '0')}`;
        document.getElementById('userName').textContent = data.nama_lengkap;
        document.getElementById('userUsername').textContent = data.username;
        document.getElementById('userEmail').textContent = data.email;
        document.getElementById('userPhone').textContent = data.no_telp;
        document.getElementById('userCreatedAt').textContent = data.created_at;
        document.getElementById('userUpdatedAt').textContent = data.updated_at;
        
        // Set role badge
        const roleElement = document.getElementById('userRole');
        if (data.role === 'petugas') {
            roleElement.className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800';
            roleElement.innerHTML = '<i class="fas fa-user-tie mr-1"></i>Petugas';
        } else {
            roleElement.className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800';
            roleElement.innerHTML = '<i class="fas fa-user mr-1"></i>Penyewa';
        }
        
        // Update avatar (you can customize this based on your needs)
        document.getElementById('userAvatar').src = `https://placehold.co/80/2dd4bf/ffffff?text=${data.nama_lengkap.charAt(0)}`;
    })
    .catch(error => {
        console.error('Error:', error);
        loading.innerHTML = '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi kesalahan saat memuat data</div>';
    });
}

function closeUserDetail() {
    document.getElementById('userDetailModal').classList.add('hidden');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Role filter functionality
document.getElementById('roleFilter').addEventListener('change', function() {
    const selectedRole = this.value;
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
        const userRole = row.getAttribute('data-role');
        if (selectedRole === '' || userRole === selectedRole) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Close modal when clicking outside
document.getElementById('userDetailModal').addEventListener('click', function(e) {
    if (e.target.id === 'userDetailModal') {
        closeUserDetail();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUserDetail();
    }
});
</script>
@endpush