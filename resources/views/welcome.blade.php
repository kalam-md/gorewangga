{{-- HALAMAN LANDING PAGE --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gor Ewangga - Booking Lapangan Olahraga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        .hero-bg {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(45, 212, 191, 0.8)), 
                        url('/img-home/home.jpeg') no-repeat;
            background-size: cover;
            background-position: center;
        }
        .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151 !important;
        }
        .fc-button {
            background-color: white;
            border: 1px solid #d1d5db !important;
            color: #374151;
            padding: 0.4rem 0.8rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }
        .fc-button:hover {
            background-color: #f3f4f6 !important;
        }
        .fc-button-primary {
            background-color: #0d9488 !important;
            border-color: #0d9488 !important;
            color: white;
        }
        .fc-button-primary:hover {
            background-color: #0f766e !important;
        }
        .fc-event {
            border-radius: 0.375rem;
            padding: 2px 4px;
            cursor: pointer;
            font-size: 0.875rem;
        }
        .fc-event:hover {
            opacity: 0.8;
        }
        .fc-daygrid-event {
            border-radius: 4px;
        }
        .fc-timegrid-event {
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-teal-600">
                            <i class="fas fa-futbol mr-2"></i>
                            Gor Ewangga
                        </h1>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="#hero" class="text-gray-700 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium scroll-link">Beranda</a>
                            <a href="#about" class="text-gray-700 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium scroll-link">Tentang</a>
                            <a href="#jadwal" class="text-gray-700 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium scroll-link">Jadwal</a>
                            <a href="#kontak" class="text-gray-700 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium scroll-link">Kontak</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6 space-x-3">
                        <a href="/login" class="bg-white text-teal-600 border border-teal-600 hover:bg-teal-50 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Login
                        </a>
                        <a href="/register" class="bg-teal-600 text-white hover:bg-teal-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Register
                        </a>
                    </div>
                </div>
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-gray-700 hover:text-teal-600 focus:outline-none focus:text-teal-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" id="mobile-menu" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
                <a href="#hero" class="text-gray-700 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium scroll-link">Beranda</a>
                <a href="#about" class="text-gray-700 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium scroll-link">Tentang</a>
                <a href="#jadwal" class="text-gray-700 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium scroll-link">Jadwal</a>
                <a href="#kontak" class="text-gray-700 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium scroll-link">Kontak</a>
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <a href="/login" class="block w-full text-center bg-white text-teal-600 border border-teal-600 hover:bg-teal-50 px-4 py-2 rounded-md text-sm font-medium mb-2 transition-colors">
                        Login
                    </a>
                    <a href="/register" class="block w-full text-center bg-teal-600 text-white hover:bg-teal-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="hero-bg min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Booking Lapangan Olahraga
                    <span class="block text-yellow-300">Mudah & Cepat</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-100">
                    Temukan dan pesan lapangan olahraga favorit Anda dengan sistem booking online yang praktis dan terpercaya
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Mulai Booking
                    </a>
                    <a href="#jadwal" class="bg-transparent border-2 border-white hover:bg-white hover:text-teal-600 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors scroll-link">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Jadwal
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Tentang Gor Ewangga</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Pusat olahraga terlengkap dengan fasilitas modern dan sistem booking online yang memudahkan Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Mengapa Memilih Kami?</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-teal-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Booking Aman & Terpercaya</h4>
                                <p class="text-gray-600">Sistem booking online yang aman dengan konfirmasi pembayaran langsung</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-teal-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">24/7 Akses Booking</h4>
                                <p class="text-gray-600">Pesan kapan saja, dimana saja melalui platform online kami</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-star text-teal-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Fasilitas Premium</h4>
                                <p class="text-gray-600">Lapangan berkualitas tinggi dengan perawatan terbaik</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-first md:order-last">
                    <img src="/img-home/home.jpeg" 
                         alt="Sports facility" 
                         class="rounded-lg shadow-xl w-full">
                </div>
            </div>

            <!-- Sports Types -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-futbol text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Futsal</h4>
                    <p class="text-gray-600 text-sm mt-2">Lapangan futsal indoor dengan rumput sintetis berkualitas</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-basketball-ball text-orange-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Basket</h4>
                    <p class="text-gray-600 text-sm mt-2">Lapangan basket indoor dengan standar internasional</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-table-tennis text-purple-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Badminton</h4>
                    <p class="text-gray-600 text-sm mt-2">Lapangan badminton dengan kualitas lantai terbaik</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tennis-ball text-red-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Tenis</h4>
                    <p class="text-gray-600 text-sm mt-2">Lapangan tenis outdoor dengan surface berkualitas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Schedule Section -->
    <section id="jadwal" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Jadwal Lapangan</h2>
                <p class="text-xl text-gray-600">
                    Lihat jadwal booking lapangan yang telah terisi dan temukan waktu yang tersedia
                </p>
            </div>

            <!-- Calendar Legend -->
            <div class="mb-8 p-6 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Keterangan Warna:</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Futsal</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Basket</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Badminton</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Tenis</span>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div id="calendar"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-16 bg-teal-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap untuk Mulai Booking?</h2>
                <p class="text-xl mb-8 text-teal-100">
                    Daftar sekarang dan nikmati kemudahan booking lapangan olahraga
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Sekarang
                    </a>
                    <a href="tel:+628123456789" class="bg-transparent border-2 border-white hover:bg-white hover:text-teal-600 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                        <i class="fas fa-phone mr-2"></i>
                        Hubungi Kami
                    </a>
                </div>

                <!-- Contact Info -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Alamat</h4>
                        <p class="text-teal-100">Jl. Olahraga No. 123<br>South Tangerang, Banten</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Telepon</h4>
                        <p class="text-teal-100">+62 812-3456-7890</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Email</h4>
                        <p class="text-teal-100">info@Gor Ewangga.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4">
                    <i class="fas fa-futbol mr-2"></i>
                    Gor Ewangga
                </h3>
                <p class="text-gray-400 mb-4">
                    Platform booking lapangan olahraga terpercaya di Indonesia
                </p>
                <div class="flex justify-center space-x-6 mb-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>
                </div>
                <div class="border-t border-gray-700 pt-6">
                    <p class="text-gray-400">
                        © 2025 Gor Ewangga. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal for Event Details -->
    <div id="eventDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg max-w-lg w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Booking</h3>
                <button onclick="closeEventDetail()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6" id="eventDetailContent">
                <!-- Event details will be populated here -->
            </div>
            
            <div class="flex justify-end px-6 py-4 border-t border-gray-200">
                <button onclick="closeEventDetail()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="calendarLoading" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-4 rounded-lg shadow-lg z-50 hidden">
        <div class="flex items-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-teal-600 mr-3"></div>
            <span class="text-gray-700">Memuat jadwal...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.min.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.style.display = mobileMenu.style.display === 'none' ? 'block' : 'none';
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('.scroll-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu if open
                document.getElementById('mobile-menu').style.display = 'none';
            });
        });

        // Initialize FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const loadingEl = document.getElementById('calendarLoading');
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                nowIndicator: true,
                navLinks: true,
                dayMaxEvents: 3, // batasi event per hari di month view
                moreLinkText: function(num) {
                    return '+ ' + num + ' lainnya';
                },
                height: 'auto',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                loading: function(isLoading) {
                    if (isLoading) {
                        loadingEl.classList.remove('hidden');
                    } else {
                        loadingEl.classList.add('hidden');
                    }
                },
                events: function(info, successCallback, failureCallback) {
                    fetch('/public/jadwal/events', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        successCallback(data);
                    })
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        // Fallback ke data demo jika API gagal
                        const demoEvents = [
                            {
                                id: 'demo-1',
                                title: 'Data tidak dapat dimuat',
                                start: new Date().toISOString().split('T')[0] + 'T08:00:00',
                                end: new Date().toISOString().split('T')[0] + 'T10:00:00',
                                backgroundColor: '#6B7280',
                                borderColor: '#6B7280',
                                textColor: '#ffffff',
                                extendedProps: {
                                    lapangan: 'Sistem tidak dapat terhubung',
                                    jenis: 'Error',
                                    penyewa: 'Silahkan coba lagi',
                                    phone: '-',
                                    total_harga: '-',
                                    metode_bayar: '-',
                                    jadwal_detail: '-',
                                    durasi: '-'
                                }
                            }
                        ];
                        successCallback(demoEvents);
                        failureCallback(error);
                    });
                },
                eventClick: function(info) {
                    showEventDetail(info.event);
                },
                eventDidMount: function(info) {
                    // Tambahkan tooltip
                    if (info.event.extendedProps.lapangan && info.event.extendedProps.penyewa) {
                        info.el.setAttribute('title', 
                            info.event.extendedProps.lapangan + ' - ' + 
                            info.event.extendedProps.penyewa
                        );
                    }
                }
            });

            calendar.render();
        });

        function showEventDetail(event) {
            const modal = document.getElementById('eventDetailModal');
            const content = document.getElementById('eventDetailContent');
            
            const props = event.extendedProps;
            
            let startTime = '-';
            let endTime = '-';
            let eventDate = '-';
            
            try {
                if (event.start) {
                    startTime = event.start.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                    eventDate = event.start.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                }
                if (event.end) {
                    endTime = event.end.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                }
            } catch (e) {
                console.error('Error formatting dates:', e);
            }

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-map-marker-alt text-teal-600 mr-2"></i>
                            Informasi Lapangan
                        </h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div><span class="font-medium">Lapangan:</span> ${props.lapangan || '-'}</div>
                            <div><span class="font-medium">Jenis:</span> ${props.jenis || '-'}</div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-clock text-blue-600 mr-2"></i>
                            Jadwal
                        </h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div><span class="font-medium">Tanggal:</span> ${eventDate}</div>
                            <div><span class="font-medium">Waktu:</span> ${startTime} - ${endTime}</div>
                            <div><span class="font-medium">Durasi:</span> ${props.durasi || '-'}</div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            Informasi Penyewa
                        </h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div><span class="font-medium">Nama:</span> ${props.penyewa || '-'}</div>
                            <div><span class="font-medium">No. Telp:</span> ${props.phone || '-'}</div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-credit-card text-yellow-600 mr-2"></i>
                            Pembayaran
                        </h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div><span class="font-medium">Total:</span> ${props.total_harga || '-'}</div>
                            <div><span class="font-medium">Metode:</span> ${props.metode_bayar || '-'}</div>
                            <div><span class="font-medium text-green-600">Status: Lunas</span></div>
                        </div>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
        }

        function closeEventDetail() {
            document.getElementById('eventDetailModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('eventDetailModal').addEventListener('click', function(e) {
            if (e.target.id === 'eventDetailModal') {
                closeEventDetail();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEventDetail();
            }
        });

        // Add active navbar highlight on scroll
        window.addEventListener('scroll', function() {
            const sections = ['hero', 'about', 'jadwal', 'kontak'];
            const navLinks = document.querySelectorAll('.scroll-link');
            
            let current = '';
            sections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) {
                    const sectionTop = section.offsetTop - 100;
                    if (pageYOffset >= sectionTop) {
                        current = sectionId;
                    }
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('text-teal-600');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('text-teal-600');
                }
            });
        });
    </script>
</body>
</html>