# 🏢 Sistem Manajemen Karyawan & Shift

Aplikasi berbasis web untuk mengelola jadwal shift, pengajuan cuti, dan manajemen data karyawan secara efisien. Dibangun menggunakan **Laravel 12**, **Livewire**, dan **Tailwind CSS**.

---

## 🚀 Fitur Utama

* **Dashboard Interaktif**: Kalender visual yang menampilkan jadwal shift dan status cuti.
* **Manajemen Shift**: Pengaturan jadwal harian karyawan dengan sistem *drag-and-drop* (atau dropdown).
* **Pengajuan Cuti**: Sistem approval cuti otomatis dengan kalkulasi sisa kuota tahunan.
* **Control Panel**: Pengaturan global untuk jumlah jatah cuti dan bulan reset kuota.
* **User Management**: Pengelolaan data karyawan beserta Role (Admin/Karyawan).

---

## 📖 Panduan Penggunaan (User Guide)

### 1. Bagi Admin
* **Menambahkan Karyawan**: Buka menu **Management**, pilih User, isi nama, email, dan password, lalu simpan.
* **Mengatur Jadwal**: Buka menu **Management**, pilih Shift, tentukan karyawan, tanggal, dan jenis shift, lalu simpan.
* **Pencarian**: Gunakan kolom pencarian di tabel untuk menemukan jadwal karyawan tertentu secara cepat.
* **Approval Cuti**: Buka menu **Permissions**, pilih Pending Approval untuk menyetujui atau menolak permohonan cuti.
* **Konfigurasi**: Buka menu **Management**, pilih Settings untuk mengubah jatah cuti tahunan perusahaan.

### 2. Bagi Karyawan
* **Melihat Jadwal**: Jadwal kerjamu akan langsung muncul di **Kalender Dashboard** setelah login.
* **Cek Sisa Cuti**: Sisa kuota cuti ditampilkan secara real-time di bagian atas dashboard.
* **Ajukan Cuti**: Buka menu **Permissions**, pilih "Request Leave", pilih tanggal, dan tunggu persetujuan Admin.

---

## 🛠️ Cara Instalasi (Technical Setup)

1.  **Clone Repository**:
    ```bash
    git clone https://github.com/babier/Employee_Management_System
    ```
2.  **Install Dependencies**:
    ```bash
    composer install
    npm install && npm run build
    ```
3.  **Konfigurasi Environment**:
    * Salin `.env.example` menjadi `.env`.
    * Sesuaikan koneksi database di `.env`.
4.  **Migrate & Seed**:
    ```bash
    php artisan migrate --seed
    ```
5.  **Jalankan Server**:
    ```bash
    php artisan serve
    ```

---

## 👤 Kontak & Kontribusi
Dibuat oleh **Delistyo Sagita Wahono**. Silakan hubungi via GitHub jika ada kendala atau ingin berkontribusi.
