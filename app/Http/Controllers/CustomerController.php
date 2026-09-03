<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        $customerCode = Customer::generateCode();
        return view('customers.create', compact('customerCode'));
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        if ($request->has('phone')) {
            $request->merge(['phone' => $this->normalizePhone($request->phone)]);
        }

        if (!$request->filled('customer_code')) {
            $request->merge(['customer_code' => Customer::generateCode()]);
        }

        $validated = $request->validate([
            'customer_code' => 'required|unique:customers',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
        ]);

        $validated['status'] = 'Active';
        $validated['total_purchases'] = 0;

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        $customer->load('salesInvoices');
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing customer
     */
    public function edit(Customer $customer)
    {
        return view('customers.create', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer)
    {
        if ($request->has('phone')) {
            $request->merge(['phone' => $this->normalizePhone($request->phone)]);
        }

        $validated = $request->validate([
            'customer_code' => 'required|unique:customers,customer_code,' . $customer->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

    /**
     * API endpoint for quick customer add
     */
    public function quickStore(Request $request)
    {
        if ($request->has('phone')) {
            $request->merge(['phone' => $this->normalizePhone($request->phone)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Generate customer code
        $validated['customer_code'] = Customer::generateCode();
        $validated['status'] = 'Active';
        $validated['total_purchases'] = 0;

        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'customer' => $customer,
        ]);
    }

    /**
     * Helper to normalize phone numbers into international E.164 format
     */
    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) return null;
        $trimmed = trim($phone);
        if (str_starts_with($trimmed, '+')) {
            $digits = preg_replace('/[^0-9]/', '', substr($trimmed, 1));
            return $digits ? '+' . $digits : null;
        }

        $digits = preg_replace('/[^0-9]/', '', $trimmed);
        if (str_starts_with($digits, '62')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }
        return $digits ? '+62' . $digits : null;
    }

    /**
     * Export customers data
     */
    public function export(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name', 'asc')->get();

        $filename = 'daftar_customer_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID Customer', 'Nama', 'Email', 'No Telepon', 'Alamat', 'Kota']);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->customer_code,
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->address,
                    $customer->city,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
