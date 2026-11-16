# Pekan 02: Test Planning & Master Data CRUD (SiBatik ERP)

Selamat datang di dokumentasi hasil pengerjaan **Pekan 02** proyek **SiBatik ERP (Sistem ERP/EIS UMKM Batik Pamekasan)**, disusun berdasarkan Rencana Pembelajaran Semester (RPS) mata kuliah *Manajemen dan Proyek SI* (MKB020204, Universitas Islam Madura).

Fokus pekan ini adalah penyusunan **Rencana Pengujian (Test Plan)** komprehensif mengacu pada standar **ISO/IEC/IEEE 29119-3**, serta pembangunan **CRUD Master Data** dengan pencegahan data ganda/redundan (menindaklanjuti temuan pada artikel Sulistiono, Hediyanto, & Budiyono, 2026).

---

## 1. Apa yang Dibangun (Lingkup Fitur)

### A. Rencana Pengujian Induk (Master Test Plan ISO/IEC/IEEE 29119-3)
- Tersusun di file [`qa/test-plan/TEST_PLAN.md`](file:///c:/Users/asus/OneDrive/Desktop/uts%20ERP%20gis%20dela/qa/test-plan/TEST_PLAN.md).
- Memuat ruang lingkup pengujian, strategi multi-tingkat (Unit, Integrasi, API Newman, E2E Playwright, Beban k6, Keamanan OWASP ZAP), kriteria **Entry & Exit**, analisis risiko kualitas produk, serta jadwal pengujian rinci 16 pekan.

### B. Modul Master Data Terpadu
1. **Vendor / Pemasok Bahan Baku:**
   - Model `Vendor`, migrasi `create_vendors_table`.
   - Mengelola pemasok kain mori, lilin/malam, pewarna alam/sintetis, dan kemasan.
   - Pengecekan kode unik, nama unik (case-insensitive), nomor telepon, dan email.
2. **Pelanggan (Reseller vs Umum):**
   - Model `Customer`, migrasi `create_customers_table`.
   - Klasifikasi pelanggan ritel standar (`umum`) dan grosir (`reseller`), pengaturan diskon khusus persentase, dan batas plafon piutang (*credit limit*).
   - Validasi duplikasi kode, email, dan nomor telepon.
3. **Gudang (Pemisahan Gudang Batik & Lainnya):**
   - Model `Warehouse`, migrasi `create_warehouses_table`.
   - Klasifikasi tipe lokasi gudang: gudang batik jadi (`batik`), bahan baku/pewarna (`bahan`), dan gudang umum/transit (`umum`).
   - Validasi keunikan kode dan nama gudang.
4. **Produk Batik & Varian Motif/Ukuran:**
   - Model `Product` dan `ProductVariant`, relasi `hasMany` varian.
   - Kategori batik: `batik_tulis`, `batik_cap`, `kain`, `pakaian`.
   - Varian mencakup ukuran (S, M, L, XL, XXL, All Size), motif (misal: Sekar Jagad, Klampar, Gentongan, dsb.), HPP (harga pokok), harga jual ritel, stok minimum (*Reordering Rule*), dan stok saat ini.
   - Pencegahan duplikasi kombinasi ukuran & corak motif pada satu produk yang sama.
   - Generator SKU otomatis (`generateVariantSku`) jika SKU tidak diisi manual.

### C. Seeder Dummy Master Data
- File: `database/seeders/MasterDataSeeder.php` (terdaftar di `DatabaseSeeder.php`).
- Data yang di-seed:
  - 3 vendor utama: PT Mori Prima Tekstil, CV Pewarna Alam Madura, UD Canting & Aksesoris Proppo.
  - 10 pelanggan riil (5 umum, 5 reseller dengan diskon 10-15%).
  - 2 gudang fisik: Gudang Utama Batik Jadi & Gudang Bahan Baku Workshop Klampar.
  - 15 produk batik khas Pamekasan lengkap dengan puluhan varian ukuran dan corak motif.

### D. Tampilan Antarmuka (Blade + Alpine.js + Tailwind CSS)
- Menu dropdown navigasi terintegrasi di navigasi desktop & responsive drawer mobile.
- Halaman Indeks & Formulir Responsif:
  - `resources/views/master/vendors/` (index & form)
  - `resources/views/master/customers/` (index & form)
  - `resources/views/master/warehouses/` (index & form)
  - `resources/views/master/products/` (index, form, & show detail varian interaktif)

---

## 2. Cara Menjalankan

### Melalui Server Lokal PHP
```bash
# 1. Jalankan migrasi dan seeder database
& ".\tools\bin\php.cmd" artisan migrate --seed

# 2. Jalankan server lokal
& ".\tools\bin\php.cmd" artisan serve --host=127.0.0.1 --port=8000
```
Aplikasi dapat diakses langsung pada browser di: **`http://127.0.0.1:8000`**

### Melalui Docker Compose
```bash
docker compose up -d --build
docker compose exec app php artisan migrate --seed
```

### Akun Uji Coba (Login)
- **Email:** `admin@sibatik.lan` (atau sembarang akun `@gmail.com` dengan auto-admin login)
- **Password:** `password`
- Masuk ke menu **Master Data** di bilah navigasi atas untuk mengelola Produk, Vendor, Pelanggan, dan Gudang.

---

## 3. Cara Menguji

Jalankan rangkaian pengujian otomatis menggunakan **Pest**:
```bash
& ".\tools\bin\php.cmd" artisan test
```
Atau khusus pengujian modul Master Data:
```bash
& ".\tools\bin\php.cmd" artisan test --filter=MasterDataCrudTest
```

---

## 4. Hasil Test (100% Lulus)

Seluruh test suite berjalan **HIJAU** tanpa kegagalan:

```text
   PASS  Tests\Unit\RoleNavigationServiceTest
  ✓ service returns correct dashboard route for each role
  ✓ service returns default dashboard when user has no role
  ✓ service registers exactly 7 supported roles

   PASS  Tests\Feature\AuthSmokeTest
  ✓ landing page loads successfully
  ✓ login screen can be rendered
  ✓ unauthenticated users are redirected from dashboard to login
  ✓ unauthenticated users cannot access role specific dashboards
  ✓ user can log in with seeded admin gmail account and redirects to admin dashboard
  ✓ any new gmail address automatically logs in as admin without error

   PASS  Tests\Feature\MasterDataCrudTest
  ✓ admin can access all master data index pages
  ✓ can create, update, and delete a vendor
  ✓ rejects duplicate vendor code and name with clear error message
  ✓ can create, update, and delete a customer
  ✓ rejects duplicate customer code, phone, and email
  ✓ can create, update, and delete a warehouse
  ✓ rejects duplicate warehouse code and name
  ✓ can create product with initial variants, update, and delete product
  ✓ rejects duplicate product code and name
  ✓ can manage product variants and rejects duplicate size/pattern combination

   PASS  Tests\Feature\RoleRedirectionTest
  ✓ admin user redirects to admin dashboard
  ✓ pembelian user redirects to pembelian dashboard
  ✓ gudang user redirects to gudang dashboard
  ✓ penjualan user redirects to penjualan dashboard
  ✓ kasir user redirects to kasir dashboard
  ✓ keuangan user redirects to keuangan dashboard
  ✓ eksekutif user redirects to eksekutif dashboard
  ✓ cross role access is blocked with 403 forbidden

  Tests:    27 passed (150 assertions)
  Duration: 18.00s
```

---

## 5. Bug dan Cacat yang Diketahui

Semua temuan bug selama pengujian telah diperbaiki dan terdokumentasi secara transparan di:
- [`docs/pekan-02/defects.md`](file:///c:/Users/asus/OneDrive/Desktop/uts%20ERP%20gis%20dela/docs/pekan-02/defects.md)

| Defect ID | Judul Masalah | Severity | Status |
| :--- | :--- | :---: | :---: |
| **DEF-003** | Inkonsistensi nama atribut narahubung vendor (`contact_name` vs `contact_person`) | Medium | Closed |
| **DEF-004** | Validasi SKU varian bersifat wajib menghambat auto-generation kode SKU | Medium | Closed |
| **DEF-005** | Form duplikasi varian terblokir field `min_stock` sebelum mencapai validasi duplikat | Low | Closed |

---

## 6. Artefak RPS Pekan 02

1. **Test Plan ISO/IEC/IEEE 29119-3:**
   - [`qa/test-plan/TEST_PLAN.md`](file:///c:/Users/asus/OneDrive/Desktop/uts%20ERP%20gis%20dela/qa/test-plan/TEST_PLAN.md)
2. **Defect Tracking Log:**
   - [`docs/pekan-02/defects.md`](file:///c:/Users/asus/OneDrive/Desktop/uts%20ERP%20gis%20dela/docs/pekan-02/defects.md)
3. **Dokumentasi Lengkap Siklus Pekan 02:**
   - [`docs/pekan-02/README.md`](file:///c:/Users/asus/OneDrive/Desktop/uts%20ERP%20gis%20dela/docs/pekan-02/README.md)
