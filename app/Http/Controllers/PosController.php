<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        $customers = Customer::orderBy('last_name')->get();
        $staff = Staff::where('is_active', true)->orderBy('last_name')->get();
        return view('pos.index', compact('services', 'customers', 'staff'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'staff_id' => 'nullable|exists:staff,id',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'staff_id' => $validated['staff_id'] ?? null,
                'subtotal' => $validated['subtotal'],
                'tax_amount' => $validated['tax_amount'],
                'discount_amount' => $validated['discount_amount'],
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create transaction items
            foreach ($validated['items'] as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'service_id' => $item['service_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);
            }

            // Update customer stats if applicable
            if ($validated['customer_id']) {
                $customer = Customer::find($validated['customer_id']);
                $customer->increment('total_visits');
                $customer->increment('total_spent', $validated['total_amount']);
            }

            DB::commit();

            return redirect()->route('pos.receipt', $transaction->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['customer', 'staff', 'items.service']);
        return view('pos.receipt', compact('transaction'));
    }
}
