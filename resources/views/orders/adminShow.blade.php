<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manage Order #{{ $order->id }}
            </h2>
            <a href="{{ route('orders.adminIndex') }}" class="text-sm text-indigo-600">Back to List</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Status Change Card --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold">Current Status</p>
                    <span class="text-lg font-black text-indigo-600 uppercase">{{ $order->status }}</span>
                </div>

                @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex gap-2">
                        @csrf @method('PATCH')
                        <select name="status" class="rounded border-gray-300 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <x-primary-button>Update Status</x-primary-button>
                    </form>
                @endif
            </div>

            {{-- Order Details Table (Same as show blade) --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="font-bold dark:text-white">Customer: {{ $order->user->name }}</h3>
                    <p class="text-sm text-gray-500">Address: {{ $order->shipping_address }}</p>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr class="text-xs uppercase text-gray-500">
                            <th class="p-4">Product</th>
                            <th class="p-4 text-center">Qty</th>
                            <th class="p-4 text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="p-4 dark:text-white">{{ $item->product->name }}</td>
                                <td class="p-4 text-center dark:text-white">{{ $item->quantity }}</td>
                                <td class="p-4 text-right dark:text-white">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
