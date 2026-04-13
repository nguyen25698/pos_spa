@extends('layouts.app')

@section('title', 'Appointments - Nail Spa POS')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Appointments</h2>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">+ Book Appointment</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Customer</th>
                <th>Staff</th>
                <th>Service</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
            <tr>
                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                <td>{{ $appointment->appointment_time->format('g:i A') }}</td>
                <td>{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</td>
                <td>{{ $appointment->staff->first_name }} {{ $appointment->staff->last_name }}</td>
                <td>{{ $appointment->service->name }}</td>
                <td>
                    <span class="badge badge-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : ($appointment->status == 'confirmed' ? 'info' : 'warning')) }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Edit</a>
                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Cancel</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No appointments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 1rem;">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
