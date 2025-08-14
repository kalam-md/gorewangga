<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GOR EWANGGA - DASHBOARD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- Tambahkan modal konfirmasi logout -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Logout</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin logout?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelLogout" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md">
                    Batal
                </button>
                <button id="confirmLogoutBtn" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md">
                    Logout
                </button>
            </div>
        </div>
    </div>
  
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex md:flex-col w-64 bg-teal-800 text-white">
        <div class="flex items-center justify-center h-16 bg-teal-900">
            <span class="text-xl font-bold">GOR Ewangga</span>
        </div>
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('dashboard') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            @if(auth()->user()->role === 'petugas')
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('users.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Pengguna</span>
            </a>
            <a href="{{ route('lapangan.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('lapangan.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-map w-5 text-center"></i>
                <span>Lapangan</span>
            </a>
            @endif
            <a href="{{ route('jadwal.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('jadwal.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-calendar-alt w-5 text-center"></i>
                <span>Jadwal</span>
            </a>
            <a href="{{ route('pemesanan.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('pemesanan.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-tags w-5 text-center"></i>
                <span>Pemesanan</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('laporan.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-cog w-5 text-center"></i>
                <span>Laporan</span>
            </a>
        </nav>
        <!-- User Profile Section -->
        <div class="p-4 border-t border-teal-700">
            <div class="flex items-center mb-3">
                <img class="w-10 h-10 rounded-full" src="https://placehold.co/400" alt="User">
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-xs text-teal-200">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <!-- Logout Button -->
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="button" onclick="confirmLogout()" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-center rounded-md transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Sidebar -->
    <aside id="mobileSidebar" class="fixed inset-y-0 left-0 w-64 bg-teal-800 text-white transform -translate-x-full transition-transform duration-300 z-50 md:hidden">
        <div class="flex items-center justify-between h-16 px-4 bg-teal-900">
            <span class="text-xl font-bold">GOR Ewangga</span>
            <button id="closeSidebar" class="w-[35px] h-[35px] flex justify-center items-center rounded-full hover:bg-teal-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('dashboard') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            @if(auth()->user()->role === 'petugas')
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('users.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Pengguna</span>
            </a>
            <a href="{{ route('lapangan.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('lapangan.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-map w-5 text-center"></i>
                <span>Lapangan</span>
            </a>
            @endif
            <a href="{{ route('jadwal.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('jadwal.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-calendar-alt w-5 text-center"></i>
                <span>Jadwal</span>
            </a>
            <a href="{{ route('pemesanan.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('pemesanan.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-tags w-5 text-center"></i>
                <span>Pemesanan</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('laporan.*') ? 'bg-teal-700' : 'hover:bg-teal-700' }} rounded-md">
                <i class="fas fa-cog w-5 text-center"></i>
                <span>Laporan</span>
            </a>
        </nav>
        <!-- Mobile User Profile Section -->
        <div class="p-4 border-t border-teal-700">
            <div class="flex items-center mb-3">
                <img class="w-10 h-10 rounded-full" src="https://placehold.co/400" alt="User">
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-xs text-teal-200">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <!-- Mobile Logout Button -->
            <button type="button" onclick="confirmLogout()" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-center rounded-md transition-colors">
                Logout
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 overflow-hidden">

        <!-- Top Navbar -->
        <header class="flex items-center justify-between h-16 px-4 bg-white border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <button id="sidebarToggle" class="md:hidden p-2 text-gray-500 rounded-md hover:bg-gray-100">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
            <div class="flex items-center space-x-4">
                <button class="p-2 text-gray-500 rounded-full hover:bg-gray-100">
                    <i class="fas fa-bell"></i>
                </button>
                <div class="flex items-center space-x-2">
                    <img class="w-8 h-8 rounded-full" src="https://placehold.co/400" alt="User">
                    <span class="hidden md:inline">{{ Auth::user()->nama_lengkap }}</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        @yield('content')
    </div>
</div>

<script>
    // Sidebar functionality
    const sidebar = document.getElementById('mobileSidebar');
    document.getElementById('sidebarToggle').addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
    });
    document.getElementById('closeSidebar').addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
    });

    // Logout functionality
    function confirmLogout() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden');
    }

    // Add event listeners after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        const cancelBtn = document.getElementById('cancelLogout');
        const confirmBtn = document.getElementById('confirmLogoutBtn');
        const modal = document.getElementById('logoutModal');
        const logoutForm = document.getElementById('logoutForm');

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        confirmBtn.addEventListener('click', () => {
            logoutForm.submit();
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target.id === 'logoutModal') {
                modal.classList.add('hidden');
            }
        });
    });

    // Charts code tetap sama
    // Line Chart
    if (document.getElementById('ordersLineChart')) {
        new Chart(document.getElementById('ordersLineChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: [45, 60, 50, 80, 90, 70, 100],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            }
        });
    }

    // Bar Chart
    if (document.getElementById('fieldBarChart')) {
        new Chart(document.getElementById('fieldBarChart'), {
            type: 'bar',
            data: {
                labels: ['Lapangan A', 'Lapangan B', 'Lapangan C', 'Lapangan D'],
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: [120, 90, 75, 60],
                    backgroundColor: ['#2563eb', '#16a34a', '#f59e0b', '#dc2626']
                }]
            }
        });
    }
</script>

@stack('scripts')

</body>
</html>