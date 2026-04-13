<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Appointment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard()
    {
        // Today's stats
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        
        $todaySales = Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_amount');
        $todayTransactions = Transaction::whereBetween('created_at', [$todayStart, $todayEnd])->count();
        
        // Monthly stats
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        
        $monthlySales = Transaction::whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total_amount');
        $monthlyTransactions = Transaction::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        
        // Top services
        $topServices = DB::table('transaction_items')
            ->join('services', 'transaction_items.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('SUM(transaction_items.quantity) as total_sold'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        // Top customers
        $topCustomers = Customer::orderByDesc('total_spent')->limit(5)->get();
        
        // Upcoming appointments
        $upcomingAppointments = Appointment::with(['customer', 'staff', 'service'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();
        
        return view('reports.dashboard', compact(
            'todaySales', 'todayTransactions',
            'monthlySales', 'monthlyTransactions',
            'topServices', 'topCustomers', 'upcomingAppointments'
        ));
    }

    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        
        $transactions = Transaction::with(['customer', 'staff', 'items.service'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalSales = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
        
        return view('reports.sales', compact('transactions', 'totalSales', 'totalTransactions', 'startDate', 'endDate'));
    }

    public function commissions(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        
        $staffCommissions = DB::table('staff')
            ->leftJoin('transactions', 'staff.id', '=', 'transactions.staff_id')
            ->leftJoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select(
                'staff.id',
                'staff.first_name',
                'staff.last_name',
                'staff.commission_rate',
                DB::raw('COALESCE(SUM(transaction_items.total_price), 0) as total_sales'),
                DB::raw('COALESCE(SUM(transaction_items.total_price), 0) * (staff.commission_rate / 100) as commission')
            )
            ->groupBy('staff.id', 'staff.first_name', 'staff.last_name', 'staff.commission_rate')
            ->orderByDesc('commission')
            ->get();
        
        return view('reports.commissions', compact('staffCommissions', 'startDate', 'endDate'));
    }
}
