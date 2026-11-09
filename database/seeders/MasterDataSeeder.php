<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run master data seeder according to Sulistiono et al. (2026) & RPS specification:
     * - 3 Vendors (kain, pewarna, aksesori)
     * - 10 Customers (umum & reseller)
     * - 2 Warehouses (gudang batik & bahan)
     * - 15 Products with S-XL variants and 3-4 distinct Madura & Lasem patterns.
     */
    public function run(): void
    {
        // 1. VENDORS (3)
        $vendors = [
            [
                'code' => 'VND-001',
                'name' => 'PT Mori Primissima Santoso',
                'category' => 'kain',
                'contact_person' => 'Bpk. Sugeng Wibowo',
                'email' => 'sugeng@primissima.co.id',
                'phone' => '081234567890',
                'address' => 'Jl. Industri Tekstil No. 12, Pekalongan',
            ],
            [
                'code' => 'VND-002',
                'name' => 'CV Sinar Warna Remasol',
                'category' => 'pewarna',
                'contact_person' => 'Ibu Lilis Suryani',
                'email' => 'sales@sinarwarna.lan',
                'phone' => '081398765432',
                'address' => 'Kawasan Industri Candi No. 45, Semarang',
            ],
            [
                'code' => 'VND-003',
                'name' => 'Toko Malam Canting Kencana',
                'category' => 'aksesori',
                'contact_person' => 'Bpk. Djoko Waluyo',
                'email' => 'kencana.malam@gmail.com',
                'phone' => '081765432100',
                'address' => 'Jl. Malioboro Kerajinan No. 8, Yogyakarta',
            ],
        ];

        foreach ($vendors as $v) {
            Vendor::firstOrCreate(['code' => $v['code']], $v);
        }

        // 2. CUSTOMERS (10: 5 Reseller, 5 Umum)
        $customers = [
            [
                'code' => 'CST-001',
                'name' => 'Butik Batik Nusantara Surabaya',
                'customer_type' => 'reseller',
                'email' => 'order@butiknusantara.com',
                'phone' => '082111222333',
                'address' => 'Grand City Mall Lt. 2 No. 14, Surabaya',
                'special_discount' => 15.00,
            ],
            [
                'code' => 'CST-002',
                'name' => 'Galeri Mahkota Madura Pamekasan',
                'customer_type' => 'reseller',
                'email' => 'galeri.mahkota@gmail.com',
                'phone' => '082222333444',
                'address' => 'Jl. Trunojoyo No. 88, Pamekasan',
                'special_discount' => 12.00,
            ],
            [
                'code' => 'CST-003',
                'name' => 'Grosir Batik Madurasa Bangkalan',
                'customer_type' => 'reseller',
                'email' => 'grosir.madurasa@gmail.com',
                'phone' => '082333444555',
                'address' => 'Pasar Tanah Merah Blok A1, Bangkalan',
                'special_discount' => 20.00,
            ],
            [
                'code' => 'CST-004',
                'name' => 'Koperasi Sentra Batik Lasem Indah',
                'customer_type' => 'reseller',
                'email' => 'koperasi.lasem@gmail.com',
                'phone' => '082444555666',
                'address' => 'Jl. Karangturi No. 5, Lasem, Rembang',
                'special_discount' => 10.00,
            ],
            [
                'code' => 'CST-005',
                'name' => 'Toko Busana Tradisi Malang',
                'customer_type' => 'reseller',
                'email' => 'busana.tradisi@yahoo.com',
                'phone' => '082555666777',
                'address' => 'Jl. Ijen Boulevard No. 23, Malang',
                'special_discount' => 10.00,
            ],
            [
                'code' => 'CST-006',
                'name' => 'Bpk. Ahmad Fauzi Hidayat',
                'customer_type' => 'umum',
                'email' => 'ahmad.fauzi@gmail.com',
                'phone' => '085666777888',
                'address' => 'Perum Graha Pamekasan Asri Blok C-3',
                'special_discount' => 0.00,
            ],
            [
                'code' => 'CST-007',
                'name' => 'Ibu Hj. Siti Nurhasanah',
                'customer_type' => 'umum',
                'email' => 'siti.hasanah@gmail.com',
                'phone' => '085777888999',
                'address' => 'Jl. Jokotole No. 45, Pamekasan',
                'special_discount' => 0.00,
            ],
            [
                'code' => 'CST-008',
                'name' => 'Bpk. Bambang Tri Sutrisno',
                'customer_type' => 'umum',
                'email' => 'bambang.tri@gmail.com',
                'phone' => '085888999000',
                'address' => 'Jl. Diponegoro No. 12, Sumenep',
                'special_discount' => 0.00,
            ],
            [
                'code' => 'CST-009',
                'name' => 'Ibu Roro Endah Wilis',
                'customer_type' => 'umum',
                'email' => 'roro.wilis@gmail.com',
                'phone' => '085999000111',
                'address' => 'Jl. Lawu No. 19, Sampang',
                'special_discount' => 0.00,
            ],
            [
                'code' => 'CST-010',
                'name' => 'Bpk. Dr. Hendra Gunawan',
                'customer_type' => 'umum',
                'email' => 'hendra.gunawan@gmail.com',
                'phone' => '081112233445',
                'address' => 'Jl. Kesehatan No. 7, Pamekasan',
                'special_discount' => 0.00,
            ],
        ];

        foreach ($customers as $c) {
            Customer::firstOrCreate(['code' => $c['code']], $c);
        }

        // 3. WAREHOUSES (2: Gudang Batik vs Gudang Lainnya)
        $warehouses = [
            [
                'code' => 'GDG-BTK',
                'name' => 'Gudang Utama Produk Batik',
                'warehouse_type' => 'batik',
                'location' => 'Galeri Depan & Lantai 2 Showroom SiBatik',
                'description' => 'Penyimpanan produk jadi siap jual (Batik Tulis, Batik Cap, Busana Batik)',
            ],
            [
                'code' => 'GDG-BHN',
                'name' => 'Gudang Bahan Baku & Aksesori',
                'warehouse_type' => 'bahan',
                'location' => 'Bengkel Workshop Bagian Belakang',
                'description' => 'Penyimpanan kain mori mentah, pewarna remasol/indigosol, malam, canting',
            ],
        ];

        foreach ($warehouses as $w) {
            Warehouse::firstOrCreate(['code' => $w['code']], $w);
        }

        // 4. PRODUCTS & VARIANTS (15 Produk)
        $products = [
            // Kategori: Batik Tulis (4)
            [
                'code' => 'PRD-BT-01',
                'name' => 'Batik Tulis Sutra Sekar Jagad Pamekasan',
                'category' => 'batik_tulis',
                'uom' => 'lembar',
                'description' => 'Kain sutra ATBM halus dengan motif Sekar Jagad khas Pamekasan, pewarnaan alami tumpal.',
                'variants' => [
                    ['sku' => 'BT01-SJ-S', 'size' => 'S', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 600000, 'sell' => 1250000, 'min' => 3, 'stock' => 12],
                    ['sku' => 'BT01-SJ-M', 'size' => 'M', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 650000, 'sell' => 1350000, 'min' => 4, 'stock' => 18],
                    ['sku' => 'BT01-SJ-L', 'size' => 'L', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 700000, 'sell' => 1450000, 'min' => 4, 'stock' => 15],
                    ['sku' => 'BT01-SJ-XL', 'size' => 'XL', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 750000, 'sell' => 1550000, 'min' => 2, 'stock' => 8],
                ],
            ],
            [
                'code' => 'PRD-BT-02',
                'name' => 'Batik Tulis Alami Tarum Gentongan',
                'category' => 'batik_tulis',
                'uom' => 'lembar',
                'description' => 'Proses fermentasi indigo pasta gentong tanah liat khas Madura, warna biru tua tahan puluhan tahun.',
                'variants' => [
                    ['sku' => 'BT02-TG-M', 'size' => 'M', 'pattern' => 'Tarum Gentongan', 'cost' => 800000, 'sell' => 1600000, 'min' => 3, 'stock' => 10],
                    ['sku' => 'BT02-TG-L', 'size' => 'L', 'pattern' => 'Tarum Gentongan', 'cost' => 850000, 'sell' => 1700000, 'min' => 3, 'stock' => 14],
                    ['sku' => 'BT02-TG-XL', 'size' => 'XL', 'pattern' => 'Tarum Gentongan', 'cost' => 900000, 'sell' => 1800000, 'min' => 2, 'stock' => 6],
                ],
            ],
            [
                'code' => 'PRD-BT-03',
                'name' => 'Batik Tulis Primissima Babaran Tiga Negeri',
                'category' => 'batik_tulis',
                'uom' => 'lembar',
                'description' => 'Perpaduan warna merah Lasem, biru Pekalongan, dan cokelat sogan Solo.',
                'variants' => [
                    ['sku' => 'BT03-3N-M', 'size' => 'M', 'pattern' => 'Tiga Negeri Babaran', 'cost' => 750000, 'sell' => 1500000, 'min' => 2, 'stock' => 9],
                    ['sku' => 'BT03-3N-L', 'size' => 'L', 'pattern' => 'Tiga Negeri Babaran', 'cost' => 800000, 'sell' => 1600000, 'min' => 2, 'stock' => 11],
                ],
            ],
            [
                'code' => 'PRD-BT-04',
                'name' => 'Batik Tulis Sutra Motif Lok Can Burung Phoenix',
                'category' => 'batik_tulis',
                'uom' => 'lembar',
                'description' => 'Akulturasi motif Lasem peranakan Tionghoa dan Madura pesisir.',
                'variants' => [
                    ['sku' => 'BT04-LC-AS', 'size' => 'All Size', 'pattern' => 'Lok Can Phoenix', 'cost' => 950000, 'sell' => 2100000, 'min' => 2, 'stock' => 7],
                ],
            ],

            // Kategori: Batik Cap (4)
            [
                'code' => 'PRD-BC-01',
                'name' => 'Batik Cap Katun Daun Memba Pamekasan',
                'category' => 'batik_cap',
                'uom' => 'lembar',
                'description' => 'Motif dedaunan khas pesisir Pamekasan dengan teknik cap tembaga presisi tinggi.',
                'variants' => [
                    ['sku' => 'BC01-DM-S', 'size' => 'S', 'pattern' => 'Daun Memba Hijau', 'cost' => 90000, 'sell' => 185000, 'min' => 10, 'stock' => 45],
                    ['sku' => 'BC01-DM-M', 'size' => 'M', 'pattern' => 'Daun Memba Hijau', 'cost' => 95000, 'sell' => 195000, 'min' => 15, 'stock' => 60],
                    ['sku' => 'BC01-DM-L', 'size' => 'L', 'pattern' => 'Daun Memba Hijau', 'cost' => 100000, 'sell' => 210000, 'min' => 15, 'stock' => 50],
                    ['sku' => 'BC01-DM-XL', 'size' => 'XL', 'pattern' => 'Daun Memba Hijau', 'cost' => 105000, 'sell' => 225000, 'min' => 10, 'stock' => 30],
                ],
            ],
            [
                'code' => 'PRD-BC-02',
                'name' => 'Batik Cap Kombinasi Gentongan Merah Lasem',
                'category' => 'batik_cap',
                'uom' => 'lembar',
                'description' => 'Warna merah darah ayam khas Lasem dengan ornamen gentong Madura.',
                'variants' => [
                    ['sku' => 'BC02-GM-S', 'size' => 'S', 'pattern' => 'Gentongan Merah', 'cost' => 95000, 'sell' => 195000, 'min' => 8, 'stock' => 35],
                    ['sku' => 'BC02-GM-M', 'size' => 'M', 'pattern' => 'Gentongan Merah', 'cost' => 100000, 'sell' => 210000, 'min' => 12, 'stock' => 55],
                    ['sku' => 'BC02-GM-L', 'size' => 'L', 'pattern' => 'Gentongan Merah', 'cost' => 105000, 'sell' => 220000, 'min' => 12, 'stock' => 40],
                    ['sku' => 'BC02-GM-XL', 'size' => 'XL', 'pattern' => 'Gentongan Merah', 'cost' => 110000, 'sell' => 235000, 'min' => 8, 'stock' => 25],
                ],
            ],
            [
                'code' => 'PRD-BC-03',
                'name' => 'Batik Cap Kontemporer Karapan Sapi',
                'category' => 'batik_cap',
                'uom' => 'lembar',
                'description' => 'Dinamika budaya Madura Karapan Sapi dalam guratan geometris cap modern.',
                'variants' => [
                    ['sku' => 'BC03-KS-M', 'size' => 'M', 'pattern' => 'Karapan Dinamis', 'cost' => 85000, 'sell' => 175000, 'min' => 10, 'stock' => 40],
                    ['sku' => 'BC03-KS-L', 'size' => 'L', 'pattern' => 'Karapan Dinamis', 'cost' => 90000, 'sell' => 185000, 'min' => 10, 'stock' => 38],
                    ['sku' => 'BC03-KS-XL', 'size' => 'XL', 'pattern' => 'Karapan Dinamis', 'cost' => 95000, 'sell' => 195000, 'min' => 6, 'stock' => 22],
                ],
            ],
            [
                'code' => 'PRD-BC-04',
                'name' => 'Batik Cap Tumpal Pesisiran Madura',
                'category' => 'batik_cap',
                'uom' => 'lembar',
                'description' => 'Ragam hias segitiga tumpal penolak bala dengan warna sogan sogan hangat.',
                'variants' => [
                    ['sku' => 'BC04-TP-S', 'size' => 'S', 'pattern' => 'Tumpal Pesisir', 'cost' => 85000, 'sell' => 175000, 'min' => 8, 'stock' => 25],
                    ['sku' => 'BC04-TP-M', 'size' => 'M', 'pattern' => 'Tumpal Pesisir', 'cost' => 90000, 'sell' => 185000, 'min' => 10, 'stock' => 42],
                    ['sku' => 'BC04-TP-L', 'size' => 'L', 'pattern' => 'Tumpal Pesisir', 'cost' => 95000, 'sell' => 195000, 'min' => 10, 'stock' => 35],
                ],
            ],

            // Kategori: Kain Bahan Baku (3)
            [
                'code' => 'PRD-KN-01',
                'name' => 'Kain Mori Primissima Cap Bedjana',
                'category' => 'kain',
                'uom' => 'meter',
                'description' => 'Kain mori katun 100% kerapatan benang tinggi kualitas ekspor untuk batik tulis.',
                'variants' => [
                    ['sku' => 'KN01-PM-2M', 'size' => '2m', 'pattern' => 'Polos Mori Putih', 'cost' => 45000, 'sell' => 70000, 'min' => 50, 'stock' => 300],
                    ['sku' => 'KN01-PM-25M', 'size' => '2.5m', 'pattern' => 'Polos Mori Putih', 'cost' => 55000, 'sell' => 85000, 'min' => 50, 'stock' => 250],
                ],
            ],
            [
                'code' => 'PRD-KN-02',
                'name' => 'Kain Sutra Super ATBM Halus',
                'category' => 'kain',
                'uom' => 'meter',
                'description' => 'Sutra alat tenun bukan mesin, serat alami berkilau lembut dan sejuk.',
                'variants' => [
                    ['sku' => 'KN02-ST-25M', 'size' => '2.5m', 'pattern' => 'Sutra Polos Cream', 'cost' => 175000, 'sell' => 280000, 'min' => 20, 'stock' => 85],
                ],
            ],
            [
                'code' => 'PRD-KN-03',
                'name' => 'Kain Rayon Santung Tebal Dingin',
                'category' => 'kain',
                'uom' => 'meter',
                'description' => 'Kain santung nyaman untuk daster dan kemeja batik kasual santai.',
                'variants' => [
                    ['sku' => 'KN03-ST-2M', 'size' => '2m', 'pattern' => 'Santung Natural', 'cost' => 32000, 'sell' => 52000, 'min' => 30, 'stock' => 150],
                ],
            ],

            // Kategori: Pakaian Jadi (4)
            [
                'code' => 'PRD-PK-01',
                'name' => 'Kemeja Pria Lengan Panjang Sekar Jagad',
                'category' => 'pakaian',
                'uom' => 'pcs',
                'description' => 'Kemeja batik katun primissima furing hero adem, cutting slim fit modern.',
                'variants' => [
                    ['sku' => 'PK01-KP-M', 'size' => 'M', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 140000, 'sell' => 285000, 'min' => 8, 'stock' => 25],
                    ['sku' => 'PK01-KP-L', 'size' => 'L', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 145000, 'sell' => 295000, 'min' => 10, 'stock' => 32],
                    ['sku' => 'PK01-KP-XL', 'size' => 'XL', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 150000, 'sell' => 310000, 'min' => 8, 'stock' => 20],
                    ['sku' => 'PK01-KP-XXL', 'size' => 'XXL', 'pattern' => 'Sekar Jagad Klasik', 'cost' => 160000, 'sell' => 330000, 'min' => 5, 'stock' => 12],
                ],
            ],
            [
                'code' => 'PRD-PK-02',
                'name' => 'Kemeja Pria Lengan Pendek Karapan Sapi',
                'category' => 'pakaian',
                'uom' => 'pcs',
                'description' => 'Busana santai formal semi kasual bermotif karapan sapi dinamis.',
                'variants' => [
                    ['sku' => 'PK02-PD-S', 'size' => 'S', 'pattern' => 'Karapan Hitam Merah', 'cost' => 110000, 'sell' => 225000, 'min' => 6, 'stock' => 18],
                    ['sku' => 'PK02-PD-M', 'size' => 'M', 'pattern' => 'Karapan Hitam Merah', 'cost' => 115000, 'sell' => 235000, 'min' => 8, 'stock' => 24],
                    ['sku' => 'PK02-PD-L', 'size' => 'L', 'pattern' => 'Karapan Hitam Merah', 'cost' => 120000, 'sell' => 245000, 'min' => 8, 'stock' => 26],
                    ['sku' => 'PK02-PD-XL', 'size' => 'XL', 'pattern' => 'Karapan Hitam Merah', 'cost' => 125000, 'sell' => 255000, 'min' => 6, 'stock' => 15],
                ],
            ],
            [
                'code' => 'PRD-PK-03',
                'name' => 'Blouse Batik Wanita Kerah Sanghai Gentongan',
                'category' => 'pakaian',
                'uom' => 'pcs',
                'description' => 'Atasan wanita elegan khas Madura Lasem, kancing shanghai tersembunyi.',
                'variants' => [
                    ['sku' => 'PK03-BL-S', 'size' => 'S', 'pattern' => 'Gentongan Indigo', 'cost' => 125000, 'sell' => 265000, 'min' => 6, 'stock' => 16],
                    ['sku' => 'PK03-BL-M', 'size' => 'M', 'pattern' => 'Gentongan Indigo', 'cost' => 130000, 'sell' => 275000, 'min' => 8, 'stock' => 22],
                    ['sku' => 'PK03-BL-L', 'size' => 'L', 'pattern' => 'Gentongan Indigo', 'cost' => 135000, 'sell' => 285000, 'min' => 8, 'stock' => 19],
                    ['sku' => 'PK03-BL-XL', 'size' => 'XL', 'pattern' => 'Gentongan Indigo', 'cost' => 140000, 'sell' => 295000, 'min' => 5, 'stock' => 11],
                ],
            ],
            [
                'code' => 'PRD-PK-04',
                'name' => 'Tunik Sutra Batik Kombinasi Tarum Pamekasan',
                'category' => 'pakaian',
                'uom' => 'pcs',
                'description' => 'Tunik panjang sutra anggun dengan aksen placket motif tarum pamekasan.',
                'variants' => [
                    ['sku' => 'PK04-TN-M', 'size' => 'M', 'pattern' => 'Tarum Pesisir', 'cost' => 190000, 'sell' => 395000, 'min' => 4, 'stock' => 14],
                    ['sku' => 'PK04-TN-L', 'size' => 'L', 'pattern' => 'Tarum Pesisir', 'cost' => 200000, 'sell' => 415000, 'min' => 4, 'stock' => 15],
                    ['sku' => 'PK04-TN-XL', 'size' => 'XL', 'pattern' => 'Tarum Pesisir', 'cost' => 210000, 'sell' => 435000, 'min' => 3, 'stock' => 9],
                ],
            ],
        ];

        foreach ($products as $p) {
            $variants = $p['variants'];
            unset($p['variants']);

            $product = Product::firstOrCreate(['code' => $p['code']], $p);

            foreach ($variants as $v) {
                ProductVariant::firstOrCreate(
                    ['sku' => $v['sku']],
                    [
                        'product_id' => $product->id,
                        'size' => $v['size'],
                        'pattern' => $v['pattern'],
                        'cost_price' => $v['cost'],
                        'selling_price' => $v['sell'],
                        'min_stock' => $v['min'],
                        'current_stock' => $v['stock'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
