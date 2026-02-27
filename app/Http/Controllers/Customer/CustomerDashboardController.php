<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $orders = $user->orders()->latest()->take(5)->get();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total');
        $pendingOrdersCount = $user->orders()->where('status', 'pending')->count();

        return view('customer.dashboard', compact('orders', 'totalSpent', 'pendingOrdersCount'));
    }
}
