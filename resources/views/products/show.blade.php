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

                        {{-- START: Product Tags Badges --}}
                        @if($product->tags->count() > 0)
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach($product->tags as $tag)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-indigo-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        {{-- END: Product Tags Badges --}}
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

                    {{-- Customer Reviews Section --}}
                    <div class="mt-16 border-t dark:border-gray-700 pt-10">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">{{ __('Customer Reviews') }}</h3>

                        {{-- 1. Add Review Form (Only for Logged-in Users) --}}
                        @auth
                            <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-xl mb-12 shadow-sm border dark:border-gray-800">
                                <h4 class="font-semibold dark:text-gray-300 text-lg mb-4">{{ __('Write a Review') }}</h4>
                                <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating</label>
                                        {{-- Added text-gray-900 and dark:text-white --}}
                                        <select name="rating" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-700 text-gray-900 dark:text-white w-full md:w-40 focus:ring-indigo-500">
                                            <option value="5">5 Stars (Excellent)</option>
                                            <option value="4">4 Stars (Good)</option>
                                            <option value="3">3 Stars (Average)</option>
                                            <option value="2">2 Stars (Poor)</option>
                                            <option value="1">1 Star (Very Bad)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comment</label>
                                        <textarea name="comment" rows="3" class="w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500" placeholder="What did you like or dislike about this product?" required></textarea>
                                    </div>

                                    <x-primary-button>Submit My Review</x-primary-button>
                                </form>
                            </div>
                        @else
                            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg text-sm text-indigo-700 dark:text-indigo-300 mb-12">
                                {{ __('Please') }} <a href="{{ route('login') }}" class="font-bold underline">{{ __('login') }}</a> {{ __('to leave a review.') }}
                            </div>
                        @endauth

                        {{-- 2. Display Existing Reviews --}}
                        <div class="space-y-8">
                            @forelse($product->reviews as $review)
                                <div class="flex flex-col border-b dark:border-gray-800 pb-8">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white">{{ $review->user->name }}</span>
                                            <div class="flex text-yellow-400 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 italic">"{{ $review->comment }}"</p>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-gray-500 italic">{{ __('No reviews yet. Be the first to review this product!') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
