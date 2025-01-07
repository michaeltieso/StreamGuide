@php use Illuminate\View\ComponentAttributeBag; @endphp

<div class="absolute inset-0">
    <div 
        class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-opacity duration-1000"
        style="background-image: url('{{ $backdrop }}');"
        x-data="{
            currentBackdrop: @entangle('backdrop'),
            init() {
                this.$watch('currentBackdrop', value => {
                    if (value) {
                        this.$el.style.backgroundImage = `url('${value}')`;
                    }
                });
            }
        }"
    >
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
</div>

@push('scripts')
@endpush
