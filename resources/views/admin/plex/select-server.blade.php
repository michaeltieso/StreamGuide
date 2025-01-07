<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Select Plex Server') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Connected as {{ $user['username'] }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $user['email'] }}
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:rounded-lg sm:p-6">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <div class="md:col-span-1">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Select Server
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Choose which Plex server to connect to.
                                    </p>
                                </div>
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <form action="{{ route('admin.plex.save-server') }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-6">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Available Servers
                                                </label>
                                                <div class="mt-4 space-y-4">
                                                    @foreach ($servers as $server)
                                                        <div class="flex items-center">
                                                            <input
                                                                id="server_{{ $server['machine_identifier'] }}"
                                                                name="server_id"
                                                                type="radio"
                                                                value="{{ $server['machine_identifier'] }}"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                                                required
                                                            >
                                                            <label for="server_{{ $server['machine_identifier'] }}" class="ml-3">
                                                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                    {{ $server['name'] }}
                                                                </span>
                                                                <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                                    Version {{ $server['version'] }}
                                                                    @if($server['owned'])
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                                            Owner
                                                                        </span>
                                                                    @endif
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <div class="flex justify-end space-x-3">
                                                <a 
                                                    href="{{ route('admin.index') }}"
                                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                >
                                                    Back to Settings
                                                </a>
                                                <button
                                                    type="submit"
                                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                >
                                                    Connect
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
