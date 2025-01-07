<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- FAQ List Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-white">
                                    Manage FAQs
                                </h2>
                                <div class="flex space-x-4">
                                    <x-button wire:navigate href="{{ route('admin.faq-categories') }}" class="bg-gray-700 hover:bg-gray-600">
                                        Manage Categories
                                    </x-button>
                                    <x-button wire:click="createFaq">
                                        Create FAQ
                                    </x-button>
                                </div>
                            </div>

                            <!-- FAQ List -->
                            <div class="mt-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Question</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Answer</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Order</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                                            @foreach($faqs as $faq)
                                                <tr>
                                                    <td class="px-6 py-4 text-sm text-gray-300">
                                                        {{ $faq->question }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-300">
                                                        {{ Str::limit($faq->answer, 100) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $faq->order }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        <button wire:click="editFaq({{ $faq->id }})" class="text-indigo-400 hover:text-indigo-300 mr-3">
                                                            Edit
                                                        </button>
                                                        <button wire:click="deleteFaq({{ $faq->id }})" class="text-red-400 hover:text-red-300">
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

                    <!-- FAQ Edit Form -->
                    @if($editingFaq)
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <h2 class="text-lg font-medium text-white mb-6">
                                {{ $currentFaq ? 'Edit FAQ' : 'Create FAQ' }}
                            </h2>

                            <form wire:submit.prevent="saveFaq" class="space-y-6">
                                <div>
                                    <x-label for="question" value="Question" />
                                    <x-input id="question" type="text" class="mt-1 block w-full" wire:model="question" />
                                    <x-input-error for="question" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="answer" value="Answer" />
                                    <textarea
                                        id="answer"
                                        rows="6"
                                        class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        wire:model="answer"
                                    ></textarea>
                                    <x-input-error for="answer" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="faq_category_id" value="Category" />
                                    <select id="faq_category_id" 
                                            wire:model="faq_category_id"
                                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select a category</option>
                                        @foreach($faqCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error for="faq_category_id" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="order" value="Order" />
                                    <x-input id="order" type="number" class="mt-1 block w-full" wire:model="order" />
                                    <x-input-error for="order" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-button type="submit">
                                        Save FAQ
                                    </x-button>

                                    <x-button type="button" wire:click="resetForm" class="bg-gray-700">
                                        Cancel
                                    </x-button>

                                    <x-action-message class="mr-3" on="faq-saved">
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
