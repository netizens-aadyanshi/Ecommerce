<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Order Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500 uppercase">Total Spent</p>
                    <p class="text-3xl font-bold">${{ number_format($totalSpent, 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500 uppercase">Pending Orders</p>
                    <p class="text-3xl font-bold">{{ $pendingOrdersCount }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Recent Orders</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="pb-2">Order ID</th>
                            <th class="pb-2">Status</th>
                            <th class="pb-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b">
                            <td class="py-2">#{{ $order->id }}</td>
                            <td class="py-2">{{ strtoupper($order->status) }}</td>
                            <td class="py-2">${{ number_format($order->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">View All Orders</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
