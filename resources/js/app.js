import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Menginisialisasi Pusher
window.Pusher = Pusher;

// Mengonfigurasi Echo dengan Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your-pusher-key', // Ganti dengan key yang sesuai dari akun Pusher Anda
    cluster: 'your-pusher-cluster', // Ganti dengan cluster Pusher Anda
    wsHost: window.location.hostname, // Host lokal
    wsPort: 6001, // Port WebSocket Laravel
    wssPort: 6001, // Port yang sama untuk WebSocket Secure
    forceTLS: false, // Tidak menggunakan TLS untuk lokal
    disableStats: true, // Nonaktifkan laporan statistik Pusher
});

// Tambahkan listener di sini
window.Echo.channel('test-channel')
    .listen('WebSocketTestEvent', (e) => {
        console.log(`Message: ${e.message}, Creator: ${e.creator}`); // Tampilkan informasi creator
        alert(`Message: ${e.message}, Creator: ${e.creator}`); // Atau tampilkan dengan alert
    });

// Tambahkan listener untuk reports-channel
window.Echo.channel('reports-channel')
    .listen('ReportChanged', (e) => {
        console.log(`Incident Type: ${e.incident_type}, Snapshot Path: ${e.snapshot_path}`);
        // Lakukan tindakan yang sesuai, misalnya memperbarui tampilan
        alert(`Incident Type: ${e.incident_type}, Snapshot Path: ${e.snapshot_path}`);
    });

// Tambahkan listener untuk messages-channel
window.Echo.channel('messages-channel')
    .listen('MessageChanged', (e) => {
        console.log(`Pesan: ${e.message.content}`);
        // Lakukan tindakan yang sesuai untuk memperbarui tampilan jika diperlukan
        // Misalnya, tambahkan baris baru atau perbarui baris yang ada di tabel
        // Contoh:
        // $('#messagesTableBody').append(`...`); // Tambahkan pesan baru ke tabel
    });

// Menyusun ajax setup untuk menyertakan token CSRF
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
