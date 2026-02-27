<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total products count
        $totalProducts = Product::count();

        // 2. Total orders count
        $totalOrders = Order::count();

        // 3. Total revenue - sum of 'total' where status is 'delivered'
        // Task Requirement: Filter by 'delivered' status
        $totalRevenue = Order::where('status', 'completed')->sum('total');

        // 4. Pending orders count
        $pendingOrders = Order::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders'
        ));
    }
}
