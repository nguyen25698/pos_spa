@extends('layouts.app')

@section('title', 'Inventory - Nail Spa POS')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Inventory Management</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary">+ Add Item</a>
</div>

@if($lowStock->count() > 0)
<div class="card" style="border-left: 4px solid #dc3545;">
    <h3 style="color: #dc3545; margin-bottom: 1rem;">⚠️ Low Stock Alert</h3>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th>Current Qty</th>
                <th>Reorder Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStock as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->sku }}</td>
                <td style="color: #dc3545; font-weight: bold;">{{ $item->quantity }}</td>
                <td>{{ $item->reorder_level }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Supplier</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category ?? '-' }}</td>
                <td>{{ $item->sku }}</td>
                <td>
                    @if($item->isLowStock())
                        <span style="color: #dc3545; font-weight: bold;">{{ $item->quantity }}</span>
                    @else
                        {{ $item->quantity }}
                    @endif
                </td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->supplier ?? '-' }}</td>
                <td>
                    <a href="{{ route('inventory.edit', $item) }}" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Edit</a>
                    <form action="{{ route('inventory.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No inventory items found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 1rem;">
        {{ $inventory->links() }}
    </div>
</div>
@endsection
