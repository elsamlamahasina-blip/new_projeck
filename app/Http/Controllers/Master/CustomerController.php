<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Services\MasterDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        protected MasterDataService $service
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $type = $request->query('type');

        $customers = Customer::query()
            ->when($search, function ($query, $term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            })
            ->when($type, function ($query, $selectedType) {
                $query->where('customer_type', $selectedType);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.customers.index', compact('customers', 'search', 'type'));
    }

    public function create(): View
    {
        return view('master.customers.form', [
            'customer' => new Customer(['customer_type' => 'umum', 'special_discount' => 0]),
            'isEdit' => false,
        ]);
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $customer = $this->service->createCustomer($request->validated());

        return redirect()->route('master.customers.index')
            ->with('success', "Pelanggan '{$customer->name}' ({$customer->code}) berhasil didaftarkan.");
    }

    public function edit(Customer $customer): View
    {
        return view('master.customers.form', [
            'customer' => $customer,
            'isEdit' => true,
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->service->updateCustomer($customer, $request->validated());

        return redirect()->route('master.customers.index')
            ->with('success', "Data pelanggan '{$customer->name}' berhasil diperbarui.");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $name = $customer->name;
        $this->service->deleteCustomer($customer);

        return redirect()->route('master.customers.index')
            ->with('success', "Pelanggan '{$name}' telah dihapus.");
    }
}
