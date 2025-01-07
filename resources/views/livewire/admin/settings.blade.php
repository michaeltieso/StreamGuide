<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="space-y-6">
                    <!-- General Settings Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-4xl">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-white">
                                        General Settings
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Basic application and server settings.
                                    </p>
                                </header>

                                <div class="mt-6 space-y-6">
                                    <!-- Application Name -->
                                    <div class="max-w-xl">
                                        <x-label for="appName" value="Application Name" />
                                        <x-input id="appName" type="text" class="mt-1 block w-full" wire:model="appName" />
                                        <x-input-error for="appName" class="mt-2" />
                                    </div>

                                    <!-- Server Title -->
                                    <div class="max-w-xl">
                                        <x-label for="serverTitle" value="Server Title" />
                                        <x-input id="serverTitle" type="text" class="mt-1 block w-full" wire:model="serverTitle" />
                                        <x-input-error for="serverTitle" class="mt-2" />
                                    </div>

                                    <!-- Server Description -->
                                    <div>
                                        <x-label for="serverDescription" value="Server Description" />
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
                                                        /* Custom color styles */
                                                        .color-red { color: #ef4444 !important; }
                                                        .color-green { color: #10b981 !important; }
                                                        .color-blue { color: #3b82f6 !important; }
                                                        .color-yellow { color: #f59e0b !important; }
                                                        .color-purple { color: #8b5cf6 !important; }
                                                    </style>
                                                    <script>
                                                        addEventListener("trix-initialize", function(event) {
                                                            // Add color picker button group
                                                            var colorGroup = document.createElement("div");
                                                            colorGroup.classList.add("trix-button-group");
                                                            colorGroup.innerHTML = `
                                                                <button type="button" class="trix-button" data-trix-attribute="color-red" title="Red">
                                                                    <span style="color: #ef4444">A</span>
                                                                </button>
                                                                <button type="button" class="trix-button" data-trix-attribute="color-green" title="Green">
                                                                    <span style="color: #10b981">A</span>
                                                                </button>
                                                                <button type="button" class="trix-button" data-trix-attribute="color-blue" title="Blue">
                                                                    <span style="color: #3b82f6">A</span>
                                                                </button>
                                                                <button type="button" class="trix-button" data-trix-attribute="color-yellow" title="Yellow">
                                                                    <span style="color: #f59e0b">A</span>
                                                                </button>
                                                                <button type="button" class="trix-button" data-trix-attribute="color-purple" title="Purple">
                                                                    <span style="color: #8b5cf6">A</span>
                                                                </button>
                                                            `;
                                                            
                                                            var toolbar = event.target.toolbarElement;
                                                            toolbar.querySelector(".trix-button-groups").appendChild(colorGroup);

                                                            // Add custom link button with options
                                                            var linkButton = toolbar.querySelector("[data-trix-attribute=href]");
                                                            if (linkButton) {
                                                                linkButton.addEventListener("click", function(e) {
                                                                    if (!linkButton.classList.contains("trix-active")) {
                                                                        var url = prompt("Enter URL:", "https://");
                                                                        if (url) {
                                                                            var text = prompt("Enter link text (optional):");
                                                                            var editor = event.target.editor;
                                                                            if (text) {
                                                                                editor.insertHTML(`<a href="${url}">${text}</a>`);
                                                                            } else {
                                                                                editor.activateAttribute("href", url);
                                                                            }
                                                                        }
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                            }
                                                        });

                                                        // Register custom attributes
                                                        addEventListener("trix-before-initialize", function(event) {
                                                            var colors = ["red", "green", "blue", "yellow", "purple"];
                                                            colors.forEach(function(color) {
                                                                Trix.config.textAttributes[`color-${color}`] = {
                                                                    style: { color: `var(--color-${color})` },
                                                                    parser: function(element) {
                                                                        return element.style.color === `var(--color-${color})`;
                                                                    },
                                                                    inheritable: true
                                                                };
                                                            });
                                                        });
                                                    </script>
                                                    <div class="body-content">
                                                        <trix-editor
                                                            class="trix-content"
                                                            x-data
                                                            x-on:trix-change="$dispatch('input', $event.target.value)"
                                                            wire:model.defer="serverDescription"
                                                            wire:key="trix-content-unique-key"
                                                        ></trix-editor>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <x-input-error for="serverDescription" class="mt-2" />
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-button wire:click="saveGeneralSettings">
                                            Save Settings
                                        </x-button>

                                        <x-action-message class="mr-3" on="settings-saved">
                                            Saved.
                                        </x-action-message>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <!-- Maintenance Window Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-white">
                                        Maintenance Window
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Configure the server maintenance window when Plex runs scheduled tasks.
                                    </p>
                                </header>

                                <div class="mt-6 space-y-6">
                                    <!-- Enable/Disable Maintenance Window -->
                                    <div class="flex items-center">
                                        <x-label for="maintenanceEnabled" value="Enable Maintenance Window" class="mr-4" />
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model.defer="maintenanceEnabled" class="sr-only peer" id="maintenanceEnabled">
                                            <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>

                                    <!-- Maintenance Window Times -->
                                    <div x-data="{ enabled: @entangle('maintenanceEnabled') }" x-show="enabled" class="space-y-4">
                                        <div>
                                            <x-label for="maintenanceStart" value="Start Time" />
                                            <x-input 
                                                id="maintenanceStart" 
                                                type="time" 
                                                class="mt-1 block w-full" 
                                                wire:model.defer="maintenanceStart"
                                            />
                                            <x-input-error for="maintenanceStart" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-label for="maintenanceEnd" value="End Time" />
                                            <x-input 
                                                id="maintenanceEnd" 
                                                type="time" 
                                                class="mt-1 block w-full" 
                                                wire:model.defer="maintenanceEnd"
                                            />
                                            <x-input-error for="maintenanceEnd" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-label for="maintenanceMessage" value="Custom Message (Optional)" />
                                            <x-input 
                                                id="maintenanceMessage" 
                                                type="text" 
                                                class="mt-1 block w-full" 
                                                wire:model.defer="maintenanceMessage"
                                                placeholder="Enter a custom message to display during maintenance"
                                            />
                                            <x-input-error for="maintenanceMessage" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-button wire:click="saveMaintenanceSettings">
                                            Save Maintenance Settings
                                        </x-button>

                                        <x-action-message class="mr-3" on="maintenance-settings-saved">
                                            Saved.
                                        </x-action-message>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <!-- Logo Settings Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-white">
                                        Logo Settings
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Upload or set a URL for your application logo.
                                    </p>
                                </header>

                                <div class="mt-6 space-y-6">
                                    <!-- Current Logo -->
                                    @if($currentLogo)
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-300">Current Logo</h3>
                                            <div class="mt-2">
                                                <img src="{{ $currentLogo }}" alt="Current Logo" class="max-w-xs">
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Logo URL -->
                                    <div>
                                        <x-label for="logoUrl" value="Logo URL" />
                                        <div class="flex mt-1">
                                            <x-input id="logoUrl" type="text" class="block w-full" wire:model.defer="logoUrl" />
                                            <x-button class="ml-4" wire:click="updateLogoUrl">
                                                Update URL
                                            </x-button>
                                        </div>
                                        <x-input-error for="logoUrl" class="mt-2" />
                                    </div>

                                    <!-- Logo Upload -->
                                    <div>
                                        <x-label for="logoFile" value="Upload Logo" />
                                        <div class="flex mt-1">
                                            <input type="file" wire:model="logoFile" id="logoFile" class="hidden">
                                            <label for="logoFile" class="cursor-pointer bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                                                Choose File
                                            </label>
                                            @if($logoFile)
                                                <x-button class="ml-4" wire:click="uploadLogo">
                                                    Upload
                                                </x-button>
                                            @endif
                                        </div>
                                        <x-input-error for="logoFile" class="mt-2" />
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <!-- Custom Styling Section -->
                    <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-white">
                                        Custom Styling
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Add custom CSS and JavaScript to customize your application.
                                    </p>
                                </header>

                                <div class="mt-6 space-y-6">
                                    <!-- Custom CSS -->
                                    <div>
                                        <x-label for="customCss" value="Custom CSS" />
                                        <textarea
                                            id="customCss"
                                            rows="10"
                                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            wire:model.defer="customCss"
                                            placeholder="/* Add your custom CSS here */"
                                        ></textarea>
                                        <x-input-error for="customCss" class="mt-2" />
                                    </div>

                                    <!-- Custom JavaScript -->
                                    <div>
                                        <x-label for="customJs" value="Custom JavaScript" />
                                        <textarea
                                            id="customJs"
                                            rows="10"
                                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            wire:model.defer="customJs"
                                            placeholder="// Add your custom JavaScript here"
                                        ></textarea>
                                        <x-input-error for="customJs" class="mt-2" />
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-button wire:click="saveCustomStyling">
                                            Save Custom Styling
                                        </x-button>

                                        <x-action-message class="mr-3" on="settings-saved">
                                            Saved.
                                        </x-action-message>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>