@extends('layouts.app')

@section('title', 'Staff - Nail Spa POS')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>👩‍🔧 Staff Management</h2>
        <a href="{{ route('staff.create') }}" class="btn">Add Staff Member</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Commission Rate</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $member)
                <tr>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->email ?? '-' }}</td>
                    <td>{{ $member->phone ?? '-' }}</td>
                    <td>{{ $member->position }}</td>
                    <td>{{ number_format($member->commission_rate, 1) }}%</td>
                    <td>
                        @if($member->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('staff.show', $member) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">View</a>
                        <a href="{{ route('staff.edit', $member) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No staff members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
