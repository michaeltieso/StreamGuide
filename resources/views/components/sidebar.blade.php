<!-- Sidebar -->
<div class="w-72 bg-gray-800 min-h-screen">
    <nav class="p-4 space-y-4">
        <!-- Logo -->
        <div class="px-4 py-3">
            <a href="{{ route('home') }}" class="flex flex-col items-center text-center">
                <x-application-logo class="w-auto h-auto max-h-[200px] object-contain" />
                <span class="mt-4 text-xl font-bold text-white">{{ config('app.name') }}</span>
            </a>
        </div>

        <!-- Profile Dropdown -->
        <div class="px-4 py-3 border-t border-gray-700">
            <div x-data="{ open: false }" class="relative">
                <button 
                    @click="open = !open"
                    class="flex items-center w-full text-left text-gray-300 hover:text-white"
                >
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <svg class="ml-2 h-5 w-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div 
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute left-0 mt-2 w-full rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5"
                    style="display: none;"
                >
                    <div class="py-1">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white">
                                Profile
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <a href="{{ route('logout') }}"
                               @click.prevent="$root.submit();"
                               class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white"
                            >
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="space-y-1">
            <a href="{{ route('home') }}" 
               class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('home') ? 'bg-gray-700 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="truncate">Home</span>
            </a>

            <!-- Admin Section -->
            @if(Auth::user()->isAdmin())
                <div x-data="{ open: {{ request()->routeIs('admin.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex items-center w-full px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.*') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="truncate">Admin</span>
                        <svg class="w-5 h-5 ml-auto transform transition-transform duration-200" 
                             :class="{ 'rotate-180': open }"
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Admin Submenu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="mt-1 pl-6 space-y-1">
                        <a href="{{ route('admin.index') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.index') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                        <a href="{{ route('admin.guide') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.guide') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="truncate">Manage Guides</span>
                        </a>
                        <a href="{{ route('admin.links') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.links') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <span class="truncate">Manage Links</span>
                        </a>
                        <a href="{{ route('admin.faqs') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.faqs') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="truncate">Manage FAQs</span>
                        </a>
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.users') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="truncate">Users</span>
                        </a>
                        <a href="{{ route('admin.debug') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.debug') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Debug</span>
                        </a>
                        <a href="{{ route('admin.import') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.import') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span class="truncate">Import</span>
                        </a>
                        <a href="{{ route('admin.about') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.about') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="truncate">About</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Guide Categories -->
        @if(isset($categories) && $categories->isNotEmpty())
            <div class="mt-8 space-y-2">
                @foreach($categories as $category)
                    @if($category->guides && $category->guides->isNotEmpty())
                        <div x-data="{ open: false }" class="bg-gray-900/50 rounded-lg overflow-hidden">
                            <button @click="open = !open" 
                                    class="flex items-center justify-between w-full px-4 py-3 text-left text-gray-400 hover:text-gray-300 border-l-4 border-transparent hover:border-indigo-500 hover:bg-gray-700/50">
                                <span class="text-sm font-semibold uppercase tracking-wider">{{ $category->name }}</span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" 
                                     :class="{ 'rotate-180': open }"
                                     fill="none" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="border-l-4 border-indigo-500 bg-gray-700/30">
                                <div class="py-2 space-y-1">
                                    @foreach($category->guides as $guide)
                                        <a href="{{ route('guide.show', $guide->slug) }}" 
                                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('guide/' . $guide->slug) ? 'bg-gray-700 text-white' : '' }}">
                                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <span class="min-w-0 flex-1">
                                                <span class="block text-sm leading-5">{{ $guide->title }}</span>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <!-- Links Section -->
        @if(isset($links) && $links->isNotEmpty())
            <div class="mt-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider px-4 mb-2">
                    Quick Links
                </h3>
                @foreach($links as $link)
                    <a href="{{ $link->url }}" 
                       target="_blank"
                       class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm leading-5">{{ $link->title }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </nav>
</div> 