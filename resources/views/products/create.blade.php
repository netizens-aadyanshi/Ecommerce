<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Product Name --}}
                            <div class="md:col-span-2">
                                <x-input-label for="name" :value="__('Product Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            {{-- Category Selection --}}
                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('Select a Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>

                            {{-- Price --}}
                            <div>
                                <x-input-label for="price" :value="__('Price ($)')" />
                                <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            {{-- Stock --}}
                            <div>
                                <x-input-label for="stock" :value="__('Stock Quantity')" />
                                <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                            </div>

                            {{-- Status Toggle --}}
                            <div class="flex items-center">
                                <label for="is_active" class="inline-flex items-center">
                                    <input id="is_active" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Mark as Active') }}</span>
                                </label>
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Product Description')" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            {{-- Multiple Image Upload --}}
                            <div class="md:col-span-2">
                                <x-input-label for="images" :value="__('Product Images (You can select multiple)')" />
                                <input id="images" name="images[]" type="file" multiple class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    dark:file:bg-gray-700 dark:file:text-gray-300" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic">
                                    The first image uploaded will be set as the primary (cover) image.
                                </p>
                                <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 border-t dark:border-gray-700 pt-6">
                            <a href="{{ route('products.adminIndex') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline px-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Create Product') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
