<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['customer', 'staff', 'service'])
            ->orderBy('appointment_date', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->paginate(20);
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $customers = Customer::orderBy('last_name')->get();
        $staff = Staff::active()->orderBy('last_name')->get();
        $services = Service::active()->orderBy('name')->get();
        return view('appointments.create', compact('customers', 'staff', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'staff_id' => 'required|exists:staff,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $service = Service::find($request->service_id);
        $validated['price_at_booking'] = $service->price;
        $validated['status'] = 'scheduled';

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'staff', 'service']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $customers = Customer::orderBy('last_name')->get();
        $staff = Staff::active()->orderBy('last_name')->get();
        $services = Service::active()->orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'customers', 'staff', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'staff_id' => 'required|exists:staff,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no-show',
            'notes' => 'nullable|string',
        ]);

        $service = Service::find($request->service_id);
        $validated['price_at_booking'] = $service->price;

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    public function confirm(Appointment $appointment)
    {
        $appointment->update(['status' => 'confirmed']);
        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment confirmed.');
    }

    public function complete(Appointment $appointment)
    {
        $appointment->update(['status' => 'completed']);
        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment marked as completed.');
    }
}
