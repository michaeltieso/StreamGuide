<!-- Admin Navigation -->
<nav class="bg-gray-800 border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-bold text-white">Admin Dashboard</h1>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('admin.index') }}" 
                           class="{{ request()->routeIs('admin.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                            Settings
                        </a>
                        <a href="{{ route('admin.guide') }}" 
                           class="{{ request()->routeIs('admin.guide') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                            Manage Guides
                        </a>
                        <a href="{{ route('admin.links') }}" 
                           class="{{ request()->routeIs('admin.links') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                            Manage Links
                        </a>
                        <a href="{{ route('admin.faqs') }}" 
                           class="{{ request()->routeIs('admin.faqs') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                            Manage FAQs
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button type="button" 
                        x-data="{ open: false }" 
                        @click="open = !open"
                        class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden" x-show="open" @click.away="open = false">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('admin.index') }}" 
               class="{{ request()->routeIs('admin.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Settings
            </a>
            <a href="{{ route('admin.guide') }}" 
               class="{{ request()->routeIs('admin.guide') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Manage Guides
            </a>
            <a href="{{ route('admin.links') }}" 
               class="{{ request()->routeIs('admin.links') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Manage Links
            </a>
            <a href="{{ route('admin.faqs') }}" 
               class="{{ request()->routeIs('admin.faqs') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Manage FAQs
            </a>
        </div>
    </div>
</nav> 