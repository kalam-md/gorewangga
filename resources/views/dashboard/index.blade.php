@extends('layout.main')
@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <!-- Overview Title -->
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Dashboard Overview</h2>
    
    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Users -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Penyewa</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ number_format($stats['total_users'] ?? 0) }}</p>
                </div>
                <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-teal-100 text-teal-600">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-500 font-medium">+12.5%</span>
                <span class="text-gray-500 ml-1">dari bulan lalu</span>
            </div>
        </div>

        <!-- Card 2: Total Pemesanan -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pemesanan</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ number_format($stats['total_pemesanan'] ?? 0) }}</p>
                </div>
                <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-500 font-medium">+8.1%</span>
                <span class="text-gray-500 ml-1">dari minggu lalu</span>
            </div>
        </div>

        <!-- Card 3: Revenue -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-500 font-medium">+15.4%</span>
                <span class="text-gray-500 ml-1">dari bulan lalu</span>
            </div>
        </div>

        <!-- Card 4: Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pemesanan Pending</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ number_format($stats['pemesanan_pending'] ?? 0) }}</p>
                </div>
                <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-red-500 font-medium">-3.2%</span>
                <span class="text-gray-500 ml-1">dari minggu lalu</span>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Pemesanan per Bulan</h3>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="ordersLineChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pemesanan per Jenis Lapangan</h3>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="fieldBarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Pemesanan Terbaru</h3>
                <a href="{{ route('laporan.index') }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">ID Pemesanan</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Penyewa</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Lapangan</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Total</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="font-medium text-gray-900">{{ $order->user->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->no_telp }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="font-medium text-gray-900">{{ $order->lapangan->nama_lapangan }}</div>
                            <div class="text-xs text-gray-500">{{ $order->lapangan->jenis_label }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $order->tanggal_pemesanan->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $order->total_harga_formatted }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_badge['class'] }}">
                                <i class="fas {{ $order->status_badge['icon'] }} mr-1"></i>
                                {{ $order->status_badge['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="#" onclick="viewDetail({{ $order->id }})" class="text-teal-600 hover:text-teal-900">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                <p>Belum ada pemesanan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variabel untuk menyimpan instance chart
let ordersChart = null;
let fieldChart = null;

// Fungsi untuk menginisialisasi chart
function initializeCharts() {
    // Pastikan data chart tersedia
    const chartData = @json($chartData ?? []);
    const fieldData = @json($fieldData ?? []);

    // Hancurkan chart sebelumnya jika ada
    if (ordersChart) {
        ordersChart.destroy();
    }
    if (fieldChart) {
        fieldChart.destroy();
    }

    // Chart 1: Line Chart - Tren Pemesanan per Bulan
    const ctx1 = document.getElementById('ordersLineChart');
    if (ctx1 && chartData && chartData.length > 0) {
        ordersChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.label || 'N/A'),
                datasets: [{
                    label: 'Pemesanan',
                    data: chartData.map(item => item.value || 0),
                    borderColor: '#0d9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0d9488',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2]
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    } else if (ctx1) {
        // Jika tidak ada data, tampilkan pesan
        ctx1.style.display = 'none';
        ctx1.parentElement.innerHTML += '<p class="text-center text-gray-500 py-8">Belum ada data pemesanan</p>';
    }

    // Chart 2: Doughnut Chart - Pemesanan per Jenis Lapangan
    const ctx2 = document.getElementById('fieldBarChart');
    if (ctx2 && fieldData && fieldData.length > 0) {
        // Warna untuk setiap jenis lapangan
        const fieldColors = {
            'futsal': '#10B981',
            'basket': '#F59E0B', 
            'badminton': '#8B5CF6',
            'tenis': '#EF4444'
        };

        const fieldLabels = {
            'futsal': 'Futsal',
            'basket': 'Basket',
            'badminton': 'Badminton',
            'tenis': 'Tenis'
        };

        fieldChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: fieldData.map(item => fieldLabels[item.jenis] || item.jenis),
                datasets: [{
                    data: fieldData.map(item => item.total || 0),
                    backgroundColor: fieldData.map(item => fieldColors[item.jenis] || '#6B7280'),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    } else if (ctx2) {
        // Jika tidak ada data, tampilkan pesan
        ctx2.style.display = 'none';
        ctx2.parentElement.innerHTML += '<p class="text-center text-gray-500 py-8">Belum ada data lapangan</p>';
    }
}

// Inisialisasi chart saat dokumen siap
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

// Function untuk view detail
function viewDetail(orderId) {
    alert('Detail pemesanan #' + orderId + '\n\nFitur ini bisa dihubungkan ke halaman detail pemesanan.');
}
</script>
@endpush