<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Jobs\SendOrderPlacedEmail;
use App\Jobs\SendOrderStatusUpdatedEmail;
use App\Jobs\SendOrderCancelledEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderService
{
    public function getAllForAdmin()
    {
        return Order::with('user')->latest()->paginate(15);
    }

    public function updateStatus(Order $order, $newStatus)
    {
        DB::beginTransaction();

        try {
            $currentStatus = $order->status;

            if ($currentStatus === 'cancelled') {
                throw new \Exception('This order is cancelled.');
            }

            switch ($currentStatus) {
                case 'pending':
                    if (!in_array($newStatus, ['processing', 'cancelled'])) {
                        throw new \Exception("Invalid status transition from $currentStatus to $newStatus.");
                    }
                    break;
                case 'processing':
                    if (!in_array($newStatus, ['completed', 'cancelled'])) {
                        throw new \Exception("Invalid status transition from $currentStatus to $newStatus.");
                    }
                    break;
                case 'completed':
                    throw new \Exception("Completed orders cannot be changed.");
                default:
                    throw new \Exception('Invalid current order status.');
            }

            if ($currentStatus !== $newStatus) {
                $order->update(['status' => $newStatus]);

                if ($newStatus === 'cancelled') {
                    $this->restoreStock($order);
                    SendOrderCancelledEmail::dispatch($order);
                } else {
                    SendOrderStatusUpdatedEmail::dispatch($order);
                }


                DB::commit();
                return $order;
            }

            DB::rollBack();
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());

            throw new \Exception($e->getMessage());
        }
    }

    public function index()
    {
        return Order::with('user')->latest()->paginate(15);
    }

    public function show(Order $order)
    {
        try
        {

             if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
                 throw new Exception('You do not have permission to view this order.');
             }

             $order->load(['user', 'orderItems.product']);

             return $order;
        } catch (Exception $e) {
             Log::error('Error showing order: ' . $e->getMessage());
             throw new Exception('Failed to show order.');

        }
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $total = $product->price * $data['quantity'];


            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'subtotal' => $total,
                'total' => $total,
                'shipping_address' => $data['shipping_address'],
                'note' => $data['note'] ?? null,
            ]);


            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'unit_price' => $product->price,
                'total_price' => $total,
            ]);


            if ($product->stock < $data['quantity']) {
                throw new Exception("Not enough stock available.");
            }
            $product->decrement('stock', $data['quantity']);

            SendOrderPlacedEmail::dispatch($order);

            return $order;
        });
    }

    public function cancel(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if ($order->status !== 'pending') {
                throw new Exception('Only pending orders can be cancelled.');
            }

            $order->update(['status' => 'cancelled']);
            $this->restoreStock($order);

            SendOrderCancelledEmail::dispatch($order);

            return $order;
        });
    }

    protected function restoreStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }
    }

}
