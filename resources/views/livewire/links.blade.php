<div class="p-4 bg-gray-800">
    <h2 class="text-xl font-semibold text-gray-100 mb-4">Quick Links</h2>
    <div class="space-y-4">
        @forelse ($links as $link)
            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" 
               class="block p-4 border border-gray-700 rounded-lg hover:bg-gray-700 transition duration-150 bg-gray-900">
                <div class="flex items-center">
                    @if ($link->icon)
                        <div class="flex-shrink-0 w-8 h-8 mr-3">
                            <i class="{{ $link->icon }} text-2xl text-indigo-400"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-medium text-gray-100">{{ $link->title }}</h3>
                        @if ($link->description)
                            <p class="mt-1 text-sm text-gray-400">{{ $link->description }}</p>
                        @endif
                    </div>
                    <div class="ml-auto">
                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-gray-400 text-center py-4">No links available</p>
        @endforelse
    </div>
</div>
