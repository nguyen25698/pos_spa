@extends('layouts.app')

@section('title', 'Sales Reports - Nail Spa POS')

@section('content')
<h2 style="margin-bottom: 1.5rem;">Sales Reports</h2>

<div class="card">
    <form method="GET" action="{{ route('reports.sales') }}" style="display: flex; gap: 1rem; align-items: flex-end; margin-bottom: 1.5rem;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate instanceof \DateTime ? $startDate->format('Y-m-d') : $startDate }}">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ $endDate instanceof \DateTime ? $endDate->format('Y-m-d') : $endDate }}">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    
    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <div class="stat-card" style="flex: 1;">
            <h3>${{ number_format($totalSales, 2) }}</h3>
            <p>Total Sales</p>
        </div>
        <div class="stat-card" style="flex: 1;">
            <h3>{{ $totalTransactions }}</h3>
            <p>Transactions</p>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction ID</th>
                <th>Customer</th>
                <th>Staff</th>
                <th>Payment Method</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('M d, Y g:i A') }}</td>
                <td>#{{ $transaction->id }}</td>
                <td>{{ $transaction->customer ? $transaction->customer->first_name . ' ' . $transaction->customer->last_name : 'Walk-in' }}</td>
                <td>{{ $transaction->staff ? $transaction->staff->first_name . ' ' . $transaction->staff->last_name : '-' }}</td>
                <td>{{ ucfirst($transaction->payment_method) }}</td>
                <td>${{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No transactions found for this period.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 1rem;">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
