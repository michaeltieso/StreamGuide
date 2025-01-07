<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-gray-100">
                Welcome to StreamGuide
            </h2>
            <p class="mt-2 text-sm text-gray-400">
                Let's get your server set up
            </p>
        </div>

        <div class="mb-4">
            <div class="flex justify-center space-x-2">
                @for ($i = 1; $i <= 3; $i++)
                    <div @class([
                        'w-3 h-3 rounded-full',
                        'bg-blue-500' => $step >= $i,
                        'bg-gray-600' => $step < $i,
                    ])></div>
                @endfor
            </div>
        </div>

        @if ($step === 1)
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-100">Create Admin Account</h3>
                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input wire:model="name" id="name" class="block mt-1 w-full" type="text" required autofocus />
                    @error('name') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input wire:model="email" id="email" class="block mt-1 w-full" type="email" required />
                    @error('email') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input wire:model="password" id="password" class="block mt-1 w-full" type="password" required />
                    @error('password') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full" type="password" required />
                    @error('password_confirmation') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        @elseif ($step === 2)
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-100">Site Settings</h3>
                <div>
                    <x-label for="appName" value="{{ __('Application Name') }}" />
                    <x-input wire:model="appName" id="appName" class="block mt-1 w-full" type="text" required />
                    @error('appName') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <p class="text-sm text-gray-400">
                    This name will be displayed throughout your application. You can change it later in the admin settings.
                </p>
            </div>
        @elseif ($step === 3)
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-100">Review & Complete</h3>
                <!-- Add final review step here -->
            </div>
        @endif

        <div class="flex justify-between mt-6">
            @if ($step > 1)
                <x-button wire:click="previousStep" type="button">
                    Previous
                </x-button>
            @else
                <div></div>
            @endif

            @if ($step < 3)
                <x-button wire:click="nextStep" type="button">
                    Next
                </x-button>
            @else
                <x-button wire:click="finish" type="button">
                    Complete Setup
                </x-button>
            @endif
        </div>
    </div>
</div>
