<div class="min-h-screen bg-gray-900">
    <div class="flex min-h-screen">
        <x-sidebar :categories="$categories" :links="$links" />

        <!-- Main Content -->
        <div class="flex-1">
            <main class="p-6">
                <div x-data="{ activeTab: 'system' }" class="space-y-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-white">System Debug Information</h1>
                        <div class="flex space-x-2">
                            <button @click="activeTab = 'system'" 
                                    :class="{ 'bg-blue-600': activeTab === 'system', 'bg-gray-700': activeTab !== 'system' }"
                                    class="px-4 py-2 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                System Info
                            </button>
                            <button @click="activeTab = 'php'" 
                                    :class="{ 'bg-blue-600': activeTab === 'php', 'bg-gray-700': activeTab !== 'php' }"
                                    class="px-4 py-2 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                PHP Info
                            </button>
                            <button @click="activeTab = 'logs'" 
                                    :class="{ 'bg-blue-600': activeTab === 'logs', 'bg-gray-700': activeTab !== 'logs' }"
                                    class="px-4 py-2 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Latest Logs
                            </button>
                        </div>
                    </div>

                    <!-- System Info Tab -->
                    <div x-show="activeTab === 'system'" class="bg-gray-800 rounded-lg p-6">
                        <div class="bg-gray-900 p-4 rounded-lg">
                            <pre class="text-gray-300 font-mono text-sm whitespace-pre-wrap">@php
$output = "";
foreach ($systemInfo as $key => $value) {
    $output .= str_pad($key . ": ", 20);
    if (is_array($value)) {
        $output .= "\n";
        foreach ($value as $subKey => $subValue) {
            $output .= str_pad("  " . $subKey . ": ", 20) . $subValue . "\n";
        }
    } else {
        $output .= $value . "\n";
    }
}
echo trim($output);
@endphp</pre>
                            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.innerText)"
                                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                Copy to Clipboard
                            </button>
                        </div>
                    </div>

                    <!-- PHP Info Tab -->
                    <div x-show="activeTab === 'php'" class="bg-gray-800 rounded-lg p-6">
                        <div class="bg-gray-900 p-4 rounded-lg">
                            <div class="max-h-[600px] overflow-y-auto">
                                <pre class="text-gray-300 font-mono text-sm whitespace-pre-wrap break-words max-w-full">@php
$info = strip_tags($phpInfo);
// Split into sections and format
$sections = preg_split('/\n\n|\r\n\r\n/', $info);
$output = '';
foreach ($sections as $section) {
    // Format each line to wrap at 100 characters
    $lines = explode("\n", $section);
    foreach ($lines as $line) {
        $output .= wordwrap($line, 100, "\n", true) . "\n";
    }
    $output .= "\n";
}
echo trim($output);
@endphp</pre>
                            </div>
                            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.querySelector('pre').innerText)"
                                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                Copy to Clipboard
                            </button>
                        </div>
                    </div>

                    <!-- Logs Tab -->
                    <div x-show="activeTab === 'logs'" class="bg-gray-800 rounded-lg p-6">
                        <div class="bg-gray-900 p-4 rounded-lg">
                            <div class="max-h-[600px] overflow-y-auto">
                                <pre class="text-gray-300 font-mono text-sm whitespace-pre-wrap break-words max-w-full">@php
$output = '';
foreach ($latestLogs as $log) {
    // Format each line to wrap at 100 characters
    $output .= wordwrap($log, 100, "\n", true) . "\n\n";
}
echo trim($output);
@endphp</pre>
                            </div>
                            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.querySelector('pre').innerText)"
                                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                Copy to Clipboard
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div> 