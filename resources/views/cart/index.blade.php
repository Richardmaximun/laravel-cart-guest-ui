@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4 space-y-4">
  <h1 class="text-xl font-semibold">Tu carrito</h1>

  @forelse($items as $it)
    <div class="flex gap-3 items-center border rounded p-3">
      <img src="{{ $it['thumb'] ?: asset(config('cart-guest-ui.media.placeholder')) }}" class="w-16 h-16 object-cover rounded" alt="">
      <div class="flex-1">
        <div class="font-medium">{{ $it['name'] }}</div>
        <form method="POST" action="{{ route(config('cart-guest-ui.routes.names.update'), ['type' => $it['type'], 'id' => $it['pk']]) }}" class="flex items-center gap-2 mt-1">
          @csrf @method('PATCH')
          <input type="number" name="qty" min="0" value="{{ $it['qty'] }}" class="w-16 border rounded px-2 py-1">
          <button class="text-sm underline">Actualizar</button>
        </form>
      </div>
      <form method="POST" action="{{ route(config('cart-guest-ui.routes.names.destroy'), ['type' => $it['type'], 'id' => $it['pk']]) }}">
        @csrf @method('DELETE')
        <button class="text-sm text-red-600">Eliminar</button>
      </form>
    </div>
  @empty
    <div class="text-gray-600">Aún no tienes productos.</div>
  @endforelse
</div>
@endsection
