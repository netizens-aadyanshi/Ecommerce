<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Error/Success Alerts --}}
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-600 text-white rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8">
                {{-- Initialize Alpine for both Image Gallery and Price Calculation --}}
                <div x-data="{
                    mainImage: '{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image_url) : asset('images/placeholder.png') }}',
                    quantity: 1,
                    price: {{ $product->price }}
                }" class="grid grid-cols-1 md:grid-cols-2 gap-10">

                    {{-- Left: Image Gallery --}}
                    <div>
                        <div class="mb-4">
                            <img :src="mainImage" class="w-full h-96 object-cover rounded-lg border dark:border-gray-700 shadow-lg">
                        </div>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_url) }}"
                                     @click="mainImage = '{{ asset('storage/' . $image->image_url) }}'"
                                     class="h-20 w-full object-cover rounded cursor-pointer border-2 hover:border-indigo-500 transition {{ $image->is_primary ? 'border-indigo-500' : 'border-transparent' }}">
                            @endforeach
                        </div>
                    </div>

                    {{-- Right: Product Details & Order Form --}}
                    <div>
                        <span class="text-indigo-500 font-semibold uppercase text-sm tracking-wider">{{ $product->category->name }}</span>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $product->name }}</h1>

                        <p class="text-gray-600 dark:text-gray-400 mt-4 leading-relaxed">{{ $product->description }}</p>

                        <div class="mt-6">
                            <p class="text-3xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</p>
                            @if($product->stock > 0)
                                <p class="text-green-600 text-sm mt-1 font-medium">● {{ $product->stock }} units available in stock</p>
                            @else
                                <p class="text-red-500 text-sm mt-1 font-bold italic">● Currently Out of Stock</p>
                            @endif
                        </div>

                        @if($product->stock > 0)
                            <form action="{{ route('orders.store') }}" method="POST" class="mt-8 space-y-6 border-t dark:border-gray-700 pt-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                {{-- Quantity Input --}}
                                <div class="w-32">
                                    <x-input-label for="quantity" value="Select Quantity" />
                                    <x-text-input id="quantity"
                                                 name="quantity"
                                                 type="number"
                                                 min="1"
                                                 max="{{ $product->stock }}"
                                                 x-model="quantity"
                                                 class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                                </div>

                                {{-- Shipping Address (Required for your migration) --}}
                                <div>
                                    <x-input-label for="shipping_address" value="Shipping Address" />
                                    <textarea id="shipping_address"
                                              name="shipping_address"
                                              rows="3"
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                              placeholder="Street name, City, Postcode"
                                              required>{{ old('shipping_address') }}</textarea>
                                    <x-input-error :messages="$errors->get('shipping_address')" class="mt-2" />
                                </div>

                                {{-- Order Summary & Submit --}}
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Total Price:</span>
                                        <span class="text-2xl font-black text-gray-900 dark:text-white">
                                            $<span x-text="(quantity * price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                        </span>
                                    </div>
                                    <x-primary-button class="w-full justify-center h-12 mt-4 text-lg">
                                        Confirm & Place Order
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
