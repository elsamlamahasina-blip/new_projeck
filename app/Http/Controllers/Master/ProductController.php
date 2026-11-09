<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\MasterDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected MasterDataService $service
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $products = Product::with('variants')
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('code', 'like', "%{$term}%")
                        ->orWhereHas('variants', function ($vq) use ($term) {
                            $vq->where('sku', 'like', "%{$term}%")
                                ->orWhere('pattern', 'like', "%{$term}%");
                        });
                });
            })
            ->when($category, function ($query, $cat) {
                $query->where('category', $cat);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.products.index', compact('products', 'search', 'category'));
    }

    public function create(): View
    {
        return view('master.products.form', [
            'product' => new Product(['category' => 'batik_tulis', 'uom' => 'pcs']),
            'isEdit' => false,
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $variants = $validated['variants'] ?? [];
        unset($validated['variants']);

        $product = $this->service->createProduct($validated, $variants);

        return redirect()->route('master.products.show', $product)
            ->with('success', "Produk '{$product->name}' ({$product->code}) berhasil disimpan.");
    }

    public function show(Product $product): View
    {
        $product->load('variants');

        return view('master.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('master.products.form', [
            'product' => $product,
            'isEdit' => true,
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['variants']);

        $this->service->updateProduct($product, $validated);

        return redirect()->route('master.products.show', $product)
            ->with('success', "Informasi produk '{$product->name}' berhasil diperbarui.");
    }

    public function storeVariant(ProductVariantRequest $request, Product $product): RedirectResponse
    {
        $variant = $this->service->saveVariant($product, $request->validated());

        return redirect()->route('master.products.show', $product)
            ->with('success', "Varian '{$variant->sku}' ({$variant->size} - {$variant->pattern}) berhasil ditambahkan.");
    }

    public function updateVariant(ProductVariantRequest $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->service->saveVariant($product, $request->validated(), $variant->id);

        return redirect()->route('master.products.show', $product)
            ->with('success', "Varian '{$variant->sku}' berhasil diperbarui.");
    }

    public function destroyVariant(Product $product, ProductVariant $variant): RedirectResponse
    {
        $sku = $variant->sku;
        $this->service->deleteVariant($variant);

        return redirect()->route('master.products.show', $product)
            ->with('success', "Varian '{$sku}' berhasil dihapus.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $this->service->deleteProduct($product);

        return redirect()->route('master.products.index')
            ->with('success', "Produk '{$name}' beserta seluruh variannya berhasil dihapus.");
    }
}
