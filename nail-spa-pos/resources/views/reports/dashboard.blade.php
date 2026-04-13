@extends('layouts.app')

@section('title', 'Dashboard - Nail Spa POS')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <h3>${{ number_format($todaySales, 2) }}</h3>
        <p>Today's Sales</p>
    </div>
    <div class="stat-card">
        <h3>{{ $todayTransactions }}</h3>
        <p>Transactions Today</p>
    </div>
    <div class="stat-card">
        <h3>{{ $todayAppointments }}</h3>
        <p>Appointments Today</p>
    </div>
    <div class="stat-card">
        <h3>{{ $lowStockItems }}</h3>
        <p>Low Stock Items</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
    <div class="card">
        <h2>📊 Sales Overview</h2>
        <p><strong>This Week:</strong> ${{ number_format($weeklySales, 2) }}</p>
        <p><strong>This Month:</strong> ${{ number_format($monthlySales, 2) }}</p>
    </div>

    <div class="card">
        <h2>⭐ Top Services</h2>
        @if($topServices->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topServices as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->total_sold }}</td>
                            <td>${{ number_format($service->revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No service data available yet.</p>
        @endif
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <h2>🚀 Quick Actions</h2>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
        <a href="{{ route('pos.index') }}" class="btn">New Sale</a>
        <a href="{{ route('appointments.create') }}" class="btn btn-secondary">Book Appointment</a>
        <a href="{{ route('customers.create') }}" class="btn btn-secondary">Add Customer</a>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">View Inventory</a>
    </div>
</div>
@endsection
