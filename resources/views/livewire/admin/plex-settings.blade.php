<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- Plex Connection Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-white">
                                        Plex Connection
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-400">
                                        Connect your Plex server to manage users and access.
                                    </p>
                                </header>

                                <div class="mt-6">
                                    @if($plexSettings && $plexSettings->access_token)
                                        <div class="mb-6">
                                            <h3 class="text-lg font-medium text-white mb-2">Connected Account</h3>
                                            <div class="flex items-center justify-between bg-gray-700 p-4 rounded-lg">
                                                <div>
                                                    <p class="text-white font-medium">{{ $plexSettings->username }}</p>
                                                    <p class="text-gray-400">{{ $plexSettings->email }}</p>
                                                </div>
                                                <div class="flex space-x-4">
                                                    <button wire:click="importPlexUsers" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                                                        Import Users
                                                    </button>
                                                    <button wire:click="disconnectPlex" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                                                        Disconnect
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        @if(!empty($servers))
                                            <div>
                                                <div class="flex justify-between items-center mb-4">
                                                    <h3 class="text-lg font-medium text-white">Available Servers</h3>
                                                    @if($selectedServer)
                                                        <button wire:click="$set('selectedServer', null)" class="px-3 py-1 text-sm bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                                            Change Server
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="grid gap-4">
                                                    @foreach($servers as $server)
                                                        @if(!$selectedServer || $selectedServer === $server['machineIdentifier'])
                                                            <div class="bg-gray-700 rounded-lg p-4">
                                                                <div class="flex items-center justify-between mb-4">
                                                                    <div>
                                                                        <h4 class="text-white font-medium">{{ $server['name'] }}</h4>
                                                                        <p class="text-gray-400 text-sm">{{ $server['machineIdentifier'] }}</p>
                                                                    </div>
                                                                    @if(!$selectedServer)
                                                                        <button wire:click="selectServer('{{ $server['machineIdentifier'] }}')" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                                            Select Server
                                                                        </button>
                                                                    @endif
                                                                </div>

                                                                @if($selectedServer === $server['machineIdentifier'])
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-400 mb-2">
                                                                            Connection
                                                                        </label>
                                                                        <select
                                                                            wire:model="selectedConnectionUrl"
                                                                            class="w-full bg-gray-800 border border-gray-600 rounded-md shadow-sm text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                                        >
                                                                            <option value="">Select Connection</option>
                                                                            @foreach($server['connections'] as $connection)
                                                                                <option value="{{ $connection['uri'] }}" {{ $selectedConnectionUrl === $connection['uri'] ? 'selected' : '' }}>
                                                                                    {{ $connection['local'] ? '🏠 Local' : '🌐 Remote' }} - {{ parse_url($connection['uri'], PHP_URL_HOST) }}:{{ parse_url($connection['uri'], PHP_URL_PORT) }}
                                                                                    {{ $connection['relay'] ? '(Relay)' : '' }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center">
                                            <button
                                                onclick="connectToPlex()"
                                                type="button"
                                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            >
                                                Connect to Plex
                                            </button>
                                        </div>

                                        <script>
                                            let checkAuthInterval;

                                            async function connectToPlex() {
                                                try {
                                                    const response = await fetch('{{ route('admin.plex.connect') }}');
                                                    const data = await response.json();
                                                    
                                                    if (data.authUrl) {
                                                        const authWindow = window.open(data.authUrl, 'plex_auth', 'width=600,height=700');
                                                        
                                                        // Start checking for auth completion
                                                        checkAuthInterval = setInterval(checkAuthStatus, 2000);
                                                        
                                                        // Clear interval when window closes
                                                        const checkClosed = setInterval(() => {
                                                            if (authWindow.closed) {
                                                                clearInterval(checkClosed);
                                                                clearInterval(checkAuthInterval);
                                                            }
                                                        }, 500);
                                                    }
                                                } catch (error) {
                                                    console.error('Failed to initiate Plex auth:', error);
                                                    alert('Failed to connect to Plex. Please try again.');
                                                }
                                            }

                                            async function checkAuthStatus() {
                                                try {
                                                    const response = await fetch('{{ route('admin.plex.callback') }}');
                                                    const data = await response.json();
                                                    
                                                    if (data.success) {
                                                        clearInterval(checkAuthInterval);
                                                        window.location.reload();
                                                    }
                                                } catch (error) {
                                                    // Ignore errors as they're expected until auth is complete
                                                }
                                            }
                                        </script>
                                    @endif
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div> 