@extends('layout.main')

@section('page-title', 'Kalendar Jadwal')

@section('content')
<main class="flex-1 overflow-y-auto p-4">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kalendar Jadwal</h2>
                <p class="text-gray-600 mt-1">Lihat jadwal pemesanan lapangan yang telah dikonfirmasi</p>
            </div>
            
            <!-- Legend untuk warna berdasarkan jenis lapangan -->
            <div class="mt-4 md:mt-0">
                <div class="flex flex-wrap gap-2">
                    <div class="flex items-center text-xs">
                        <div class="w-3 h-3 rounded mr-1" style="background-color: #10B981;"></div>
                        <span>Futsal</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <div class="w-3 h-3 rounded mr-1" style="background-color: #F59E0B;"></div>
                        <span>Basket</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <div class="w-3 h-3 rounded mr-1" style="background-color: #8B5CF6;"></div>
                        <span>Badminton</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <div class="w-3 h-3 rounded mr-1" style="background-color: #EF4444;"></div>
                        <span>Tenis</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Modal untuk detail event -->
    <div id="eventModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Detail Jadwal Pemesanan
                            </h3>
                            <div class="mt-4" id="modal-content">
                                <!-- Content will be filled by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="closeModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
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
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    let modal = document.getElementById('eventModal');
    let closeModalBtn = document.getElementById('closeModal');

    let calendar = new FullCalendar.Calendar(calendarEl, {
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
        events: function(info, successCallback, failureCallback) {
            fetch('/jadwal/events', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                successCallback(data);
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                failureCallback(error);
            });
        },
        eventClick: function(info) {
            showEventModal(info.event);
        },
        eventDidMount: function(info) {
            // Tambahkan tooltip
            info.el.setAttribute('title', 
                info.event.extendedProps.lapangan + ' - ' + 
                info.event.extendedProps.penyewa
            );
        }
    });

    calendar.render();

    // Function untuk menampilkan modal detail
    function showEventModal(event) {
        const props = event.extendedProps;
        const startTime = event.start.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
        const endTime = event.end.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
        const eventDate = event.start.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        document.getElementById('modal-content').innerHTML = `
            <div class="space-y-3">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Informasi Lapangan</h4>
                    <div class="grid grid-cols-1 gap-2 text-sm">
                        <div><span class="font-medium">Lapangan:</span> ${props.lapangan}</div>
                        <div><span class="font-medium">Jenis:</span> ${props.jenis}</div>
                    </div>
                </div>
                
                <div class="bg-blue-50 p-3 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Jadwal</h4>
                    <div class="grid grid-cols-1 gap-2 text-sm">
                        <div><span class="font-medium">Tanggal:</span> ${eventDate}</div>
                        <div><span class="font-medium">Waktu:</span> ${startTime} - ${endTime}</div>
                        <div><span class="font-medium">Durasi:</span> ${props.durasi}</div>
                    </div>
                </div>
                
                <div class="bg-green-50 p-3 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Informasi Penyewa</h4>
                    <div class="grid grid-cols-1 gap-2 text-sm">
                        <div><span class="font-medium">Nama:</span> ${props.penyewa}</div>
                        <div><span class="font-medium">No. Telp:</span> ${props.phone}</div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Pembayaran</h4>
                    <div class="grid grid-cols-1 gap-2 text-sm">
                        <div><span class="font-medium">Total:</span> ${props.total_harga}</div>
                        <div><span class="font-medium">Metode:</span> ${props.metode_bayar}</div>
                        <div><span class="font-medium text-green-600">Status: Lunas</span></div>
                    </div>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
    }

    // Event listeners untuk modal
    closeModalBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endpush