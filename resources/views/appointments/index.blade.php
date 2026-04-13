@extends('layouts.calendar')

@section('title', 'Calendar - Nail Spa POS')

@section('content')
<div class="calendar-header">
    <button class="today-btn" id="todayBtn">Today</button>
    <div class="date-nav">
        <button class="date-nav-btn" id="prevDay">‹</button>
        <span class="current-date" id="currentDate">September 7, 2022</span>
        <button class="date-nav-btn" id="nextDay">›</button>
        <button class="settings-btn">⚙</button>
    </div>
    <div class="view-toggle">
        <button class="view-btn active" data-view="daily">Daily</button>
        <button class="view-btn" data-view="weekly">Weekly</button>
        <button class="view-btn" data-view="monthly">Monthly</button>
    </div>
</div>

<div style="background: #fff; overflow-x: auto;">
    <div style="display: grid; grid-template-columns: 60px repeat({{ $staff->count() + 1 }}, minmax(150px, 1fr)); border-bottom: 2px solid #e0e0e0;">
        <!-- Time column header -->
        <div style="border-right: 1px solid #e0e0e0;"></div>
        
        <!-- Unassigned column header -->
        <div style="padding: 0.75rem; text-align: center; font-weight: 500; border-right: 1px solid #e0e0e0; background: #f9f9f9;">
            Unassign
        </div>
        
        <!-- Staff column headers -->
        @foreach($staff as $member)
        <div style="padding: 0.75rem; text-align: center; font-weight: 500; border-right: 1px solid #e0e0e0;">
            <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <span style="width: 24px; height: 24px; background: #e3f2fd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">👤</span>
                {{ strtoupper($member->first_name) }}
            </span>
        </div>
        @endforeach
    </div>

    <!-- Time slots -->
    <div style="position: relative; min-height: 600px;">
        @php
            $hours = array_merge(
                array_map(fn($h) => sprintf('%dAM', $h), range(9, 11)),
                array_map(fn($h) => sprintf('%dPM', $h == 12 ? 12 : $h - 12), range(12, 14))
            );
            $hourPositions = [9 => 0, 10 => 1, 11 => 2, 12 => 3, 13 => 4, 14 => 5];
        @endphp

        @foreach($hours as $index => $hour)
        <div style="display: grid; grid-template-columns: 60px repeat({{ $staff->count() + 1 }}, minmax(150px, 1fr)); height: 100px; border-bottom: 1px solid #e0e0e0;">
            <!-- Time label -->
            <div style="padding: 0.5rem; font-size: 0.85rem; color: #666; border-right: 1px solid #e0e0e0; background: #f9f9f9;">
                {{ $hour }}
            </div>
            
            <!-- Unassigned column -->
            <div style="border-right: 1px solid #e0e0e0; position: relative;">
                @foreach($appointments->where('staff_id', null)->whereBetween('appointment_time', [
                    sprintf('%02d:00:00', $index + 9 < 12 ? $index + 9 : $index + 9 - 12 + 12),
                    sprintf('%02d:59:59', $index + 9 < 12 ? $index + 9 : $index + 9 - 12 + 12)
                ]) as $appointment)
                <div style="position: absolute; left: 4px; right: 4px; background: #fff9c4; border-left: 4px solid #ffeb3b; padding: 0.5rem; border-radius: 4px; font-size: 0.85rem; overflow: hidden; z-index: 1;">
                    <div style="font-weight: 500;">{{ $appointment->service->name }}</div>
                    <div style="color: #666; font-size: 0.8rem;">
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} - 
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes($appointment->service->duration_minutes ?? 60)->format('h:i A') }}
                    </div>
                    <div style="font-size: 0.75rem; color: #999;">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</div>
                </div>
                @endforeach
            </div>
            
            <!-- Staff columns -->
            @foreach($staff as $member)
            <div style="border-right: 1px solid #e0e0e0; position: relative;">
                @php
                    $staffAppointments = $appointments->where('staff_id', $member->id)->filter(function($apt) use ($index) {
                        $aptHour = \Carbon\Carbon::parse($apt->appointment_time)->hour;
                        return $aptHour == ($index + 9);
                    });
                @endphp
                
                @foreach($staffAppointments as $appointment)
                @php
                    $colors = [
                        'scheduled' => ['#e8f5e9', '#4caf50'],
                        'confirmed' => ['#e3f2fd', '#2196f3'],
                        'completed' => ['#fce4ec', '#e91e63'],
                        'cancelled' => ['#ffebee', '#f44336']
                    ];
                    $colorPair = $colors[$appointment->status] ?? $colors['scheduled'];
                @endphp
                <div style="position: absolute; left: 4px; right: 4px; background: {{ $colorPair[0] }}; border-left: 4px solid {{ $colorPair[1] }}; padding: 0.5rem; border-radius: 4px; font-size: 0.85rem; overflow: hidden; z-index: 1;">
                    <div style="font-weight: 500;">{{ $appointment->service->name }}</div>
                    <div style="color: #666; font-size: 0.8rem;">
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} - 
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes($appointment->service->duration_minutes ?? 60)->format('h:i A') }}
                    </div>
                    <div style="font-size: 0.75rem; color: #999;">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

<script>
let currentDate = new Date();

function updateDateDisplay() {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').textContent = currentDate.toLocaleDateString('en-US', options);
}

document.getElementById('todayBtn').addEventListener('click', function() {
    currentDate = new Date();
    updateDateDisplay();
    // Reload page with today's date
    window.location.href = '{{ route("appointments.index") }}';
});

document.getElementById('prevDay').addEventListener('click', function() {
    currentDate.setDate(currentDate.getDate() - 1);
    updateDateDisplay();
});

document.getElementById('nextDay').addEventListener('click', function() {
    currentDate.setDate(currentDate.getDate() + 1);
    updateDateDisplay();
});

document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        // In a real app, this would switch the view
    });
});

updateDateDisplay();
</script>
@endsection
