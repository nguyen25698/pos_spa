@extends('layouts.app')

@section('title', 'Inventory - Nail Spa POS')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>📦 Inventory Management</h2>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">All Items</a>
            <a href="{{ route('inventory.index', ['low_stock' => 1]) }}" class="btn btn-danger">Low Stock</a>
            <a href="{{ route('inventory.create') }}" class="btn">Add New Item</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Min Qty</th>
                <th>Cost Price</th>
                <th>Sale Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory as $item)
                <tr style="{{ $item->isLowStock() ? 'background: #fff3cd;' : '' }}">
                    <td>{{ $item->name }}</td>
                    <td><span class="badge badge-info">{{ $item->category }}</span></td>
                    <td>
                        {{ $item->quantity }} {{ $item->unit }}
                        @if($item->isLowStock())
                            <span class="badge badge-danger">Low Stock!</span>
                        @endif
                    </td>
                    <td>{{ $item->min_quantity }}</td>
                    <td>${{ number_format($item->cost_price, 2) }}</td>
                    <td>${{ number_format($item->sale_price, 2) }}</td>
                    <td>
                        @if($item->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('inventory.show', $item) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">View</a>
                        <a href="{{ route('inventory.edit', $item) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No inventory items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $inventory->links() }}
    </div>
</div>
@endsection
