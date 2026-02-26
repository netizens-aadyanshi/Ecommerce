<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <a href="{{ route('products.show', $product) }}">
                            <img src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image_url) : asset('images/placeholder.png') }}"
                                 class="w-full h-48 object-cover">
                        </a>

                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                            <p class="text-xl font-bold text-indigo-600 mt-2">${{ number_format($product->price, 2) }}</p>

                            <a href="{{ route('products.show', $product) }}" class="mt-4 block text-center bg-gray-200 dark:bg-gray-700 py-2 rounded-md text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>
