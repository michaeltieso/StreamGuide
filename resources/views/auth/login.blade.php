<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">
        <livewire:auth.login-backdrop />

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-800/80 backdrop-blur-sm shadow-md overflow-hidden sm:rounded-lg z-10 relative">
            <div class="mb-8 flex justify-center">
                @if(settings('logo_url'))
                    <img src="{{ settings('logo_url') }}" alt="{{ config('app.name') }}" class="h-20">
                @else
                    <img src="{{ asset('images/logos/default-logo.png') }}" alt="{{ config('app.name') }}" class="h-20">
                @endif
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('auth.plex') }}" 
                   onclick="console.log('Plex auth link clicked')"
                   class="w-full inline-flex justify-center items-center px-6 py-4 border border-transparent rounded-md font-semibold text-lg text-white bg-[#282A2D] hover:bg-[#cc7b19] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#cc7b19] transition-all duration-200 ease-in-out shadow-lg group">
                    <img src="{{ asset('images/plex-logo.svg') }}" alt="Plex" class="w-8 h-8 mr-3 text-[#cc7b19] group-hover:text-white transition-colors duration-200">
                    <span class="text-xl">Sign in with Plex</span>
                </a>
            </div>

            <div x-data="{ showAdmin: false }" class="text-center">
                <button 
                    type="button"
                    @click="showAdmin = !showAdmin"
                    class="text-sm text-gray-400 hover:text-gray-300 focus:outline-none focus:underline"
                >
                    <span x-show="!showAdmin">Admin Login</span>
                    <span x-show="showAdmin">Hide Admin Login</span>
                </button>

                <div 
                    x-show="showAdmin"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="mt-6"
                >
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        </div>

                        <div>
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="current-password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <x-checkbox id="remember_me" name="remember" />
                                <span class="ml-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <x-button class="w-full justify-center">
                            {{ __('Log in') }}
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wallpaper rotation logic will go here
        });
    </script>
    @endpush
</x-guest-layout>
