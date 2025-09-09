<div class="relative" @if($poll>0) wire:poll.{{ $poll }}s @endif>
  <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"><path d="M0 0h24v24H0z" fill="none"/><path d="M7 4h-2l-1 2v2h2l3 7h8l3-7h2V6h-3l-1 2H9"/></svg>
    <span class="inline-flex items-center justify-center text-xs font-semibold rounded-full min-w-5 h-5 px-1 bg-black text-white">{{ $count }}</span>
  </a>

  @if($miniCart)
  <div class="absolute right-0 mt-2 w-72 rounded-md border bg-white shadow z-50"
       x-data="{open:false}" @mouseenter="open=true" @mouseleave="open=false">
    <button class="sr-only" type="button" @click="open=!open">Toggle mini cart</button>
    <div x-show="open" class="p-3 space-y-2">
      @forelse($items as $it)
        <div class="flex gap-2">
          <img src="{{ $it['thumb'] ?: asset(config('cart-guest-ui.media.placeholder')) }}" class="w-12 h-12 object-cover rounded" alt="">
          <div class="flex-1">
            <div class="text-sm font-medium truncate">{{ $it['name'] }}</div>
            <div class="text-xs text-gray-500">x{{ $it['qty'] }}</div>
          </div>
        </div>
      @empty
        <div class="text-sm text-gray-500">Tu carrito está vacío.</div>
      @endforelse
      <div class="pt-2">
        <a href="{{ route('cart.index') }}" class="text-sm underline">Ver carrito</a>
      </div>
    </div>
  </div>
  @endif
</div>
