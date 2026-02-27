<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    // protected $statusOrder = ['pending', 'processing', 'completed', 'cancelled'];

    public function adminIndex()
    {
        $orders = Order::with('user')->latest()->paginate(15);
        return view('orders.adminIndex', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $newStatus = $request->status;
        $currentStatus = $order->status;

        if ($currentStatus === 'cancelled') {
            return back()->with('error', 'This order is cancelled.');
        }

        switch ($currentStatus) {
            case 'pending':
                if (!in_array($newStatus, ['processing', 'cancelled'])) {
                    return back()->with('error', "Invalid status transition from $currentStatus to $newStatus.");
                }
                break;
            case 'processing':
                if (!in_array($newStatus, ['completed', 'cancelled'])) {
                    return back()->with('error', "Invalid status transition from $currentStatus to $newStatus.");
                }
                break;
            case 'completed':
                return back()->with('error', "Completed orders cannot be changed.");
            default:
                return back()->with('error', 'Invalid current order status.');
        }

        if ($currentStatus !== $newStatus) {
            $order->update(['status' => $newStatus]);

            // Task Requirement: Dispatch Mail via Queue
            Mail::to($order->user)->queue(new OrderStatusUpdatedMail($order));

            return back()->with('success', "Order moved to $newStatus.");
        }

        return back();
    }

    public function index()
    {
        // Query scoped to the authenticated user
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'You do not have permission to view this order.');
        }

        $order->load(['user', 'orderItems.product']);

        // Check if the request is coming from the admin prefix route
        if (request()->is('admin/*')) {
            return view('orders.adminShow', compact('order'));
        }

        return view('orders.show', compact('order'));
    }

    public function store(OrderRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        DB::beginTransaction();

        try {
            $total = $product->price * $request->quantity;

            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'subtotal' => $total,
                'total' => $total,
                'shipping_address' => $request->shipping_address,
                'note' => $request->note,
            ]);

            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
                'total_price' => $total,
            ]);

            $product->decrement('stock', $request->quantity);

            DB::commit();

            Mail::to(auth()->user())->queue(new OrderPlacedMail($order));

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }


    }

    public function cancel(Order $order)
    {

        $isAdmin = auth()->user()->role === 'admin';
        $isOwner = $order->user_id === auth()->id();

        if (!($isOwner || $isAdmin) || $order->status !== 'pending') {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        try {
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'cancelled']);

                foreach ($order->orderItems as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
            });

            Mail::to($order->user)->queue(new OrderCancelledMail($order));

            return back()->with('success', 'Order cancelled and stock restored.');

        } catch (\Exception $e) {
            return back()->with('error', 'Cancellation failed.');
        }
    }
}
