# Shiftech CRM – Sistem Manajemen CRM dan Pelacakan Kunjungan Tim Marketing

MarketerCRM adalah aplikasi berbasis web untuk membantu tim marketing dalam mengelola pelanggan, menjadwalkan dan merekam kunjungan, serta memonitor progress pemasaran secara terstruktur dan terukur.

Sistem ini memudahkan manajemen dalam mengawasi aktivitas marketing, melihat performa tiap marketer, dan menganalisis status prospek hingga closing.

---

## 🚀 Fitur Utama

- Manajemen Data Pelanggan (Customers)  
- Penugasan Pelanggan kepada Marketer (Users)  
- Penjadwalan dan Pencatatan Kunjungan Marketing (Visits)  
- Tracking Status Progress Prospek (pipeline status)  
- Dashboard performa tim marketing (kunjungan, konversi, follow-up)  
- Laporan detail aktivitas kunjungan per marketer  
- Analisis status pelanggan dan sumber prospek  
- Notifikasi reminder follow-up pelanggan  

---

## 💡 Tech Stack

- **Backend**: Laravel 11 (PHP)  
- **Frontend**: Vue.js 3 + Quasar 2 / Livewire (opsional)  
- **Database**: MySQL  
- **Charting & Dashboard**: ECharts / Chart.js  
- **Auth**: Laravel Sanctum / Passport  

---

## 📦 Struktur Database (Inti)

- `users` — data tim marketing dan admin  
- `customers` — data prospek dan pelanggan  
- `visits` — catatan kunjungan marketing per tanggal  
- `visit_notes` — detail hasil kunjungan dan aktivitas follow-up  

---

## 📈 Dashboard dan Laporan

- **Dashboard Marketer**  
  - Jumlah kunjungan hari ini / minggu / bulan  
  - Status pelanggan (new, contacted, interested, closed)  
  - Grafik performa per marketer  

- **Laporan Kunjungan**  
  - Daftar kunjungan per marketer dengan status hasil kunjungan  
  - Rangkuman aktivitas follow-up dan jadwal berikutnya  

- **Laporan Pipeline**  
  - Visualisasi funnel penjualan dari prospek ke closing  
  - Statistik sumber pelanggan dan konversi  

---

## 🚀 Cara Instalasi

1. Clone repository  
2. Jalankan `composer install`  
3. Setup `.env` dan konfigurasi database  
4. Jalankan migrasi dan seeder:  
   ```bash
   php artisan migrate --seed
