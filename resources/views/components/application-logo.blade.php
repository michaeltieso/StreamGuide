@php
    $logoUrl = App\Models\SiteSetting::get('logo_url');
@endphp

<div
    x-data="{ 
        logoUrl: '{{ $logoUrl }}',
        updateLogo(e) {
            this.logoUrl = e.detail.logoUrl || '';
        }
    }"
    x-on:logo-updated.window="updateLogo($event)"
>
    @if ($logoUrl)
        <img src="{{ $logoUrl }}" {{ $attributes->merge(['class' => 'block h-12 w-auto']) }} alt="Logo" />
    @else
        <img src="{{ asset('images/logos/default-logo.png') }}" {{ $attributes->merge(['class' => 'block h-12 w-auto']) }} alt="{{ config('app.name') }}" />
    @endif
</div>
