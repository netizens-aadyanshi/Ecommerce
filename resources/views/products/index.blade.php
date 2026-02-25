<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shop Products') }}
            </h2>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm">
                    Back to Admin Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <h1 class="text-3xl font-bold mb-4">Welcome to our Store, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mb-8">You have successfully authenticated and verified your email.</p>

                    <hr class="my-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                            <div class="bg-gray-200 h-40 w-full mb-4 rounded"></div>
                            <h3 class="font-bold">Sample Product</h3>
                            <p class="text-gray-500">$99.99</p>
                            <button class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">View Details</button>
                        </div>

                        <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                            <div class="bg-gray-200 h-40 w-full mb-4 rounded"></div>
                            <h3 class="font-bold">Another Item</h3>
                            <p class="text-gray-500">$49.99</p>
                            <button class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
