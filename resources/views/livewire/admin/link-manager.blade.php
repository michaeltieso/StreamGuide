<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- Link List Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-white">
                                    Manage Links
                                </h2>
                                <x-button wire:click="createLink">
                                    Create Link
                                </x-button>
                            </div>

                            <!-- Link List -->
                            <div class="mt-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Title</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">URL</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Order</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                                            @foreach($links as $link)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $link->title }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        <a href="{{ $link->url }}" target="_blank" class="text-indigo-400 hover:text-indigo-300">
                                                            {{ $link->url }}
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $link->order }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        <button wire:click="editLink({{ $link->id }})" class="text-indigo-400 hover:text-indigo-300 mr-3">
                                                            Edit
                                                        </button>
                                                        <button wire:click="deleteLink({{ $link->id }})" class="text-red-400 hover:text-red-300">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Link Edit Form -->
                    @if($editingLink)
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <h2 class="text-lg font-medium text-white mb-6">
                                {{ $currentLink ? 'Edit Link' : 'Create Link' }}
                            </h2>

                            <form wire:submit.prevent="saveLink" class="space-y-6">
                                <div>
                                    <x-label for="title" value="Title" />
                                    <x-input id="title" type="text" class="mt-1 block w-full" wire:model="title" />
                                    <x-input-error for="title" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="url" value="URL" />
                                    <x-input id="url" type="url" class="mt-1 block w-full" wire:model="url" />
                                    <x-input-error for="url" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="order" value="Order" />
                                    <x-input id="order" type="number" class="mt-1 block w-full" wire:model="order" />
                                    <x-input-error for="order" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-button type="submit">
                                        Save Link
                                    </x-button>

                                    <x-button type="button" wire:click="resetForm" class="bg-gray-700">
                                        Cancel
                                    </x-button>

                                    <x-action-message class="mr-3" on="link-saved">
                                        Saved.
                                    </x-action-message>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</div>
