<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <!-- Server Info Section -->
                <div class="bg-gray-800 shadow-xl rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h1 class="text-2xl font-bold text-white mb-4">{{ $serverTitle }}</h1>
                        <div class="prose prose-invert max-w-none">
                            {!! $serverDescription !!}
                        </div>
                    </div>
                </div>

                <!-- Server Statistics -->
                <div class="mt-8 bg-gray-800 shadow-xl rounded-lg overflow-hidden" 
                    wire:init="init">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-6">Server Statistics</h3>

                        <!-- Maintenance Notice -->
                        @if($maintenanceEnabled && $maintenanceStart && $maintenanceEnd)
                            <div class="mb-4" x-data="{ 
                                start: '{{ $maintenanceStart }}',
                                end: '{{ $maintenanceEnd }}',
                                now: null,
                                timeUntil: '',
                                init() {
                                    this.updateTime();
                                    setInterval(() => this.updateTime(), 1000);
                                },
                                updateTime() {
                                    const now = new Date();
                                    const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                                    
                                    // Convert times to minutes since midnight for easier comparison
                                    const currentMinutes = now.getHours() * 60 + now.getMinutes();
                                    const [startHour, startMin] = this.start.split(':').map(Number);
                                    const [endHour, endMin] = this.end.split(':').map(Number);
                                    const startMinutes = startHour * 60 + startMin;
                                    const endMinutes = endHour * 60 + endMin;
                                    
                                    // Handle maintenance window that crosses midnight
                                    if (endMinutes < startMinutes) {
                                        // If current time is before end time OR after start time, we're in the window
                                        if (currentMinutes <= endMinutes || currentMinutes >= startMinutes) {
                                            const minutesUntilEnd = currentMinutes <= endMinutes 
                                                ? endMinutes - currentMinutes 
                                                : (24 * 60 - currentMinutes) + endMinutes;
                                            const hours = Math.floor(minutesUntilEnd / 60);
                                            const minutes = minutesUntilEnd % 60;
                                            this.timeUntil = `Ends in ${hours}h ${minutes}m`;
                                        } else {
                                            const minutesUntilStart = startMinutes - currentMinutes;
                                            const hours = Math.floor(minutesUntilStart / 60);
                                            const minutes = minutesUntilStart % 60;
                                            this.timeUntil = `Starts in ${hours}h ${minutes}m`;
                                        }
                                    } else {
                                        // Normal time window (doesn't cross midnight)
                                        if (currentMinutes >= startMinutes && currentMinutes <= endMinutes) {
                                            const minutesUntilEnd = endMinutes - currentMinutes;
                                            const hours = Math.floor(minutesUntilEnd / 60);
                                            const minutes = minutesUntilEnd % 60;
                                            this.timeUntil = `Ends in ${hours}h ${minutes}m`;
                                        } else {
                                            let minutesUntilStart;
                                            if (currentMinutes < startMinutes) {
                                                minutesUntilStart = startMinutes - currentMinutes;
                                            } else {
                                                minutesUntilStart = (24 * 60 - currentMinutes) + startMinutes;
                                            }
                                            const hours = Math.floor(minutesUntilStart / 60);
                                            const minutes = minutesUntilStart % 60;
                                            this.timeUntil = `Starts in ${hours}h ${minutes}m`;
                                        }
                                    }
                                }
                            }" x-init="init()">
                                <p class="text-xs text-gray-300">
                                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-1"></span>
                                    <span class="text-gray-200">Daily maintenance:</span> {{ $maintenanceStart }} - {{ $maintenanceEnd }} ({{ config('app.timezone') }})
                                    <span class="ml-1 text-gray-300" x-text="timeUntil"></span>
                                </p>
                            </div>
                        @endif

                        <!-- Debug Info -->
                        @if(app()->environment('local'))
                            <div class="text-xs text-gray-400 mb-4">
                                Last Updated: {{ now() }}
                            </div>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Server Status -->
                            <div class="group relative overflow-hidden rounded-lg border border-gray-800 bg-black/50 p-4 transition-all duration-300 hover:border-indigo-500/50 hover:bg-black/70">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-medium text-gray-400">Server Status</div>
                                    <div wire:loading.delay>
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <div wire:loading.delay.remove>
                                        <svg class="w-5 h-5 {{ $serverStats && $serverStats['status'] === 'online' ? 'text-green-400' : 'text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-2xl font-bold text-white">
                                    <span wire:loading.delay.class="opacity-50">{{ $serverStats ? ucfirst($serverStats['status']) : 'Unknown' }}</span>
                                </p>
                                @if($serverStats)
                                    <p class="mt-1 text-sm text-gray-400">Version {{ $serverStats['version'] }}</p>
                                @endif
                            </div>

                            <!-- Active Users -->
                            <div class="group relative overflow-hidden rounded-lg border border-gray-800 bg-black/50 p-4 transition-all duration-300 hover:border-indigo-500/50 hover:bg-black/70">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-medium text-gray-400">Active Users</div>
                                    <div wire:loading.delay>
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <div wire:loading.delay.remove>
                                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-2xl font-bold text-white">
                                    <span wire:loading.delay.class="opacity-50">{{ $serverStats ? $serverStats['activeUsers'] : '0' }}</span>
                                </p>
                                <p class="mt-1 text-sm text-gray-400">Currently Streaming</p>
                            </div>

                            @if($serverStats && !empty($serverStats['libraries']))
                                <div class="group relative overflow-hidden rounded-lg border border-gray-800 bg-black/50 p-4 transition-all duration-300 hover:border-indigo-500/50 hover:bg-black/70">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-gray-400">Movies</div>
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                        </svg>
                                    </div>
                                    <p class="mt-2 text-2xl font-bold text-white">
                                        <span wire:loading.delay.class="opacity-50">{{ number_format($serverStats['libraries']['movies']) }}</span>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-400">Total Movies</p>
                                </div>

                                <div class="group relative overflow-hidden rounded-lg border border-gray-800 bg-black/50 p-4 transition-all duration-300 hover:border-indigo-500/50 hover:bg-black/70">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-gray-400">TV Shows</div>
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="mt-2 text-2xl font-bold text-white">
                                        <span wire:loading.delay.class="opacity-50">{{ number_format($serverStats['libraries']['shows']) }}</span>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-400">Total Shows</p>
                                </div>

                                <div class="group relative overflow-hidden rounded-lg border border-gray-800 bg-black/50 p-4 transition-all duration-300 hover:border-indigo-500/50 hover:bg-black/70">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-gray-400">Music</div>
                                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                    </div>
                                    <p class="mt-2 text-2xl font-bold text-white">
                                        <span wire:loading.delay.class="opacity-50">{{ number_format($serverStats['libraries']['music']) }}</span>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-400">Total Albums</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- FAQs Section -->
                @if($faqCategories->isNotEmpty())
                    <div class="mt-8 bg-gray-800 shadow-xl rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-white mb-6">Frequently Asked Questions</h2>
                            <div class="space-y-8">
                                @foreach($faqCategories as $category)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-200 mb-4">{{ $category->name }}</h3>
                                        <div class="space-y-4">
                                            @foreach($category->faqs as $faq)
                                                <div class="bg-gray-700/50 rounded-lg p-4">
                                                    <h4 class="text-gray-200 font-medium mb-2">{{ $faq->question }}</h4>
                                                    <p class="text-gray-300">{{ $faq->answer }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
