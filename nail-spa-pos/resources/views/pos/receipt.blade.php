@extends('layouts.app')

@section('title', 'Receipt - Nail Spa POS')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="text-align: center; border-bottom: 2px dashed #ddd; padding-bottom: 1rem; margin-bottom: 1rem;">
        <h2 style="color: #667eea;">💅 Nail Spa POS</h2>
        <p>Thank you for your business!</p>
    </div>

    <div style="margin-bottom: 1rem;">
        <p><strong>Invoice #:</strong> {{ $transaction->invoice_number }}</p>
        <p><strong>Date:</strong> {{ $transaction->created_at->format('M d, Y h:i A') }}</p>
        @if($transaction->customer)
            <p><strong>Customer:</strong> {{ $transaction->customer->full_name }}</p>
        @endif
        @if($transaction->staff)
            <p><strong>Technician:</strong> {{ $transaction->staff->full_name }}</p>
        @endif
        <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="border-top: 2px dashed #ddd; padding-top: 1rem; margin-top: 1rem;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Subtotal:</span>
            <span>${{ number_format($transaction->subtotal, 2) }}</span>
        </div>
        @if($transaction->tax_amount > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Tax:</span>
                <span>${{ number_format($transaction->tax_amount, 2) }}</span>
            </div>
        @endif
        @if($transaction->discount_amount > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #28a745;">
                <span>Discount:</span>
                <span>-${{ number_format($transaction->discount_amount, 2) }}</span>
            </div>
        @endif
        <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold; margin-top: 1rem;">
            <span>Total:</span>
            <span>${{ number_format($transaction->total_amount, 2) }}</span>
        </div>
    </div>

    @if($transaction->notes)
        <div style="margin-top: 1rem; padding: 0.5rem; background: #f8f9fa; border-radius: 4px;">
            <strong>Notes:</strong> {{ $transaction->notes }}
        </div>
    @endif

    <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 2px dashed #ddd;">
        <p>We look forward to seeing you again!</p>
        <button onclick="window.print()" class="btn" style="margin-top: 1rem;">Print Receipt</button>
        <a href="{{ route('pos.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">New Sale</a>
    </div>
</div>
@endsection
