<div class="min-h-screen bg-gray-900">
    <!-- Page Content -->
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div class="bg-gray-800 shadow-xl rounded-lg overflow-hidden">
                    <div class="p-8">
                        <div class="prose prose-invert max-w-none">
                            <style>
                                /* Remove file name and size from Trix attachments */
                                .attachment__caption {
                                    display: none !important;
                                }
                                /* Remove link from images */
                                .attachment--preview {
                                    cursor: default !important;
                                }
                                .attachment--preview a {
                                    pointer-events: none !important;
                                    cursor: default !important;
                                    text-decoration: none !important;
                                }
                                /* Style the images */
                                .attachment--preview img {
                                    max-width: 100% !important;
                                    height: auto !important;
                                    margin: 1rem 0 !important;
                                    border-radius: 0.375rem !important;
                                }
                            </style>
                            <h1>{{ $guide->title }}</h1>
                            {!! $guide->content !!}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div> 