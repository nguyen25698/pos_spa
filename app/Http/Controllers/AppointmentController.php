<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['customer', 'staff', 'service'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(20);
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $customers = Customer::orderBy('last_name')->get();
        $staff = Staff::where('is_active', true)->orderBy('last_name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('appointments.create', compact('customers', 'staff', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'staff_id' => 'required|exists:staff,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'scheduled';

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function edit(Appointment $appointment)
    {
        $customers = Customer::orderBy('last_name')->get();
        $staff = Staff::where('is_active', true)->orderBy('last_name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'customers', 'staff', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'staff_id' => 'required|exists:staff,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled successfully.');
    }
}
