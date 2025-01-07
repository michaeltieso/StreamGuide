<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Search Bar -->
        <div class="mb-8">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Search FAQs..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="mb-8 flex flex-wrap gap-2">
            <button 
                wire:click="$set('selectedCategory', null)"
                class="px-4 py-2 text-sm rounded-full {{ !$selectedCategory ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}"
            >
                All Categories
            </button>
            @foreach($faqCategories as $category)
                <button 
                    wire:click="$set('selectedCategory', {{ $category->id }})"
                    class="px-4 py-2 text-sm rounded-full {{ $selectedCategory == $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- FAQ List -->
        <div class="space-y-8">
            @forelse($faqCategories as $category)
                @if(count($category->faqs) > 0)
                    <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-100 mb-4">{{ $category->name }}</h2>
                            <div class="space-y-4">
                                @foreach($category->faqs as $faq)
                                    <div class="border-b border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                        <h3 class="text-lg font-medium text-gray-200 mb-2">{{ $faq->question }}</h3>
                                        <p class="text-gray-400">{{ $faq->answer }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-400">
                            @if($search)
                                No FAQs found matching your search.
                            @else
                                No FAQs available at this time.
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
