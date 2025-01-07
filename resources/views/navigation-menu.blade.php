<nav x-data="{ open: false }" class="relative z-50 bg-gray-900">
    <!-- Primary Navigation Menu -->
    <div class="relative bg-gray-900/95 backdrop-blur-xl border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="group">
                            <x-application-logo class="block h-9 w-auto text-gray-200 transition-all duration-300 group-hover:text-indigo-400" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')"
                            class="transition-colors duration-300 ease-in-out {{ request()->routeIs('home') ? 'text-indigo-400 border-indigo-400' : 'text-gray-300 hover:text-indigo-400 border-transparent hover:border-indigo-400/50' }}">
                            {{ __('Home') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('guide') }}" :active="request()->routeIs('guide')"
                            class="transition-colors duration-300 ease-in-out {{ request()->routeIs('guide') ? 'text-indigo-400 border-indigo-400' : 'text-gray-300 hover:text-indigo-400 border-transparent hover:border-indigo-400/50' }}">
                            {{ __('Guide') }}
                        </x-nav-link>

                        @if (auth()->user()->is_admin)
                            <x-nav-link href="{{ route('admin.index') }}" :active="request()->routeIs('admin.index')"
                                class="transition-colors duration-300 ease-in-out {{ request()->routeIs('admin.index') ? 'text-indigo-400 border-indigo-400' : 'text-gray-300 hover:text-indigo-400 border-transparent hover:border-indigo-400/50' }}">
                                {{ __('Admin') }}
                            </x-nav-link>
                        @endif
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <!-- Teams Dropdown -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="ms-3 relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-700 text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-900 hover:text-indigo-400 hover:border-indigo-400/50 focus:outline-none transition ease-in-out duration-300">
                                            {{ Auth::user()->currentTeam->name }}

                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60 bg-gray-900 border border-gray-800 rounded-lg shadow-2xl">
                                        <!-- Team Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Team') }}
                                        </div>

                                        <!-- Team Settings -->
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link>

                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan

                                        <!-- Team Switcher -->
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-800"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Switch Teams') }}
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm border-2 border-gray-700 rounded-full focus:outline-none focus:border-indigo-400/50 transition">
                                        <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-700 text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-900 hover:text-indigo-400 hover:border-indigo-400/50 focus:outline-none transition ease-in-out duration-300">
                                            {{ Auth::user()->name }}

                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-2xl">
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ Auth::user()->email }}
                                    </div>

                                    @if(Auth::user()->isAdmin())
                                        <x-dropdown-link href="{{ route('profile.show') }}" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <x-dropdown-link href="{{ route('api-tokens.index') }}" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                                                {{ __('API Tokens') }}
                                            </x-dropdown-link>
                                        @endif

                                        <div class="border-t border-gray-800"></div>
                                    @endif

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf

                                        <x-dropdown-link href="{{ route('logout') }}"
                                                @click.prevent="$root.submit();" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-indigo-400 hover:bg-gray-800 focus:outline-none transition duration-300">
                        <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-gray-900 border-b border-gray-800">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('guide') }}" :active="request()->routeIs('guide')" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                {{ __('Guide') }}
            </x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link href="{{ route('admin.index') }}" :active="request()->routeIs('admin.index')" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                    {{ __('Admin') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-800 bg-gray-900">
            <div class="flex items-center px-4">
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::user()->isAdmin())
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();" class="text-gray-300 hover:text-indigo-400 hover:bg-gray-800">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
