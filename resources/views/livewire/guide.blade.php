<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="flex">
                <!-- Sidebar -->
                <div class="w-64 bg-gray-900 min-h-screen p-4">
                    <nav class="space-y-6">
                        @foreach($categories as $category)
                            <div>
                                <h3 class="text-lg font-medium text-indigo-400 mb-2">{{ $category->name }}</h3>
                                <ul class="space-y-2">
                                    @foreach($category->guides as $guide)
                                        <li>
                                            <button 
                                                wire:click="selectGuide('{{ $guide->slug }}')"
                                                class="w-full text-left px-3 py-2 rounded-lg text-sm {{ $selectedGuide && $selectedGuide->id === $guide->id ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }} transition-colors duration-150"
                                            >
                                                {{ $guide->title }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1 p-8">
                    @if($selectedGuide)
                        <div class="prose prose-invert max-w-none">
                            <h1 class="text-2xl font-bold text-white mb-4">{{ $selectedGuide->title }}</h1>
                            <div class="mt-4 trix-content">
                                <style>
                                    .trix-content {
                                        @apply text-gray-300;
                                    }
                                    .trix-content h1 {
                                        @apply text-2xl font-bold mb-4 text-white;
                                    }
                                    .trix-content img {
                                        @apply max-w-full h-auto rounded-lg shadow-lg my-4;
                                    }
                                    .trix-content figure {
                                        @apply my-4;
                                    }
                                    .trix-content figcaption {
                                        @apply text-sm text-gray-400 text-center mt-2;
                                    }
                                    .trix-content ul {
                                        @apply list-disc list-inside mb-4;
                                    }
                                    .trix-content ol {
                                        @apply list-decimal list-inside mb-4;
                                    }
                                    .trix-content a {
                                        @apply text-indigo-400 hover:text-indigo-300 underline;
                                    }
                                    .trix-content blockquote {
                                        @apply border-l-4 border-indigo-400 pl-4 italic my-4;
                                    }
                                    .trix-content pre {
                                        @apply bg-gray-900 rounded-lg p-4 overflow-x-auto;
                                    }
                                    .trix-content code {
                                        @apply bg-gray-900 rounded px-1 py-0.5;
                                    }
                                </style>
                                {!! $selectedGuide->content !!}
                            </div>
                        </div>
                    @else
                        <div class="text-center text-gray-400">
                            <p>Select a guide from the sidebar to view its content.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
