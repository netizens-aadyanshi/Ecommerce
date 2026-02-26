<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product Management') }}
            </h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                {{ __('Add New Product') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="py-3 px-4 uppercase text-xs font-bold text-gray-500">Image</th>
                                <th class="py-3 px-4 uppercase text-xs font-bold text-gray-500">Name</th>
                                <th class="py-3 px-4 uppercase text-xs font-bold text-gray-500">Category</th>
                                <th class="py-3 px-4 uppercase text-xs font-bold text-gray-500">Price</th>
                                <th class="py-3 px-4 uppercase text-xs font-bold text-gray-500">Stock</th>
                                <th class="py-3 px-4 uppercase text-xs font-bold text-gray-500 text-center">Status</th>
                                <th class="py-3 px-4 uppercase text-xs font-bold text-gray-500 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($products as $product)
                            <tr>
                                <td class="py-4 px-4">
                                    @php $primary = $product->images->where('is_primary', true)->first(); @endphp
                                    <img src="{{ $primary ? asset('storage/' . $primary->image_url) : asset('images/placeholder.png') }}"
                                         class="w-12 h-12 object-cover rounded border dark:border-gray-600">
                                </td>
                                <td class="py-4 px-4 font-medium">{{ $product->name }}</td>
                                <td class="py-4 px-4 text-sm">{{ $product->category->name }}</td>
                                <td class="py-4 px-4 text-sm font-bold">${{ number_format($product->price, 2) }}</td>
                                <td class="py-4 px-4 text-sm">
                                    <span class="{{ $product->stock < 10 ? 'text-red-500 font-bold' : '' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    {{-- Toggle Active Form --}}
                                    <form action="{{ route('products.toggle', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('products.edit', $product) }}" class="text-indigo-400 hover:text-indigo-300">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-400 hover:text-red-300">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
