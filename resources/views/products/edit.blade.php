<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Part 1: Product Details --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
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

                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Update Product Details') }}</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Part 2: Image Gallery Management --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Product Images') }}</h3>

                {{-- Upload New Images --}}
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mb-8 p-4 border border-dashed border-gray-600 rounded">
                    @csrf @method('PUT')
                    <x-input-label :value="__('Add More Images')" />
                    <input type="file" name="images[]" multiple class="mt-2 block w-full text-sm text-gray-400">
                    <x-primary-button class="mt-2">{{ __('Upload') }}</x-primary-button>
                </form>

                {{-- Existing Image Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($product->images as $img)
                        <div class="relative group rounded-lg border {{ $img->is_primary ? 'border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-700' }} p-1 overflow-hidden">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="h-24 w-full object-cover rounded">

                            <div class="mt-2 flex flex-col gap-1">
                                @if(!$img->is_primary)
                                    <a href="{{ route('product-images.setPrimary', $img) }}" class="text-[10px] text-center bg-indigo-600 text-white rounded py-1">Make Primary</a>
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
