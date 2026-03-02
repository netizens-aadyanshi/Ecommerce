<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category Review Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Category Selection Dropdown Section --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ __('Currently Viewing:') }} <span class="text-indigo-500">{{ $category->name }}</span>
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('Switch the category below to update statistics.') }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <label for="category_select" class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                        {{ __('Select Category:') }}
                    </label>
                    {{-- The onchange script automatically redirects the browser when a new category is picked --}}
                    <select id="category_select"
                            onchange="window.location.href='/admin/categories/' + this.value"
                            class="rounded-md border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 w-full md:w-64 shadow-sm">
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}" {{ $cat->id == $category->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm border dark:border-gray-700 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Total Reviews') }}</p>
                    <p class="text-5xl font-black mt-2 text-gray-900 dark:text-white">
                        {{ $totalReviews }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm border dark:border-gray-700 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Average Rating') }}</p>
                    <div class="flex justify-center items-center mt-2">
                        <p class="text-5xl font-black text-yellow-500">
                            {{ number_format($avgRating, 1) }}
                        </p>
                        <span class="ml-2 text-2xl text-gray-400">/ 5.0</span>
                    </div>
                </div>
            </div>

            {{-- Reviews Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden border dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ __('Recent Customer Comments for') }} {{ $category->name }}
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Product') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Customer') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Rating') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Comment') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentReviews as $review)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $review->product->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $review->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex text-yellow-500">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 italic">
                                        "{{ $review->comment }}"
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 italic">
                                        {{ __('No reviews found for this category yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($recentReviews->hasPages())
                    <div class="p-6 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700">
                        {{ $recentReviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
