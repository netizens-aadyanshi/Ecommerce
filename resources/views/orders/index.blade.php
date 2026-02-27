<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="p-4 bg-green-600 text-white rounded-lg shadow-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($orders as $order)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border dark:border-gray-700">
                    <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Order #{{ $order->id }}</span>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500',
                                        'shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-500',
                                        'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $statusClasses[$order->status] ?? 'bg-gray-100' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Placed on {{ $order->created_at->format('M d, Y') }} • {{ $order->orderItems->count() }} items
                            </p>
                        </div>

                        <div class="text-center md:text-right px-4">
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Total</p>
                            <p class="text-xl font-black text-indigo-600 dark:text-indigo-400">${{ number_format($order->total, 2) }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('orders.show', $order) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 transition">
                                View Details
                            </a>

                            @if($order->status === 'pending')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Cancel this order?')">
                                    @csrf @method('PATCH')
                                    <button class="inline-flex items-center px-4 py-2 bg-red-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 transition">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <p class="text-gray-500">You haven't placed any orders yet.</p>
                </div>
            @endforelse

            <div class="mt-6">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
