<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>GOR EWANGGA - DASHBOARD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">
  
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex md:flex-col w-64 bg-teal-800 text-white">
        <div class="flex items-center justify-center h-16 bg-teal-900">
            <span class="text-xl font-bold">GOR Ewangga</span>
        </div>
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-3 py-2 bg-teal-700 rounded-md">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Pengguna</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-box w-5 text-center"></i>
                <span>Lapangan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span>Jadwal</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-cog w-5 text-center"></i>
                <span>Laporan</span>
            </a>
        </nav>
        <div class="p-4 border-t border-teal-700 flex items-center">
            <img class="w-10 h-10 rounded-full" src="https://placehold.co/400" alt="User">
            <div class="ml-3">
                <p class="text-sm font-medium">Admin User</p>
                <p class="text-xs text-teal-200">admin@example.com</p>
            </div>
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
            <a href="#" class="flex items-center gap-3 px-3 py-2 bg-teal-700 rounded-md">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Pengguna</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-box w-5 text-center"></i>
                <span>Lapangan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span>Jadwal</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-teal-700 rounded-md">
                <i class="fas fa-cog w-5 text-center"></i>
                <span>Laporan</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 overflow-hidden">

        <!-- Top Navbar -->
        <header class="flex items-center justify-between h-16 px-4 bg-white border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <button id="sidebarToggle" class="md:hidden p-2 text-gray-500 rounded-md hover:bg-gray-100">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
            </div>
            <div class="flex items-center space-x-4">
                <button class="p-2 text-gray-500 rounded-full hover:bg-gray-100">
                    <i class="fas fa-bell"></i>
                </button>
                <div class="flex items-center space-x-2">
                    <img class="w-8 h-8 rounded-full" src="https://placehold.co/400" alt="User">
                    <span class="hidden md:inline">Admin</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-4">
            
            <!-- Overview Title -->
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Overview</h2>
            
            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Users</p>
                            <p class="text-2xl font-semibold text-gray-800">1,254</p>
                        </div>
                        <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-teal-100 text-teal-600">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm">
                        <span class="text-green-500 font-medium">+12.5%</span>
                        <span class="text-gray-500 ml-1">from last month</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">New Orders</p>
                            <p class="text-2xl font-semibold text-gray-800">320</p>
                        </div>
                        <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm">
                        <span class="text-green-500 font-medium">+8.1%</span>
                        <span class="text-gray-500 ml-1">from last week</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Revenue</p>
                            <p class="text-2xl font-semibold text-gray-800">$12,450</p>
                        </div>
                        <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm">
                        <span class="text-green-500 font-medium">+15.4%</span>
                        <span class="text-gray-500 ml-1">from last month</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Support Tickets</p>
                            <p class="text-2xl font-semibold text-gray-800">89</p>
                        </div>
                        <div class="w-[50px] h-[50px] flex justify-center items-center rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-life-ring"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm">
                        <span class="text-red-500 font-medium">-3.2%</span>
                        <span class="text-gray-500 ml-1">from last week</span>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Pemesanan per Bulan</h3>
                    <canvas id="ordersLineChart" height="150"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pemesanan per Lapangan</h3>
                    <canvas id="fieldBarChart" height="150"></canvas>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Order ID</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Customer</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Date</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Amount</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Status</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">#ORD-1234</td>
                                <td class="px-6 py-4 text-sm text-gray-500">John Doe</td>
                                <td class="px-6 py-4 text-sm text-gray-500">2023-05-15</td>
                                <td class="px-6 py-4 text-sm text-gray-500">$245.00</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="#" class="text-teal-600 hover:text-teal-900">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <button class="text-teal-600 hover:text-teal-800 text-sm font-medium">View all orders</button>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('mobileSidebar');
        document.getElementById('sidebarToggle').addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
    });
    document.getElementById('closeSidebar').addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
    });

    // Line Chart
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

    // Bar Chart
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
</script>

</body>
</html>
