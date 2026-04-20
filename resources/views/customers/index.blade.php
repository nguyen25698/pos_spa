@extends('layouts.app')

@section('title', 'Customers - Nail Spa POS')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Customers</h2>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Add Customer</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Total Visits</th>
                <th>Total Spent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                <td>{{ $customer->email ?? '-' }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td>{{ $customer->total_visits }}</td>
                <td>${{ number_format($customer->total_spent, 2) }}</td>
                <td>
                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">View</a>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-success" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No customers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 1rem;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
