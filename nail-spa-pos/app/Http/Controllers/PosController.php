<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Service;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('last_name')->get();
        $staff = Staff::active()->orderBy('last_name')->get();
        $services = Service::active()->orderBy('category')->orderBy('name')->get();
        $products = Inventory::active()->where('sale_price', '>', 0)->get();
        
        return view('pos.index', compact('customers', 'staff', 'services', 'products'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'staff_id' => 'nullable|exists:staff,id',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:service,product',
            'items.*.id' => 'required_if:items.*.item_type,service|exists:services,id|nullable',
            'items.*.inventory_id' => 'required_if:items.*.item_type,product|exists:inventory,id|nullable',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'numeric|min:0|max:100',
            'discount_amount' => 'numeric|min:0',
            'payment_method' => 'required|in:cash,credit,debit,gift_card',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create transaction
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['unit_price'] * $item['quantity'];
            }

            $taxRate = $validated['tax_rate'] ?? 0;
            $taxAmount = ($subtotal * $taxRate) / 100;
            $discountAmount = $validated['discount_amount'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            $transaction = Transaction::create([
                'customer_id' => $validated['customer_id'],
                'staff_id' => $validated['staff_id'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'completed',
            ]);

            // Create transaction items
            foreach ($validated['items'] as $itemData) {
                $item = [
                    'transaction_id' => $transaction->id,
                    'item_type' => $itemData['item_type'],
                    'name' => $itemData['name'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['unit_price'] * $itemData['quantity'],
                ];

                if ($itemData['item_type'] === 'service') {
                    $item['service_id'] = $itemData['id'];
                } else {
                    $item['inventory_id'] = $itemData['inventory_id'];
                    // Decrement inventory
                    $inventory = Inventory::find($itemData['inventory_id']);
                    $inventory->decrementQuantity($itemData['quantity']);
                }

                TransactionItem::create($item);
            }

            // Update customer stats
            if ($validated['customer_id']) {
                $customer = Customer::find($validated['customer_id']);
                $customer->incrementVisits();
                $customer->addToLifetimeValue($totalAmount);
            }

            DB::commit();

            return redirect()->route('pos.receipt', $transaction)
                ->with('success', 'Transaction completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['customer', 'staff', 'items']);
        return view('pos.receipt', compact('transaction'));
    }

    public function recentTransactions()
    {
        $transactions = Transaction::with(['customer', 'staff', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('pos.transactions', compact('transactions'));
    }
}
