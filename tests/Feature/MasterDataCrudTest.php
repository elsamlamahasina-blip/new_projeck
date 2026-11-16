<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Warehouse;
use Database\Seeders\RoleAndUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleAndUserSeeder::class);
    $this->admin = User::where('email', 'admin@sibatik.lan')->first();
});

test('admin can access all master data index pages', function () {
    $this->actingAs($this->admin)->get(route('master.vendors.index'))->assertStatus(200)->assertSee('Master Data Vendor');
    $this->actingAs($this->admin)->get(route('master.customers.index'))->assertStatus(200)->assertSee('Master Data Pelanggan');
    $this->actingAs($this->admin)->get(route('master.warehouses.index'))->assertStatus(200)->assertSee('Master Data Gudang');
    $this->actingAs($this->admin)->get(route('master.products.index'))->assertStatus(200)->assertSee('Master Data Produk');
});

test('can create, update, and delete a vendor', function () {
    // 1. Create
    $response = $this->actingAs($this->admin)->post(route('master.vendors.store'), [
        'code' => 'VND-TEST-01',
        'name' => 'CV Sumber Kain Pamekasan',
        'category' => 'Kain Mori',
        'contact_name' => 'Bpk. Hasan',
        'phone' => '081233445566',
        'email' => 'hasan@sumberkain.test',
        'city' => 'Pamekasan',
        'address' => 'Jl. Jokotole No. 12',
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('master.vendors.index'));
    $this->assertDatabaseHas('vendors', [
        'code' => 'VND-TEST-01',
        'name' => 'CV Sumber Kain Pamekasan',
    ]);

    $vendor = Vendor::where('code', 'VND-TEST-01')->first();

    // 2. Update
    $updateResponse = $this->actingAs($this->admin)->put(route('master.vendors.update', $vendor), [
        'code' => 'VND-TEST-01',
        'name' => 'CV Sumber Kain Pamekasan Sejahtera',
        'category' => 'Kain Mori Premium',
        'is_active' => 1,
    ]);

    $updateResponse->assertRedirect(route('master.vendors.index'));
    $this->assertDatabaseHas('vendors', [
        'id' => $vendor->id,
        'name' => 'CV Sumber Kain Pamekasan Sejahtera',
    ]);

    // 3. Delete
    $deleteResponse = $this->actingAs($this->admin)->delete(route('master.vendors.destroy', $vendor));
    $deleteResponse->assertRedirect(route('master.vendors.index'));
    $this->assertDatabaseMissing('vendors', [
        'id' => $vendor->id,
    ]);
});

test('rejects duplicate vendor code and name with clear error message', function () {
    Vendor::create([
        'code' => 'VND-DUP-01',
        'name' => 'PT Batik Asli Madura',
        'category' => 'kain',
        'email' => 'batik@asli.test',
        'phone' => '0811223344',
    ]);

    // Test duplicate code
    $responseCode = $this->actingAs($this->admin)->from(route('master.vendors.create'))->post(route('master.vendors.store'), [
        'code' => 'VND-DUP-01',
        'name' => 'PT Vendor Lain Berbeda',
        'category' => 'kain',
    ]);

    $responseCode->assertSessionHasErrors('code');

    // Test duplicate name (case-insensitive)
    $responseName = $this->actingAs($this->admin)->from(route('master.vendors.create'))->post(route('master.vendors.store'), [
        'code' => 'VND-BARU-99',
        'name' => 'pt batik asli madura',
        'category' => 'kain',
    ]);

    $responseName->assertSessionHasErrors('name');
});

test('can create, update, and delete a customer', function () {
    // 1. Create
    $response = $this->actingAs($this->admin)->post(route('master.customers.store'), [
        'code' => 'CUST-TEST-01',
        'name' => 'Galeri Batik Pamekasan Indah',
        'customer_type' => 'reseller',
        'contact_name' => 'Ibu Siti Aminah',
        'phone' => '087788990011',
        'email' => 'siti@galeriindah.test',
        'city' => 'Pamekasan',
        'address' => 'Jl. Trunojoyo No. 45',
        'special_discount' => 10.0,
        'credit_limit' => 20000000,
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('master.customers.index'));
    $this->assertDatabaseHas('customers', [
        'code' => 'CUST-TEST-01',
        'customer_type' => 'reseller',
    ]);

    $customer = Customer::where('code', 'CUST-TEST-01')->first();

    // 2. Update
    $updateResponse = $this->actingAs($this->admin)->put(route('master.customers.update', $customer), [
        'code' => 'CUST-TEST-01',
        'name' => 'Galeri Batik Pamekasan Indah Pusat',
        'customer_type' => 'reseller',
        'special_discount' => 15.0,
        'is_active' => 1,
    ]);

    $updateResponse->assertRedirect(route('master.customers.index'));
    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Galeri Batik Pamekasan Indah Pusat',
        'special_discount' => 15.0,
    ]);

    // 3. Delete
    $deleteResponse = $this->actingAs($this->admin)->delete(route('master.customers.destroy', $customer));
    $deleteResponse->assertRedirect(route('master.customers.index'));
    $this->assertDatabaseMissing('customers', [
        'id' => $customer->id,
    ]);
});

