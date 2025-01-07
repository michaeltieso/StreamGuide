<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- Import Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <h2 class="text-lg font-medium text-white mb-6">
                                Import Content
                            </h2>

                            <div class="space-y-6">
                                <!-- Basic Plex Guides -->
                                <div class="bg-gray-700/50 rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-white">Basic Plex Guides</h3>
                                            <p class="mt-2 text-sm text-gray-300">
                                                Import essential guides to help users understand and use Plex. Includes getting started guides, interface tutorials, and device setup instructions.
                                            </p>
                                            <ul class="mt-4 text-sm text-gray-300 list-disc list-inside space-y-1">
                                                <li>Welcome to Plex</li>
                                                <li>Getting Started</li>
                                                <li>Using the Plex Interface</li>
                                                <li>Watching on Different Devices</li>
                                                <li>Managing Your Experience</li>
                                            </ul>
                                        </div>
                                        <x-button wire:click="confirmImport('basic_guides')" class="shrink-0">
                                            Import Basic Guides
                                        </x-button>
                                    </div>
                                </div>

                                <!-- Server-Specific Guides -->
                                <div class="bg-gray-700/50 rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-white">Server-Specific Guides</h3>
                                            <p class="mt-2 text-sm text-gray-300">
                                                Import essential guides for using and understanding the Plex server, including request procedures, server rules, issue reporting, and feature overviews.
                                            </p>
                                            <ul class="mt-4 text-sm text-gray-300 list-disc list-inside space-y-1">
                                                <li>How to Request Movies or TV Shows</li>
                                                <li>Understanding Server Rules</li>
                                                <li>How to Report Issues</li>
                                                <li>Server Features Overview</li>
                                            </ul>
                                        </div>
                                        <x-button wire:click="importServerSpecificGuides" class="shrink-0">
                                            Import Server Guides
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    @if (session()->has('message'))
                        <div class="rounded-md bg-green-500/10 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-400">
                                        {{ session('message') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="rounded-md bg-red-500/10 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-400">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Import Confirmation Modal -->
    <div x-data="{ show: @entangle('showConfirmation') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Confirm Import
                        </h3>
                        <div class="mt-2">
                            @if($existingContent)
                                <p class="text-sm text-gray-400">
                                    This content already exists. Importing again will update the existing content with any changes. Do you want to continue?
                                </p>
                            @else
                                <p class="text-sm text-gray-400">
                                    Are you sure you want to import this content?
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="button"
                            wire:click="executeImport"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                        Import
                    </button>
                    <button type="button"
                            wire:click="cancelImport"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 