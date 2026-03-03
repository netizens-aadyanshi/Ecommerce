<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\OrderService;



class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function adminIndex()
    {
        $orders = $this->orderService->getAllForAdmin();
        return view('orders.adminIndex', compact('orders'));
    }

    public function updateStatus(OrderRequest $request, Order $order)
    {

        try {
            $this->orderService->updateStatus($order, $request->status);

            return back()->with('success', 'Order status updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $orders = $this->orderService->index();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        try {
            $order = $this->orderService->show($order);

            if (request()->is('admin/*')) {
                return view('orders.adminShow', compact('order'));
            }

            return view('orders.show', compact('order'));

        } catch (\Exception $e) {
            return back()->with('error', 'You are not authorized to view this order.');
        }

    }

    public function store(OrderRequest $request)
    {
        try {
            $this->orderService->store($request->validated());

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {

            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }


    }

    public function cancel(Order $order)
    {
        if (auth()->id() !== $order->user_id && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        try {
            $this->orderService->cancel($order);
            return back()->with('success', 'Order cancelled and stock restored.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
