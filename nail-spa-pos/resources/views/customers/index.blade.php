@extends('layouts.app')

@section('title', 'Customers - Nail Spa POS')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>👥 Customers</h2>
        <a href="{{ route('customers.create') }}" class="btn">Add Customer</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Visits</th>
                <th>Lifetime Value</th>
                <th>Last Visit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->full_name }}</td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    <td>{{ $customer->total_visits }}</td>
                    <td>${{ number_format($customer->lifetime_value, 2) }}</td>
                    <td>{{ $customer->last_visit ? $customer->last_visit->format('M d, Y') : 'Never' }}</td>
                    <td>
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">View</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
