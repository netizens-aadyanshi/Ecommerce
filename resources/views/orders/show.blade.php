<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Order Detail #{{ $order->id }}
            </h2>
            <span class="text-sm text-gray-500">Status: <b class="uppercase text-indigo-500">{{ $order->status }}</b></span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden border dark:border-gray-700">

                {{-- Info Header --}}
                <div class="p-8 border-b dark:border-gray-700 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Shipping Address</h4>
                        <p class="text-gray-900 dark:text-white leading-relaxed">{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Payment Summary</h4>
                        <p class="text-sm text-gray-500">Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">Total: ${{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Notes</h4>
                        <p class="text-gray-600 dark:text-gray-400 italic text-sm">{{ $order->note ?? 'No special notes.' }}</p>
                    </div>
                </div>

                {{-- Item Table --}}
                <div class="p-8">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-gray-400 text-xs uppercase">
                                <th class="py-3">Product</th>
                                <th class="py-3 text-center">Qty</th>
                                <th class="py-3 text-right">Price</th>
                                <th class="py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="py-4 flex items-center gap-4">
                                        <img src="{{ $item->product->primaryImage ? asset('storage/' . $item->product->primaryImage->image_url) : asset('images/placeholder.png') }}" class="w-12 h-12 object-cover rounded shadow-sm border dark:border-gray-600">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</span>
                                    </td>
                                    <td class="py-4 text-center dark:text-white">{{ $item->quantity }}</td>
                                    <td class="py-4 text-right dark:text-white">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-4 text-right font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-8 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                    <a href="{{ route('orders.index') }}" class="text-sm text-indigo-500 hover:underline">← Back to My Orders</a>

                    @if($order->status === 'pending')
                        <form action="{{ route('orders.cancel', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <x-danger-button>Cancel Order</x-danger-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
