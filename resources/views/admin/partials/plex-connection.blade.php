<!-- Plex Connection Section -->
<div class="md:grid md:grid-cols-3 md:gap-6">
    <div class="md:col-span-1">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
            Plex Connection
        </h3>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Connect your Plex Media Server to enable media information and user validation.
        </p>
    </div>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 sm:p-6 bg-gray-50 dark:bg-gray-700 sm:rounded-lg">
            @if($plexSettings->access_token)
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Connected Account</h4>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $plexSettings->username }} ({{ $plexSettings->email }})
                    </p>
                </div>

                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Connected Server</h4>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $plexSettings->server_name }} (v{{ $plexSettings->server_version }})
                    </p>
                </div>

                <form action="{{ route('admin.plex.disconnect') }}" method="POST" class="mt-5">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Disconnect from Plex
                    </button>
                </form>
            @else
                <div class="text-center">
                    <button
                        onclick="window.open('{{ route('admin.plex.connect') }}', 'plex_auth', 'width=600,height=700')"
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Connect to Plex
                    </button>
                </div>
            @endif

            @if(session('show_plex_server_select') && session('plex_temp_servers'))
                <div class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-6">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Select Plex Server</h4>
                    
                    <form action="{{ route('admin.plex.save-server') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            @foreach (session('plex_temp_servers') as $server)
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

                        <div class="mt-6">
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Connect to Selected Server
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
