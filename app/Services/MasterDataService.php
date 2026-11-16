<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MasterDataService
{
    /**
     * ==========================================
     * 1. VENDOR SERVICES
     * ==========================================
     */

    /**
     * Deteksi potensi duplikasi data vendor.
     */
    public function validateUniqueVendor(array $data, ?int $ignoreId = null): void
    {
        $code = trim($data['code'] ?? '');
        $name = trim($data['name'] ?? '');
        $email = isset($data['email']) ? trim($data['email']) : null;
        $phone = isset($data['phone']) ? trim($data['phone']) : null;

        // Cek duplikasi kode vendor
        if ($code !== '') {
            $query = Vendor::where('code', $code);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'code' => "Kode vendor '{$code}' sudah terdaftar. Gunakan kode unik lain.",
                ]);
            }
        }

        // Cek duplikasi nama vendor (case-insensitive)
        if ($name !== '') {
            $query = Vendor::whereRaw('LOWER(name) = ?', [strtolower($name)]);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'name' => "Nama vendor '{$name}' sudah ada dalam sistem. Harap periksa kembali untuk menghindari data redundan.",
                ]);
            }
        }

        // Cek duplikasi email vendor
        if (! empty($email)) {
            $query = Vendor::where('email', $email);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'email' => "Email vendor '{$email}' sudah digunakan oleh vendor lain.",
                ]);
            }
        }

        // Cek duplikasi nomor telepon vendor
        if (! empty($phone)) {
            $query = Vendor::where('phone', $phone);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'phone' => "Nomor telepon vendor '{$phone}' sudah terdaftar.",
                ]);
            }
        }
    }

    public function createVendor(array $data): Vendor
    {
        if (isset($data['contact_name']) && !isset($data['contact_person'])) {
            $data['contact_person'] = $data['contact_name'];
            unset($data['contact_name']);
        }

        $this->validateUniqueVendor($data);

        return Vendor::create($data);
    }

    public function updateVendor(Vendor $vendor, array $data): Vendor
    {
        if (isset($data['contact_name']) && !isset($data['contact_person'])) {
            $data['contact_person'] = $data['contact_name'];
            unset($data['contact_name']);
        }

        $this->validateUniqueVendor($data, $vendor->id);

        $vendor->update($data);

        return $vendor;
    }

    public function deleteVendor(Vendor $vendor): bool
    {
        return $vendor->delete();
    }

    /**
     * ==========================================
     * 2. CUSTOMER SERVICES
     * ==========================================
     */

    /**
     * Deteksi potensi duplikasi data pelanggan.
     */
    public function validateUniqueCustomer(array $data, ?int $ignoreId = null): void
    {
        $code = trim($data['code'] ?? '');
        $name = trim($data['name'] ?? '');
        $email = isset($data['email']) ? trim($data['email']) : null;
        $phone = isset($data['phone']) ? trim($data['phone']) : null;

        // Cek duplikasi kode pelanggan
        if ($code !== '') {
            $query = Customer::where('code', $code);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'code' => "Kode pelanggan '{$code}' sudah terdaftar.",
                ]);
            }
        }

        // Cek duplikasi nama pelanggan (case-insensitive)
        if ($name !== '') {
            $query = Customer::whereRaw('LOWER(name) = ?', [strtolower($name)]);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'name' => "Nama pelanggan '{$name}' sudah ada dalam sistem. Pastikan tidak terjadi duplikasi akun pelanggan.",
                ]);
            }
        }

        // Cek email
        if (! empty($email)) {
            $query = Customer::where('email', $email);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'email' => "Email '{$email}' sudah terdaftar pada pelanggan lain.",
                ]);
            }
        }

        // Cek telepon
        if (! empty($phone)) {
            $query = Customer::where('phone', $phone);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'phone' => "Nomor telepon '{$phone}' sudah digunakan oleh pelanggan lain.",
                ]);
            }
        }
    }

    public function createCustomer(array $data): Customer
    {
        $this->validateUniqueCustomer($data);

        return Customer::create($data);
    }

    public function updateCustomer(Customer $customer, array $data): Customer
    {
        $this->validateUniqueCustomer($data, $customer->id);

        $customer->update($data);

        return $customer;
    }

    public function deleteCustomer(Customer $customer): bool
    {
        return $customer->delete();
    }

    /**
     * ==========================================
     * 3. WAREHOUSE SERVICES
     * ==========================================
     */

    /**
     * Deteksi duplikasi gudang.
     */
    public function validateUniqueWarehouse(array $data, ?int $ignoreId = null): void
    {
        $code = trim($data['code'] ?? '');
        $name = trim($data['name'] ?? '');

        if ($code !== '') {
            $query = Warehouse::where('code', $code);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'code' => "Kode gudang '{$code}' sudah digunakan.",
                ]);
            }
        }

        if ($name !== '') {
            $query = Warehouse::whereRaw('LOWER(name) = ?', [strtolower($name)]);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'name' => "Nama gudang '{$name}' sudah terdaftar.",
                ]);
            }
        }
    }

    public function createWarehouse(array $data): Warehouse
    {
        $this->validateUniqueWarehouse($data);

        return Warehouse::create($data);
    }

    public function updateWarehouse(Warehouse $warehouse, array $data): Warehouse
    {
        $this->validateUniqueWarehouse($data, $warehouse->id);

        $warehouse->update($data);

        return $warehouse;
    }

    public function deleteWarehouse(Warehouse $warehouse): bool
    {
        return $warehouse->delete();
    }

    /**
     * ==========================================
     * 4. PRODUCT & VARIANT SERVICES
     * ==========================================
     */

    /**
     * Deteksi duplikasi produk induk.
     */
    public function validateUniqueProduct(array $data, ?int $ignoreId = null): void
    {
        $code = trim($data['code'] ?? '');
        $name = trim($data['name'] ?? '');

        if ($code !== '') {
            $query = Product::where('code', $code);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'code' => "Kode produk '{$code}' sudah ada. Gunakan kode unik lain.",
                ]);
            }
        }

        if ($name !== '') {
            $query = Product::whereRaw('LOWER(name) = ?', [strtolower($name)]);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'name' => "Nama produk batik '{$name}' sudah ada dalam katalog.",
                ]);
            }
        }
    }

    /**
     * Deteksi duplikasi varian produk (kombinasi ukuran dan motif di bawah produk yang sama).
     */
    public function validateUniqueVariant(int $productId, array $variantData, ?int $ignoreVariantId = null): void
    {
        $sku = trim($variantData['sku'] ?? '');
        $size = trim($variantData['size'] ?? '');
        $pattern = trim($variantData['pattern'] ?? '');

        // Cek SKU unik global
        if ($sku !== '') {
            $query = ProductVariant::where('sku', $sku);
            if ($ignoreVariantId) {
                $query->where('id', '!=', $ignoreVariantId);
            }
            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'sku' => "SKU varian '{$sku}' sudah digunakan pada varian lain.",
                ]);
            }
        }

        // Cek duplikasi ukuran dan motif pada produk yang sama
        if ($size !== '' && $pattern !== '') {
            $query = ProductVariant::where('product_id', $productId)
                ->where('size', $size)
                ->whereRaw('LOWER(pattern) = ?', [strtolower($pattern)]);

            if ($ignoreVariantId) {
                $query->where('id', '!=', $ignoreVariantId);
            }

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'size' => "Varian ukuran '{$size}' dengan motif '{$pattern}' sudah terdaftar untuk produk ini.",
                ]);
            }
        }
    }

    /**
     * Buat produk beserta varian pertamanya secara transaksional.
     */
    public function createProduct(array $productData, array $variants = []): Product
    {
        $this->validateUniqueProduct($productData);

        return DB::transaction(function () use ($productData, $variants) {
            $product = Product::create($productData);

            if (! empty($variants)) {
                foreach ($variants as $variant) {
                    if (empty($variant['sku'])) {
                        $variant['sku'] = $this->generateVariantSku($product, $variant['size'] ?? 'DEF', $variant['pattern'] ?? 'ALL');
                    }
                    $this->validateUniqueVariant($product->id, $variant);
                    $product->variants()->create($variant);
                }
            } else {
                // Buat varian default jika tidak disediakan
                $defaultSku = strtoupper($product->code).'-DEF';
                $product->variants()->create([
                    'sku' => $defaultSku,
                    'size' => 'All Size',
                    'pattern' => $product->name,
                    'cost_price' => 0,
                    'selling_price' => 0,
                    'min_stock' => 5,
                    'current_stock' => 0,
                ]);
            }

            return $product->fresh(['variants']);
        });
    }

    public function generateVariantSku(Product $product, string $size, string $pattern): string
    {
        $cleanSize = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $size));
        $cleanPattern = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $pattern), 0, 6));
        $baseSku = strtoupper($product->code).'-'.($cleanSize ?: 'ALL').'-'.($cleanPattern ?: 'VAR');

        $sku = $baseSku;
        $counter = 1;
        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = $baseSku.'-'.$counter++;
        }

        return $sku;
    }

    public function updateProduct(Product $product, array $productData): Product
    {
        $this->validateUniqueProduct($productData, $product->id);

        $product->update($productData);

        return $product;
    }

    public function saveVariant(Product $product, array $variantData, ?int $variantId = null): ProductVariant
    {
        if (empty($variantData['sku'])) {
            $variantData['sku'] = $this->generateVariantSku($product, $variantData['size'] ?? 'DEF', $variantData['pattern'] ?? 'ALL');
        }

        $this->validateUniqueVariant($product->id, $variantData, $variantId);

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);
            $variant->update($variantData);

            return $variant;
        }

        return $product->variants()->create($variantData);
    }

    public function deleteVariant(ProductVariant $variant): bool
    {
        return $variant->delete();
    }

    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }
}
