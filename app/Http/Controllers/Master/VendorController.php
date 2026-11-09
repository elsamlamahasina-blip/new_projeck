<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\Vendor;
use App\Services\MasterDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function __construct(
        protected MasterDataService $service
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');

        $vendors = Vendor::query()
            ->when($search, function ($query, $term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.vendors.index', compact('vendors', 'search'));
    }

    public function create(): View
    {
        return view('master.vendors.form', [
            'vendor' => new Vendor(),
            'isEdit' => false,
        ]);
    }

    public function store(VendorRequest $request): RedirectResponse
    {
        $vendor = $this->service->createVendor($request->validated());

        return redirect()->route('master.vendors.index')
            ->with('success', "Vendor '{$vendor->name}' ({$vendor->code}) berhasil ditambahkan ke sistem.");
    }

    public function edit(Vendor $vendor): View
    {
        return view('master.vendors.form', [
            'vendor' => $vendor,
            'isEdit' => true,
        ]);
    }

    public function update(VendorRequest $request, Vendor $vendor): RedirectResponse
    {
        $this->service->updateVendor($vendor, $request->validated());

        return redirect()->route('master.vendors.index')
            ->with('success', "Data vendor '{$vendor->name}' berhasil diperbarui.");
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $name = $vendor->name;
        $this->service->deleteVendor($vendor);

        return redirect()->route('master.vendors.index')
            ->with('success', "Vendor '{$name}' telah dinonaktifkan/dihapus.");
    }
}
