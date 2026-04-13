@extends('layouts.app')

@section('title', 'Staff - Nail Spa POS')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Staff Management</h2>
    <a href="{{ route('staff.create') }}" class="btn btn-primary">+ Add Staff</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Commission Rate</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $member)
            <tr>
                <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                <td>{{ $member->email ?? '-' }}</td>
                <td>{{ $member->phone ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $member->role)) }}</td>
                <td>{{ number_format($member->commission_rate, 2) }}%</td>
                <td>
                    <span class="badge badge-{{ $member->is_active ? 'success' : 'danger' }}">
                        {{ $member->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('staff.edit', $member) }}" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Edit</a>
                    <form action="{{ route('staff.destroy', $member) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No staff members found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 1rem;">
        {{ $staff->links() }}
    </div>
</div>
@endsection
