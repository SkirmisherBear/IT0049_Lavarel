<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $sales = Sale::query();

        if ($user->role === 'staff') {
            $sales->where('sold_by', $user->id);
            $todaySales = (clone $sales)->whereDate('created_at', today())->sum('total_price');
            $todayTransactions = (clone $sales)->whereDate('created_at', today())->count();
            $recentSales = (clone $sales)->with(['product', 'customer'])->latest()->limit(5)->get();

            return view('pos.staff-dashboard', compact('todaySales', 'todayTransactions', 'recentSales'));
        }

        $todaySales = (clone $sales)->whereDate('created_at', today())->sum('total_price');
        $monthSales = (clone $sales)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $totalSales = (clone $sales)->sum('total_price');
        $todayTransactions = (clone $sales)->whereDate('created_at', today())->count();
        $totalTransactions = (clone $sales)->count();
        $totalProducts = Product::count();
        $lowStock = Product::where('stock_quantity', '<=', 5)->count();
        $totalCustomers = Customer::count();
        $totalStaff = User::where('role', 'staff')->count();
        $recentSales = (clone $sales)->with(['product', 'customer', 'staff'])->latest()->limit(5)->get();

        return view('pos.admin-dashboard', compact(
            'todaySales',
            'monthSales',
            'totalSales',
            'todayTransactions',
            'totalTransactions',
            'totalProducts',
            'lowStock',
            'totalCustomers',
            'totalStaff',
            'recentSales'
        ));
    }
}
