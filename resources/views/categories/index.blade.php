<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Add Category') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 px-4 font-bold uppercase text-xs text-gray-600 dark:text-gray-400">Name</th>
                                    <th class="py-3 px-4 font-bold uppercase text-xs text-gray-600 dark:text-gray-400">Description</th>
                                    <th class="py-3 px-4 font-bold uppercase text-xs text-gray-600 dark:text-gray-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($categories as $category)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="py-4 px-4 text-sm font-medium">{{ $category->name }}</td>
                                        <td class="py-4 px-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($category->description, 60) }}
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                                    {{ __('Edit') }}
                                                </a>

                                                <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium"
                                                            onclick="return confirm('{{ __('Are you sure you want to delete this category?') }}')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
