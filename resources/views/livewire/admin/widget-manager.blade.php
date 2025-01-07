<div class="p-6 bg-gray-800 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-white">Homepage Widgets</h2>
        <button wire:click="createWidget" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Add Widget
        </button>
    </div>

    <!-- Widget List -->
    <div class="space-y-4" wire:sortable="updateOrder">
        @foreach($widgets as $widget)
            <div wire:key="widget-{{ $widget->id }}" wire:sortable.item="{{ $widget->id }}" class="bg-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Drag Handle -->
                        <div wire:sortable.handle class="cursor-move text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                        </div>
                        
                        <!-- Widget Info -->
                        <div>
                            <h3 class="text-lg font-medium text-white">{{ $widget->title }}</h3>
                            <p class="text-sm text-gray-400">{{ $widgetTypes[$widget->type]['name'] }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <button wire:click="toggleWidget({{ $widget->id }})"
                                class="p-2 rounded-lg {{ $widget->active ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-500' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="{{ $widget->active ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}">
                                </path>
                            </svg>
                        </button>
                        <button wire:click="editWidget({{ $widget->id }})"
                                class="p-2 bg-blue-600 rounded-lg hover:bg-blue-700">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </button>
                        <button wire:click="deleteWidget({{ $widget->id }})"
                                class="p-2 bg-red-600 rounded-lg hover:bg-red-700">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Widget Modal -->
    <div x-data="{ show: @entangle('showWidgetModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-gray-800 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-white">
                        {{ $currentWidgetId ? 'Edit Widget' : 'Add Widget' }}
                    </h3>

                    <div class="mt-4 space-y-4">
                        <!-- Widget Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Widget Type</label>
                            <select wire:model="selectedType"
                                    class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a type</option>
                                @foreach($widgetTypes as $type => $details)
                                    <option value="{{ $type }}">{{ $details['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Widget Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Widget Title</label>
                            <input type="text" wire:model="widgetTitle"
                                   class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Dynamic Content Fields -->
                        @if($selectedType)
                            @foreach($widgetTypes[$selectedType]['fields'] as $field => $type)
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">
                                        {{ ucfirst($field) }}
                                    </label>
                                    @if($type === 'textarea')
                                        <textarea wire:model="widgetContent.{{ $field }}"
                                                  class="mt-1 block w-full rounded-md border-gray-700 bg-gray-700 text-white focus:border-indigo-500 focus:ring-indigo-500"
                                                  rows="4"></textarea>
                                    @elseif($type === 'array')
                                        <div class="space-y-2">
                                            @foreach($widgetContent[$field] ?? [] as $index => $item)
                                                <div class="flex items-center space-x-2">
                                                    <input type="text" wire:model="widgetContent.{{ $field }}.{{ $index }}"
                                                           class="flex-1 rounded-md border-gray-700 bg-gray-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                                                    <button wire:click="removeArrayItem('{{ $field }}', {{ $index }})"
                                                            class="p-2 text-red-400 hover:text-red-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                            <button wire:click="addArrayItem('{{ $field }}')"
                                                    class="mt-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500">
                                                Add Item
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-800 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="saveWidget"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button @click="show = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
