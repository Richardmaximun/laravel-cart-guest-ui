@php($mode = config('cart-guest-ui.badge.mode', 'livewire'))

@if($mode === 'ajax')
    @include('cart-guest-ui::components.cart.badge-ajax')
@else
    @if(class_exists(\Livewire\Livewire::class))
        <livewire:cart.badge />
    @else
        {{-- Fallback a AJAX si Livewire no está instalado --}}
        @include('cart-guest-ui::components.cart.badge-ajax')
    @endif
@endif
