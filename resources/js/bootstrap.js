/**
 * Pertama-tama, kita akan memuat pustaka axios yang memungkinkan kita untuk
 * dengan mudah mengirim permintaan ke backend Laravel kita. Pustaka ini
 * secara otomatis menangani pengiriman token CSRF sebagai header berdasarkan
 * nilai cookie "XSRF" token.
 */

import axios from 'axios';
window.axios = axios;

// Menambahkan header default untuk permintaan AJAX
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo menawarkan API ekspresif untuk berlangganan saluran dan mendengarkan
 * acara yang disiarkan oleh Laravel. Echo dan siaran acara memungkinkan
 * tim Anda untuk dengan mudah membangun aplikasi web waktu nyata yang kuat.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Menginisialisasi Pusher
window.Pusher = Pusher;

// Mengonfigurasi Echo dengan Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your-pusher-key', // Ganti dengan kunci Pusher Anda dari .env atau dashboard Pusher
    cluster: 'your-pusher-cluster', // Ganti dengan cluster Pusher Anda
    wsHost: window.location.hostname, // Host lokal (misal: 127.0.0.1)
    wsPort: 6001, // Port WebSocket Laravel
    wssPort: 6001, // Port yang sama untuk WebSocket Secure (wss)
    forceTLS: false, // Tidak menggunakan TLS untuk lokal
    disableStats: true, // Nonaktifkan laporan statistik Pusher
});

// Pastikan untuk menggunakan `window.Echo` setelah inisialisasi ini
