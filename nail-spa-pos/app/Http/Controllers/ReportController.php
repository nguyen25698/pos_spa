<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard()
    {
        $todaySales = Transaction::today()->sum('total_amount');
        $todayTransactions = Transaction::today()->count();
        $todayAppointments = Appointment::today()->count();
        $lowStockItems = Inventory::lowStock()->count();

        $weeklySales = Transaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_amount');

        $monthlySales = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $topServices = DB::table('transaction_items')
            ->select('name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total_price) as revenue'))
            ->where('item_type', 'service')
            ->groupBy('name')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();

        return view('reports.dashboard', compact(
            'todaySales',
            'todayTransactions',
            'todayAppointments',
            'lowStockItems',
            'weeklySales',
            'monthlySales',
            'topServices'
        ));
    }

    public function sales(Request $request)
    {
        $query = Transaction::with(['customer', 'staff', 'items'])
            ->orderBy('created_at', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->paginate(20);
        $totalSales = $sales->sum('total_amount');

        return view('reports.sales', compact('sales', 'totalSales'));
    }

    public function staffPerformance(Request $request)
    {
        $staff = Staff::withCount(['appointments', 'transactions'])
            ->withSum('transactions', 'total_amount')
            ->orderBy('transactions_sum_total_amount', 'desc')
            ->get();

        foreach ($staff as $member) {
            $member->total_commission = 0;
            if ($member->transactions_sum_total_amount) {
                $member->total_commission = $member->calculateCommission($member->transactions_sum_total_amount);
            }
        }

        return view('reports.staff_performance', compact('staff'));
    }

    public function inventoryReport()
    {
        $inventory = Inventory::orderBy('category')->get();
        $lowStock = $inventory->filter->isLowStock();
        $totalValue = $inventory->sum(function ($item) {
            return $item->quantity * $item->cost_price;
        });

        return view('reports.inventory', compact('inventory', 'lowStock', 'totalValue'));
    }

    public function customerReport(Request $request)
    {
        $query = Customer::withCount(['appointments', 'transactions'])
            ->withSum('transactions', 'total_amount');

        if ($request->has('min_visits')) {
            $query->having('total_visits', '>=', $request->min_visits);
        }

        $customers = $query->orderBy('transactions_sum_total_amount', 'desc')
            ->paginate(20);

        return view('reports.customers', compact('customers'));
    }
}
