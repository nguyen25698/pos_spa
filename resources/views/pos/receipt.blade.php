@extends('layouts.app')

@section('title', 'Receipt - Nail Spa POS')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="text-align: center; padding: 2rem 0; border-bottom: 2px dashed #ddd; margin-bottom: 1.5rem;">
        <h2 style="color: #d4a5a5;">💅 Nail Spa Salon</h2>
        <p style="color: #666; margin-top: 0.5rem;">Thank you for your visit!</p>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <p><strong>Receipt #:</strong> {{ $transaction->id }}</p>
        <p><strong>Date:</strong> {{ $transaction->created_at->format('M d, Y g:i A') }}</p>
        @if($transaction->customer)
        <p><strong>Customer:</strong> {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}</p>
        @endif
        @if($transaction->staff)
        <p><strong>Technician:</strong> {{ $transaction->staff->first_name }} {{ $transaction->staff->last_name }}</p>
        @endif
        <p><strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) }}</p>
    </div>
    
    <table style="margin-bottom: 1.5rem;">
        <thead>
            <tr>
                <th>Service</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
            <tr>
                <td>{{ $item->service ? $item->service->name : 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>${{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="border-top: 2px dashed #ddd; padding-top: 1rem;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Subtotal:</span>
            <span>${{ number_format($transaction->subtotal, 2) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Tax:</span>
            <span>${{ number_format($transaction->tax_amount, 2) }}</span>
        </div>
        @if($transaction->discount_amount > 0)
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Discount:</span>
            <span>-${{ number_format($transaction->discount_amount, 2) }}</span>
        </div>
        @endif
        <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd;">
            <span>Total Paid:</span>
            <span>${{ number_format($transaction->total_amount, 2) }}</span>
        </div>
    </div>
    
    @if($transaction->notes)
    <div style="margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
        <strong>Notes:</strong>
        <p style="margin-top: 0.5rem;">{{ $transaction->notes }}</p>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 2px dashed #ddd;">
        <p style="color: #666;">We appreciate your business!</p>
        <p style="color: #666; font-size: 0.875rem;">Please come again</p>
    </div>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="{{ route('pos.index') }}" class="btn btn-primary">New Transaction</a>
        <button onclick="window.print()" class="btn btn-success" style="margin-left: 0.5rem;">Print Receipt</button>
    </div>
</div>
@endsection