test('rejects duplicate customer code, phone, and email', function () {
    Customer::create([
        'code' => 'CUST-DUP-01',
        'name' => 'Toko Batik Lama',
        'customer_type' => 'umum',
        'email' => 'lama@toko.test',
        'phone' => '0855667788',
    ]);

    // Duplicate code
    $resCode = $this->actingAs($this->admin)->post(route('master.customers.store'), [
        'code' => 'CUST-DUP-01',
        'name' => 'Toko Baru',
        'customer_type' => 'umum',
    ]);
    $resCode->assertSessionHasErrors('code');

    // Duplicate phone
    $resPhone = $this->actingAs($this->admin)->post(route('master.customers.store'), [
        'code' => 'CUST-BARU-02',
        'name' => 'Toko Baru 2',
        'customer_type' => 'umum',
        'phone' => '0855667788',
    ]);
    $resPhone->assertSessionHasErrors('phone');

    // Duplicate email
    $resEmail = $this->actingAs($this->admin)->post(route('master.customers.store'), [
        'code' => 'CUST-BARU-03',
        'name' => 'Toko Baru 3',
        'customer_type' => 'umum',
        'email' => 'lama@toko.test',
    ]);
    $resEmail->assertSessionHasErrors('email');
});

test('can create, update, and delete a warehouse', function () {
    // 1. Create
    $response = $this->actingAs($this->admin)->post(route('master.warehouses.store'), [
        'code' => 'GDG-TEST-01',
        'name' => 'Gudang Kain Mori Proppo',
        'warehouse_type' => 'bahan',
        'location' => 'Lantai 1 Workshop',
        'capacity' => 10000,
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('master.warehouses.index'));
    $this->assertDatabaseHas('warehouses', [
        'code' => 'GDG-TEST-01',
        'warehouse_type' => 'bahan',
    ]);

    $warehouse = Warehouse::where('code', 'GDG-TEST-01')->first();

    // 2. Update
    $updateResponse = $this->actingAs($this->admin)->put(route('master.warehouses.update', $warehouse), [
        'code' => 'GDG-TEST-01',
        'name' => 'Gudang Bahan Baku Utama Proppo',
        'warehouse_type' => 'bahan',
        'is_active' => 1,
    ]);

    $updateResponse->assertRedirect(route('master.warehouses.index'));
    $this->assertDatabaseHas('warehouses', [
        'id' => $warehouse->id,
        'name' => 'Gudang Bahan Baku Utama Proppo',
    ]);

    // 3. Delete
    $deleteResponse = $this->actingAs($this->admin)->delete(route('master.warehouses.destroy', $warehouse));
    $deleteResponse->assertRedirect(route('master.warehouses.index'));
    $this->assertDatabaseMissing('warehouses', [
        'id' => $warehouse->id,
    ]);
});

test('rejects duplicate warehouse code and name', function () {
    Warehouse::create([
        'code' => 'GDG-DUP-01',
        'name' => 'Gudang Sentral Batik',
        'warehouse_type' => 'batik',
    ]);

    $resCode = $this->actingAs($this->admin)->post(route('master.warehouses.store'), [
        'code' => 'GDG-DUP-01',
        'name' => 'Gudang Cabang Baru',
        'warehouse_type' => 'batik',
    ]);
    $resCode->assertSessionHasErrors('code');

    $resName = $this->actingAs($this->admin)->post(route('master.warehouses.store'), [
        'code' => 'GDG-BARU-02',
        'name' => 'gudang sentral batik',
        'warehouse_type' => 'batik',
    ]);
    $resName->assertSessionHasErrors('name');
});

test('can create product with initial variants, update, and delete product', function () {
    // 1. Create with variants
    $response = $this->actingAs($this->admin)->post(route('master.products.store'), [
        'code' => 'BTK-TEST-01',
        'name' => 'Batik Tulis Gentongan Klampar',
        'category' => 'batik_tulis',
        'uom' => 'lembar',
        'description' => 'Motif khas Desa Klampar Pamekasan dengan pewarna alam',
        'is_active' => 1,
        'variants' => [
            [
                'size' => 'All Size',
                'pattern' => 'Klampar Klasik Hitam Merah',
                'cost_price' => 250000,
                'selling_price' => 450000,
                'min_stock' => 5,
            ],
            [
                'size' => 'All Size',
                'pattern' => 'Klampar Sekar Melati',
                'cost_price' => 300000,
                'selling_price' => 550000,
                'min_stock' => 3,
            ],
        ],
    ]);

    $product = Product::where('code', 'BTK-TEST-01')->first();
    expect($product)->not->toBeNull();
    $response->assertRedirect(route('master.products.show', $product));

    expect($product->variants()->count())->toBe(2);

    // 2. Update product
    $updateResponse = $this->actingAs($this->admin)->put(route('master.products.update', $product), [
        'code' => 'BTK-TEST-01',
        'name' => 'Batik Tulis Gentongan Klampar Super',
        'category' => 'batik_tulis',
        'uom' => 'lembar',
        'is_active' => 1,
    ]);

    $updateResponse->assertRedirect(route('master.products.show', $product));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Batik Tulis Gentongan Klampar Super',
    ]);

    // 3. Delete product cascades variants
    $deleteResponse = $this->actingAs($this->admin)->delete(route('master.products.destroy', $product));
    $deleteResponse->assertRedirect(route('master.products.index'));
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $this->assertDatabaseMissing('product_variants', ['product_id' => $product->id]);
});

