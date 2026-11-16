# Log Cacat dan Bug (Defect Tracking) - Pekan 02

Dokumen ini mencatat defect/bug yang ditemukan selama pengembangan dan pengujian pada siklus **Pekan 02: Test Planning & Master Data CRUD (Vendor, Pelanggan, Gudang, Produk & Varian)** sesuai aturan penjaminan mutu SiBatik ERP.

---

## Ringkasan Status Cacat

| Total Cacat | Open | In Progress | Resolved / Closed |
| :---: | :---: | :---: | :---: |
| 3 | 0 | 0 | 3 |

---

## Rincian Defect

### DEF-003: Ketidakcocokan Field Kontak PIC & Pilihan Kategori Vendor
- **Komponen:** `app/Http/Requests/VendorRequest.php`, `app/Models/Vendor.php`, `resources/views/master/vendors/`
- **Tingkat Keparahan (Severity):** Medium
- **Prioritas:** High
- **Deskripsi:** Form view menggunakan nama atribut `contact_name` sedangkan skema database menggunakan `contact_person`. Selain itu, validasi kategori vendor `in:kain,pewarna,aksesori,lainnya` menolak input string kategori bebas dari form.
- **Dampak:** Penyimpanan vendor baru dapat gagal atau nilai narahubung tidak tersimpan ke database.
- **Solusi / Perbaikan:**
  - Ditambahkan alias handling di `MasterDataService` (`contact_name` dipetakan ke `contact_person`).
  - Ditambahkan accessor `getContactNameAttribute()` pada model `Vendor`.
  - Diselaraskan aturan validasi di `VendorRequest` agar mendukung fleksibilitas kategori hingga 100 karakter.
- **Status:** **CLOSED / RESOLVED** (Terverifikasi lewat `MasterDataCrudTest` test #2).

---

### DEF-004: Validasi SKU Varian Produk Menghambat Auto-Generation SKU
- **Komponen:** `app/Http/Requests/ProductVariantRequest.php`, `app/Http/Requests/ProductRequest.php`, `app/Services/MasterDataService.php`
- **Tingkat Keparahan (Severity):** Medium
- **Prioritas:** Medium
- **Deskripsi:** Aturan validasi `variants.*.sku` dan `sku` pada varian diset `required`, sehingga pengguna atau API yang tidak menyertakan SKU manual (mengharapkan SKU terbentuk otomatis dari kode produk + ukuran + motif) mengalami kegagalan validasi form.
- **Dampak:** Pengguna wajib mengetik SKU secara manual saat menambah varian baru, menurunkan efisiensi entri data katalog batik.
- **Solusi / Perbaikan:**
  - Aturan validasi `sku` diubah menjadi `nullable|string|max:50`.
  - Diimplementasikan method `generateVariantSku(Product $product, string $size, string $pattern)` pada `MasterDataService` yang menghasilkan kode SKU unik otomatis (misal: `BTK-TL-001-M-KLASIK`) dan menangani collision dengan penomoran berurut.
- **Status:** **CLOSED / RESOLVED** (Terverifikasi lewat `MasterDataCrudTest` test #8 & #10).

---

### DEF-005: Form Duplikasi Varian Terhalang Validasi Field Lain Sebelum Pengecekan Duplikat
- **Komponen:** `app/Http/Requests/ProductVariantRequest.php`, `tests/Feature/MasterDataCrudTest.php`
- **Tingkat Keparahan (Severity):** Low
- **Prioritas:** Low
- **Deskripsi:** Pengecekan duplikat kombinasi ukuran dan motif terhalang jika field pendukung seperti `min_stock` tidak diisi (karena aturan sebelumnya `required`).
- **Dampak:** Pesan error yang muncul adalah "min_stock wajib diisi", bukan informasi eksplisit mengenai duplikasi varian batik.
- **Solusi / Perbaikan:**
  - Field `min_stock` dijadikan nullable di `ProductVariantRequest` dengan default fallback di level model/service.
  - Skenario pengujian duplikasi diperbaiki agar menyediakan payload lengkap sehingga penolakan murni menguji aturan `validateUniqueVariant`.
- **Status:** **CLOSED / RESOLVED** (Terverifikasi lewat `MasterDataCrudTest` test #10).
