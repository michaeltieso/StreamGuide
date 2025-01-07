<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- User List Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-white">
                                    Manage Users
                                </h2>
                                <x-button wire:click="createUser">
                                    Create User
                                </x-button>
                            </div>

                            <!-- User List -->
                            <div class="mt-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Role</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                                            @foreach($users as $user)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $user->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $user->email }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        <button wire:click="editUser({{ $user->id }})" class="text-indigo-400 hover:text-indigo-300 mr-3">
                                                            Edit
                                                        </button>
                                                        <button wire:click="deleteUser({{ $user->id }})" class="text-red-400 hover:text-red-300">
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

                    <!-- User Edit Form -->
                    @if($editingUser)
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <h2 class="text-lg font-medium text-white mb-6">
                                {{ $currentUser ? 'Edit User' : 'Create User' }}
                            </h2>

                            <form wire:submit.prevent="saveUser" class="space-y-6">
                                <div>
                                    <x-label for="name" value="Name" />
                                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                                    <x-input-error for="name" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="email" value="Email" />
                                    <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                                    <x-input-error for="email" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="password" value="{{ $currentUser ? 'New Password (leave blank to keep current)' : 'Password' }}" />
                                    <x-input id="password" type="password" class="mt-1 block w-full" wire:model="password" />
                                    <x-input-error for="password" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="password_confirmation" value="Confirm Password" />
                                    <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="password_confirmation" />
                                </div>

                                <div class="block">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_admin" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-300">Admin User</span>
                                    </label>
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-button type="submit">
                                        Save User
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
                </div>
            </main>
        </div>
    </div>
</div> 