@extends('layouts.app')

@section('title', 'Dashboard - Nail Spa POS')

@section('content')
<h2 style="margin-bottom: 1.5rem;">Dashboard Overview</h2>

<div class="stats-grid">
    <div class="stat-card">
        <h3>${{ number_format($todaySales, 2) }}</h3>
        <p>Today's Sales</p>
    </div>
    <div class="stat-card">
        <h3>{{ $todayTransactions }}</h3>
        <p>Today's Transactions</p>
    </div>
    <div class="stat-card">
        <h3>${{ number_format($monthlySales, 2) }}</h3>
        <p>Monthly Sales</p>
    </div>
    <div class="stat-card">
        <h3>{{ $monthlyTransactions }}</h3>
        <p>Monthly Transactions</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Top Services</h3>
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Total Sold</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topServices as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->total_sold }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2">No data available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem;">Top Customers</h3>
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Total Spent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topCustomers as $customer)
                <tr>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>${{ number_format($customer->total_spent, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2">No customers yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <h3 style="margin-bottom: 1rem;">Upcoming Appointments</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Customer</th>
                <th>Staff</th>
                <th>Service</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($upcomingAppointments as $appointment)
            <tr>
                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                <td>{{ $appointment->appointment_time->format('g:i A') }}</td>
                <td>{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</td>
                <td>{{ $appointment->staff->first_name }} {{ $appointment->staff->last_name }}</td>
                <td>{{ $appointment->service->name }}</td>
                <td>
                    <span class="badge badge-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'info') }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No upcoming appointments</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
