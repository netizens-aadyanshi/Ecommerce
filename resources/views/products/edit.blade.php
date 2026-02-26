<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if($errors->any())
                <div class="p-4 bg-red-500 text-white rounded-lg">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Part 1: Product & Stock Details --}}
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3">
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                        </div>

                        {{-- Added Stock Field --}}
                        <div>
                            <x-input-label for="stock" :value="__('Stock Quantity')" />
                            <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', $product->stock)" required />
                        </div>

                        <div class="md:col-span-3">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Part 2: Image Management --}}
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Product Images') }}</h3>

                    <div class="mb-8 p-4 border border-dashed border-gray-600 rounded">
                        <x-input-label :value="__('Upload New Images')" />
                        <input type="file" name="images[]" multiple class="mt-2 block w-full text-sm text-gray-400">
                        <p class="text-xs text-gray-500 mt-1">You can select multiple files at once.</p>
                    </div>

                    <div class="flex items-center gap-4 mb-8">
                        <x-primary-button>{{ __('Save All Changes') }}</x-primary-button>
                    </div>
            </form> {{-- Main Form Ends Here --}}

                <hr class="border-gray-700 my-6">

                {{-- Existing Image Grid (Kept separate for Delete/Set Primary actions) --}}
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($product->images as $img)
                        <div class="relative group rounded-lg border {{ $img->is_primary ? 'border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-700' }} p-1 overflow-hidden bg-gray-900">
                            <img src="{{ asset('storage/' . $img->image_url) }}" class="h-24 w-full object-cover rounded">

                            <div class="mt-2 flex flex-col gap-1">
                                @if(!$img->is_primary)
                                    <form action="{{ route('product-images.setPrimary', $img) }}" method="POST">
                                        @csrf @method('PATCH') {{-- Using PATCH for state changes --}}
                                        <button class="w-full text-[10px] text-center bg-indigo-600 text-white rounded py-1">Make Primary</button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-center bg-green-600 text-white rounded py-1 uppercase font-bold">Primary</span>
                                @endif

                                <form action="{{ route('product-images.destroy', $img) }}" method="POST" onsubmit="return confirm('Remove image?')">
                                    @csrf @method('DELETE')
                                    <button class="w-full text-[10px] bg-red-600 text-white rounded py-1">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
