<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use App\Services\MasterDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function __construct(
        protected MasterDataService $service
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');

        $warehouses = Warehouse::query()
            ->when($search, function ($query, $term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%")
                    ->orWhere('warehouse_type', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.warehouses.index', compact('warehouses', 'search'));
    }

    public function create(): View
    {
        return view('master.warehouses.form', [
            'warehouse' => new Warehouse(['warehouse_type' => 'batik']),
            'isEdit' => false,
        ]);
    }

    public function store(WarehouseRequest $request): RedirectResponse
    {
        $warehouse = $this->service->createWarehouse($request->validated());

        return redirect()->route('master.warehouses.index')
            ->with('success', "Gudang '{$warehouse->name}' ({$warehouse->code}) berhasil ditambahkan.");
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('master.warehouses.form', [
            'warehouse' => $warehouse,
            'isEdit' => true,
        ]);
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $this->service->updateWarehouse($warehouse, $request->validated());

        return redirect()->route('master.warehouses.index')
            ->with('success', "Informasi gudang '{$warehouse->name}' berhasil diperbarui.");
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $name = $warehouse->name;
        $this->service->deleteWarehouse($warehouse);

        return redirect()->route('master.warehouses.index')
            ->with('success', "Gudang '{$name}' telah dinonaktifkan.");
    }
}
