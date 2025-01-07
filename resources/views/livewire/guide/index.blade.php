<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <!-- Guide Categories -->
                @foreach($categories as $category)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-white mb-4">{{ $category->name }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($category->guides as $guide)
                                <a href="{{ route('guide.show', $guide->slug) }}" 
                                   class="group block p-6 bg-gray-900 rounded-lg border border-gray-800 hover:border-indigo-500/50 transition-colors duration-200"
                                >
                                    <h3 class="text-lg font-medium text-white mb-2">{{ $guide->title }}</h3>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-400">View Guide</span>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-400 transform group-hover:translate-x-1 transition-all duration-200" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </main>
        </div>
    </div>
</div> 