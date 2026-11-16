# Changelog - SiBatik ERP

Semua perubahan penting pada proyek SiBatik ERP didokumentasikan dalam berkas ini.
Format penulisan mengacu pada [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan prinsip [Conventional Commits](https://www.conventionalcommits.org/).

## [v0.02] - 2026-11-16 (Pekan 2: Test Planning & Master Data CRUD)

### Ditambahkan (Added)
- **Rencana Pengujian Induk (Master Test Plan):** Penyusunan dokumen perencanaan uji komprehensif `qa/test-plan/TEST_PLAN.md` mengacu pada standar ISO/IEC/IEEE 29119-3, memuat strategi pengujian multi-level, kriteria entry/exit, analisis risiko kualitas, estimasi sumber daya, dan jadwal 16 pekan.
- **CRUD Master Data & Validasi Anti-Redundan:**
  - **Vendor:** Model, migrasi, request, dan view manajemen vendor pemasok bahan baku (kain mori, pewarna, malam, aksesoris) dengan validasi duplikasi kode, nama (case-insensitive), email, dan nomor telepon.
  - **Pelanggan (Customer):** Model, migrasi, request, dan view manajemen pelanggan ritel (`umum`) dan grosir (`reseller`), diskon khusus (%), dan batas piutang (*credit limit*), lengkap dengan validasi duplikasi kode, email, dan telepon.
  - **Gudang (Warehouse):** Model, migrasi, request, dan view pemisahan lokasi gudang fisik antara gudang batik jadi (`batik`), gudang bahan baku/pewarna (`bahan`), dan gudang umum/transit (`umum`).
  - **Produk Batik & Varian:** Model `Product` & `ProductVariant`, mendukung katalog batik tulis, batik cap, kain lembaran, dan pakaian jadi beserta variasi ukuran (S-XL) dan corak motif. Dilengkapi proteksi duplikasi kombinasi ukuran & corak motif pada produk yang sama, serta generator SKU otomatis.
- **Master Data Dummy Seeder:** `database/seeders/MasterDataSeeder.php` mencakup 3 vendor, 10 pelanggan, 2 gudang, dan 15 model produk batik dengan puluhan varian ukuran & motif.
- **Logika Bisnis di Service Layer:** `app/Services/MasterDataService.php` menangani seluruh verifikasi duplikasi, transaksional pembuatan produk & varian, serta isolasi logika dari controller.
- **Pengujian Fitur (Feature Testing):** `tests/Feature/MasterDataCrudTest.php` dengan 10 test case otomatis (total 27 test case di sistem, 150 assertions, 100% green).
- **Artefak RPS Pekan 02:**
  - `qa/test-plan/TEST_PLAN.md`: Dokumen Master Test Plan standar ISO/IEC/IEEE 29119-3.
  - `docs/pekan-02/defects.md`: Log pelacakan cacat DEF-003, DEF-004, DEF-005 (seluruhnya terselesaikan).
  - `docs/pekan-02/README.md`: Ringkasan implementasi, cara menjalankan, dan hasil verifikasi Pekan 2.

## [v0.01] - 2026-11-02 (Pekan 1: SQA, V-Model, ISO/IEC/IEEE 29119)

### Ditambahkan (Added)
- **Fondasi Repositori:** Struktur dasar direktori proyek ERP/EIS SiBatik untuk UMKM Batik Pamekasan.
- **Docker Compose:** Definisi kontainer layanan `app` (PHP 8.3 FPM), `nginx` (web server port 8000), dan `postgres` (PostgreSQL 16 port 5432) dengan volume persisten data dan network bridge terisolasi.
- **Framework & Autentikasi:** Laravel 11 terkonfigurasi dengan `spatie/laravel-permission` dan `laravel/sanctum`.
- **Manajemen Peran (Role Management):** 7 peran hak akses spesifik sistem:
  1. `admin` (Administrator Sistem)
  2. `pembelian` (Staff / Kepala Pembelian)
  3. `gudang` (Staff / Kepala Gudang)
  4. `penjualan` (Staff / Kepala Penjualan)
  5. `kasir` (Kasir POS Batik)
  6. `keuangan` (Staff / Akuntan Keuangan)
  7. `eksekutif` (Pimpinan / Pemilik UMKM)
- **Role Routing & Dashboard:** Kontroller routing berbasis peran (`RoleDashboardController`) yang mengarahkan pengguna secara otomatis ke dashboard modul masing-masing pasca login.
- **Tampilan Antarmuka (Blade Layout):** Layout dasar terintegrasi Tailwind CSS dan Alpine.js dengan sidebar dinamis, navigasi peran, serta feedback status.
- **Pengujian Otomatis (Testing Suite):** Pengujian Pest terpasang mencakup:
  - Smoke test: Halaman login terbuka dengan kode status 200.
  - Role redirection test: Verifikasi alur login dan pengalihan ke dashboard spesifik masing-masing dari ke-7 peran.
  - Hak akses terproteksi: Memastikan pengguna tanpa otentikasi diarahkan kembali ke halaman login.
- **Artefak RPS:**
  - `docs/ROADMAP.md`: Peta jalan 16 pekan, arsitektur modul, dan model data.
  - `AGENTS.md`: Aturan agen AI dalam pengembangan dan penjaminan mutu.
  - `docs/pekan-01/vmodel.md`: Pemetaan V-Model (ISO/IEC/IEEE 29119) dan analisis Verifikasi vs Validasi.
  - `docs/pekan-01/defects.md`: Log pencatatan dan resolusi cacat Pekan 1.
  - `docs/pekan-01/README.md`: Panduan eksekusi dan pelaporan hasil uji Pekan 1.

