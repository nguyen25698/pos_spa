@extends('layouts.app')

@section('title', 'Services - Nail Spa POS')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Services Management</h2>
    <a href="{{ route('services.create') }}" class="btn btn-primary">+ Add New Service</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ $service->category ?? '-' }}</td>
                <td>${{ number_format($service->price, 2) }}</td>
                <td>{{ $service->duration_minutes }} min</td>
                <td>
                    <span class="badge badge-{{ $service->is_active ? 'success' : 'danger' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Edit</a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No services found. Add your first service!</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
