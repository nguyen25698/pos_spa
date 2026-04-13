@extends('layouts.app')

@section('title', 'Services - Nail Spa POS')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>💅 Services Management</h2>
        <a href="{{ route('services.create') }}" class="btn">Add New Service</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td><span class="badge badge-info">{{ $service->category }}</span></td>
                    <td>{{ $service->duration_minutes }} min</td>
                    <td>${{ number_format($service->price, 2) }}</td>
                    <td>
                        @if($service->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('services.edit', $service) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">Edit</a>
                        <form action="{{ route('services.destroy', $service) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem;">Delete</button>
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
