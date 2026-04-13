@extends('layouts.app')

@section('title', 'Appointments - Nail Spa POS')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>📅 Appointments</h2>
        <a href="{{ route('appointments.create') }}" class="btn">Book Appointment</a>
    </div>

    <form method="GET" style="margin-bottom: 1rem; display: flex; gap: 1rem;">
        <select name="status" class="form-control" style="width: auto;">
            <option value="">All Statuses</option>
            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="form-control" style="width: auto;">
        <button type="submit" class="btn btn-secondary">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Technician</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
                    <td>{{ $appointment->customer->full_name }}</td>
                    <td>{{ $appointment->service->name }}</td>
                    <td>{{ $appointment->staff->full_name }}</td>
                    <td>${{ number_format($appointment->price_at_booking, 2) }}</td>
                    <td>
                        @if($appointment->status == 'scheduled')
                            <span class="badge badge-info">Scheduled</span>
                        @elseif($appointment->status == 'confirmed')
                            <span class="badge badge-success">Confirmed</span>
                        @elseif($appointment->status == 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif($appointment->status == 'cancelled')
                            <span class="badge badge-danger">Cancelled</span>
                        @else
                            <span class="badge badge-warning">{{ ucfirst($appointment->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem;">View</a>
                        @if($appointment->status == 'scheduled')
                            <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" style="padding: 0.25rem 0.5rem;">Confirm</button>
                            </form>
                        @endif
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
