<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- Category List Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-white">
                                    Manage FAQ Categories
                                </h2>
                                <div class="flex space-x-4">
                                    <x-button wire:click="createCategory">
                                        Create Category
                                    </x-button>
                                    <x-button wire:navigate href="{{ route('admin.faqs') }}" class="bg-gray-700 hover:bg-gray-600">
                                        Back to FAQs
                                    </x-button>
                                </div>
                            </div>

                            <!-- Category List -->
                            <div class="mt-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Order</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">FAQs</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                                            @foreach($faqCategories as $category)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $category->name }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-300">
                                                        {{ $category->description }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $category->order }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $category->faqs->count() }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        <button wire:click="editCategory({{ $category->id }})" class="text-indigo-400 hover:text-indigo-300 mr-3">
                                                            Edit
                                                        </button>
                                                        <button wire:click="deleteCategory({{ $category->id }})" class="text-red-400 hover:text-red-300">
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

                    <!-- Category Edit Form -->
                    @if($editingCategory)
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <h2 class="text-lg font-medium text-white mb-6">
                                {{ $currentCategory ? 'Edit Category' : 'Create Category' }}
                            </h2>

                            <form wire:submit.prevent="saveCategory" class="space-y-6">
                                <div>
                                    <x-label for="name" value="Name" />
                                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                                    <x-input-error for="name" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="description" value="Description" />
                                    <textarea id="description"
                                            wire:model="description"
                                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            rows="3"></textarea>
                                    <x-input-error for="description" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="order" value="Order" />
                                    <x-input id="order" type="number" class="mt-1 block w-full" wire:model="order" />
                                    <x-input-error for="order" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-button type="submit">
                                        Save Category
                                    </x-button>

                                    <x-button type="button" wire:click="resetForm" class="bg-gray-700">
                                        Cancel
                                    </x-button>

                                    <x-action-message class="mr-3" on="saved">
                                        Saved.
                                    </x-action-message>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

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
</div> 