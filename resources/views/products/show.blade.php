<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                    <div x-data="{ mainImage: '{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image_url) : asset('images/placeholder.png') }}' }">
                        <div class="mb-4">
                            <img :src="mainImage" class="w-full h-96 object-cover rounded-lg border dark:border-gray-700">
                        </div>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_url) }}"
                                     @click="mainImage = '{{ asset('storage/' . $image->image_url) }}'"
                                     class="h-20 w-full object-cover rounded cursor-pointer border-2 hover:border-indigo-500 transition {{ $image->is_primary ? 'border-indigo-500' : 'border-transparent' }}">
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <span class="text-indigo-500 font-semibold uppercase text-sm">{{ $product->category->name }}</span>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $product->name }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-4">{{ $product->description }}</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-6">${{ number_format($product->price, 2) }}</p>

                        <div class="mt-4">
                            @if($product->stock > 0)
                                <span class="text-green-600 font-medium">In Stock ({{ $product->stock }} available)</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full uppercase">Out of Stock</span>
                            @endif
                        </div>

                        @if($product->stock > 0)
                            <form action="{{ route('orders.store') }}" method="POST" class="mt-8">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="flex items-center gap-4">
                                    <div class="w-24">
                                        <x-input-label for="quantity" value="Quantity" />
                                        <x-text-input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1" class="w-full" />
                                    </div>
                                    <x-primary-button class="h-11 mt-6">
                                        Place Order
                                    </x-primary-button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
