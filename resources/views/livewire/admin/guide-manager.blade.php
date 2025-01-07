<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- Guide List Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-white">
                                    Manage Guides
                                </h2>
                                <div class="flex space-x-4">
                                    <x-button wire:click="createGuide">
                                        Create Guide
                                    </x-button>
                                    <x-button wire:navigate href="{{ route('admin.guide-categories') }}" class="bg-gray-700 hover:bg-gray-600">
                                        Manage Categories
                                    </x-button>
                                </div>
                            </div>

                            <!-- Guide List -->
                            <div class="mt-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Title</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Category</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Order</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                                            @foreach($guides as $guide)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $guide->title }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $guide->category->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $guide->order }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        <button wire:click="editGuide({{ $guide->id }})" class="text-indigo-400 hover:text-indigo-300 mr-3">
                                                            Edit
                                                        </button>
                                                        <button wire:click="deleteGuide({{ $guide->id }})" class="text-red-400 hover:text-red-300">
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

                    <!-- Guide Edit Form -->
                    @if($editingGuide)
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <h2 class="text-lg font-medium text-white mb-6">
                                {{ $currentGuide ? 'Edit Guide' : 'Create Guide' }}
                            </h2>

                            <form wire:submit.prevent="saveGuide" class="space-y-6">
                                <div>
                                    <x-label for="title" value="Title" />
                                    <x-input id="title" type="text" class="mt-1 block w-full" wire:model="title" />
                                    <x-input-error for="title" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="slug" value="Slug" />
                                    <x-input id="slug" type="text" class="mt-1 block w-full" wire:model="slug" />
                                    <x-input-error for="slug" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="category_id" value="Category" />
                                    <select id="category_id" 
                                            wire:model="category_id"
                                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error for="category_id" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="order" value="Order" />
                                    <x-input id="order" type="number" class="mt-1 block w-full" wire:model="order" />
                                    <x-input-error for="order" class="mt-2" />
                                </div>

                                <!-- Content Editor -->
                                <div>
                                    <x-label for="content" value="Content" />
                                    <div class="mt-1">
                                        <div class="rounded-md shadow-sm">
                                            <div class="mt-1">
                                                <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
                                                <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
                                                <style>
                                                    trix-editor {
                                                        min-height: 24rem !important;
                                                        max-height: 36rem !important;
                                                        overflow-y: auto !important;
                                                        color: #e2e8f0 !important;
                                                        background-color: #1a202c !important;
                                                        border: 1px solid #4a5568 !important;
                                                        border-radius: 0.375rem !important;
                                                    }
                                                    trix-toolbar {
                                                        background-color: #2d3748 !important;
                                                        padding: 0.5rem !important;
                                                        border-radius: 0.375rem 0.375rem 0 0 !important;
                                                        border: 1px solid #4a5568 !important;
                                                        border-bottom: none !important;
                                                    }
                                                    trix-toolbar .trix-button-group {
                                                        border: 1px solid #4a5568 !important;
                                                        border-radius: 0.25rem !important;
                                                        margin-bottom: 0.25rem !important;
                                                    }
                                                    trix-toolbar .trix-button {
                                                        border: none !important;
                                                        background: #374151 !important;
                                                        color: #e2e8f0 !important;
                                                    }
                                                    trix-toolbar .trix-button:not(:first-child) {
                                                        border-left: 1px solid #4a5568 !important;
                                                    }
                                                    trix-toolbar .trix-button.trix-active {
                                                        background: #4f46e5 !important;
                                                    }
                                                    trix-toolbar .trix-button:hover:not(.trix-active) {
                                                        background: #4a5568 !important;
                                                    }
                                                    trix-toolbar .trix-button::before {
                                                        filter: invert(100%) !important;
                                                    }
                                                    trix-editor:focus {
                                                        outline: 2px solid #4f46e5 !important;
                                                        outline-offset: 2px !important;
                                                    }
                                                    trix-editor a {
                                                        color: #93c5fd !important;
                                                    }
                                                    trix-editor ul {
                                                        list-style-type: disc !important;
                                                        padding-left: 1.5rem !important;
                                                    }
                                                    trix-editor ol {
                                                        list-style-type: decimal !important;
                                                        padding-left: 1.5rem !important;
                                                    }
                                                    trix-editor h1 {
                                                        font-size: 1.5rem !important;
                                                        font-weight: bold !important;
                                                        margin: 1rem 0 !important;
                                                    }
                                                    trix-editor blockquote {
                                                        border-left: 3px solid #4a5568 !important;
                                                        padding-left: 1rem !important;
                                                        color: #9ca3af !important;
                                                    }
                                                    trix-editor pre {
                                                        background: #374151 !important;
                                                        padding: 1rem !important;
                                                        border-radius: 0.375rem !important;
                                                        margin: 1rem 0 !important;
                                                    }
                                                    trix-editor img {
                                                        max-width: 100% !important;
                                                        height: auto !important;
                                                        margin: 1rem 0 !important;
                                                        border-radius: 0.375rem !important;
                                                    }
                                                </style>
                                                <div wire:ignore>
                                                    <input id="content-editor" type="hidden" name="content" value="{{ $content }}">
                                                    <trix-editor
                                                        input="content-editor"
                                                        class="trix-content"
                                                        x-data
                                                        x-on:trix-change="$dispatch('input', event.target.value)"
                                                        x-on:trix-file-accept="
                                                            if (!event.file.type.includes('image/')) {
                                                                event.preventDefault();
                                                                alert('Only images can be uploaded');
                                                                return;
                                                            }
                                                        "
                                                        x-on:trix-attachment-add="
                                                            let attachment = $event.attachment;
                                                            if (attachment.file) {
                                                                let reader = new FileReader();
                                                                reader.onload = function(e) {
                                                                    let fileData = {
                                                                        name: attachment.file.name,
                                                                        type: attachment.file.type,
                                                                        data: e.target.result.split(',')[1]
                                                                    };
                                                                    @this.handleFileUpload(fileData).then(url => {
                                                                        if (url) {
                                                                            attachment.setAttributes({
                                                                                url: url,
                                                                                href: url
                                                                            });
                                                                        }
                                                                    });
                                                                };
                                                                reader.readAsDataURL(attachment.file);
                                                            }
                                                        "
                                                        wire:model.defer="content"
                                                    ></trix-editor>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <x-input-error for="content" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-button type="submit">
                                        Save Guide
                                    </x-button>

                                    <x-button type="button" wire:click="resetForm" class="bg-gray-700">
                                        Cancel
                                    </x-button>

                                    <x-action-message class="mr-3" on="guide-saved">
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