test('rejects duplicate product code and name', function () {
    Product::create([
        'code' => 'BTK-EXIST-01',
        'name' => 'Batik Sekar Jagad',
        'category' => 'batik_tulis',
        'uom' => 'pcs',
    ]);

    $resCode = $this->actingAs($this->admin)->post(route('master.products.store'), [
        'code' => 'BTK-EXIST-01',
        'name' => 'Batik Varian Lain',
        'category' => 'batik_tulis',
        'uom' => 'pcs',
    ]);
    $resCode->assertSessionHasErrors('code');

    $resName = $this->actingAs($this->admin)->post(route('master.products.store'), [
        'code' => 'BTK-NEW-02',
        'name' => 'batik sekar jagad',
        'category' => 'batik_tulis',
        'uom' => 'pcs',
    ]);
    $resName->assertSessionHasErrors('name');
});

test('can manage product variants and rejects duplicate size/pattern combination', function () {
    $product = Product::create([
        'code' => 'BTK-VAR-TEST',
        'name' => 'Kemeja Batik Pria Moderen',
        'category' => 'pakaian',
        'uom' => 'pcs',
    ]);

    // 1. Add variant S
    $addResponse = $this->actingAs($this->admin)->post(route('master.products.variants.store', $product), [
        'size' => 'S',
        'pattern' => 'Motif Daun Memey',
        'cost_price' => 120000,
        'selling_price' => 200000,
        'min_stock' => 5,
        'current_stock' => 10,
    ]);

    $addResponse->assertRedirect(route('master.products.show', $product));
    $this->assertDatabaseHas('product_variants', [
        'product_id' => $product->id,
        'size' => 'S',
        'pattern' => 'Motif Daun Memey',
    ]);

    // 2. Reject duplicate size + pattern on same product
    $dupResponse = $this->actingAs($this->admin)->post(route('master.products.variants.store', $product), [
        'size' => 'S',
        'pattern' => 'motif daun memey', // case-insensitive check
        'cost_price' => 120000,
        'selling_price' => 200000,
        'min_stock' => 5,
    ]);
    $dupResponse->assertSessionHasErrors('size');

    // 3. Update existing variant
    $variant = ProductVariant::where('product_id', $product->id)->where('size', 'S')->first();
    $updateVarResponse = $this->actingAs($this->admin)->put(route('master.products.variants.update', [$product, $variant]), [
        'size' => 'S',
        'pattern' => 'Motif Daun Memey Emas',
        'cost_price' => 130000,
        'selling_price' => 220000,
        'min_stock' => 8,
        'current_stock' => 15,
    ]);
    $updateVarResponse->assertRedirect(route('master.products.show', $product));
    $this->assertDatabaseHas('product_variants', [
        'id' => $variant->id,
        'pattern' => 'Motif Daun Memey Emas',
        'selling_price' => 220000,
    ]);

    // 4. Delete variant
    $delVarResponse = $this->actingAs($this->admin)->delete(route('master.products.variants.destroy', [$product, $variant]));
    $delVarResponse->assertRedirect(route('master.products.show', $product));
    $this->assertDatabaseMissing('product_variants', [
        'id' => $variant->id,
    ]);
});
