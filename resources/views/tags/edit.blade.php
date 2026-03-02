<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Tag') }}: {{ $tag->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('tags.update', $tag) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tag Name</label>
                        <input type="text" name="name" value="{{ old('name', $tag->name) }}" class="w-full border-gray-300 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Update Tag</button>
                    <a href="{{ route('tags.index') }}" class="ml-4 text-gray-600 dark:text-gray-400">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
