<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage All Customer Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Standard Status & Error Messages --}}
            @if(session('success'))
                <div class="p-4 bg-green-600 text-white rounded-lg mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="p-4 text-sm font-semibold dark:text-gray-200">Order ID</th>
                            <th class="p-4 text-sm font-semibold dark:text-gray-200">Customer</th>
                            <th class="p-4 text-sm font-semibold dark:text-gray-200">Date</th>
                            <th class="p-4 text-sm font-semibold dark:text-gray-200">Total</th>
                            <th class="p-4 text-sm font-semibold dark:text-gray-200">Status</th>
                            <th class="p-4 text-sm font-semibold dark:text-gray-200 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @forelse($orders as $order)
                            <tr>
                                <td class="p-4 dark:text-gray-300">#{{ $order->id }}</td>
                                <td class="p-4">
                                    <div class="text-sm font-bold dark:text-white">{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                </td>
                                <td class="p-4 text-sm dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="p-4 font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($order->total, 2) }}</td>
                                <td class="p-4">
                                    {{-- Reusing your badge logic --}}
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100' }}">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    {{-- FIX: Link to adminShow, not show --}}
                                    <a href="{{ route('orders.adminShow', $order) }}"
                                       class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center dark:text-gray-400">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
