<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Products</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalProducts }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Orders</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalOrders }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Revenue</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($totalRevenue, 2) }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Pending Orders</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $pendingOrders }}</div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
