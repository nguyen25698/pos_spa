<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::orderBy('category')->orderBy('name')->paginate(20);
        $lowStock = Inventory::whereColumn('quantity', '<=', 'reorder_level')->get();
        return view('inventory.index', compact('inventory', 'lowStock'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'sku' => 'required|string|unique:inventory,sku|max:50',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Inventory::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Inventory item added successfully.');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'sku' => 'required|string|unique:inventory,sku,' . $inventory->id . '|max:50',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
    }
}
