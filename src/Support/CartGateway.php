<?php

namespace Richardmaximun\CartGuestUi\Support;

use Binafy\LaravelCart\Facades\LaravelCart;
use Binafy\LaravelCart\Models\Cart as CartModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartGateway
{
    public function add(string $type, int $id, int $qty = 1, ?int $price = null): void
    {
        if (Auth::check()) {
            $model = $type::query()->findOrFail($id);
            LaravelCart::driver('database')->storeItem($model);
            if ($qty > 1) LaravelCart::driver('database')->increaseQuantity($model, $qty - 1);
            return;
        }

        $key = $this->guestKey();
        $cart = collect(session()->get($key, []));
        $row = $cart->firstWhere('itemable_id', $id);

        if ($row) {
            $cart = $cart->map(function ($it) use ($id, $qty) {
                if ($it['itemable_id'] === $id) $it['quantity'] = ($it['quantity'] ?? 1) + $qty;
                return $it;
            });
        } else {
            $cart->push([
                'itemable_type' => $type,
                'itemable_id'   => $id,
                'quantity'      => $qty,
                'price'         => $price,
            ]);
        }

        session()->put($key, $cart->values()->all());
    }

    public function setQuantity(string $type, int $id, int $qty): void
    {
        if (Auth::check()) {
            $model = $type::query()->findOrFail($id);
            LaravelCart::driver('database')->removeItem($model)->storeItem($model);
            if ($qty > 1) LaravelCart::driver('database')->increaseQuantity($model, $qty - 1);
            return;
        }

        $key = $this->guestKey();
        $cart = collect(session()->get($key, []))
            ->map(function ($it) use ($id, $qty) {
                if ($it['itemable_id'] === $id) $it['quantity'] = max(0, $qty);
                return $it;
            })
            ->filter(fn($it) => ($it['quantity'] ?? 0) > 0)
            ->values()->all();

        session()->put($key, $cart);
    }

    public function remove(string $type, int $id): void
    {
        if (Auth::check()) {
            $model = $type::query()->findOrFail($id);
            LaravelCart::driver('database')->removeItem($model);
            return;
        }

        $key = $this->guestKey();
        $cart = collect(session()->get($key, []))
            ->reject(fn ($it) => $it['itemable_id'] === $id)
            ->values()->all();

        session()->put($key, $cart);
    }

    public function empty(): void
    {
        if (Auth::check()) { LaravelCart::driver('database')->emptyCart(); return; }
        session()->forget($this->guestKey());
    }

    public function count(): int
    {
        if (Auth::check()) {
            $cart = CartModel::query()->withCount('items')->where('user_id', Auth::id())->first();
            return (int) ($cart->items_count ?? 0);
        }

        return collect(session()->get($this->guestKey(), []))->sum('quantity') ?: 0;
    }

    public function items(int $limit = 50): Collection
    {
        $conv = config('cart-guest-ui.media.conversion', 'thumb');

        if (Auth::check()) {
            $cart = CartModel::query()->with(['items.itemable'])->where('user_id', Auth::id())->first();

            return collect(optional($cart)->items)->take($limit)->map(function ($it) use ($conv) {
                $model = $it->itemable;
                $thumb = (is_object($model) && method_exists($model, 'getFirstMediaUrl'))
                    ? $model->getFirstMediaUrl($conv)
                    : null;

                return [
                    'id'    => $it->id,
                    'name'  => $model->name ?? class_basename($model).' #'.$model?->getKey(),
                    'qty'   => $it->quantity ?? 1,
                    'price' => $it->price ?? null,
                    'thumb' => $thumb,
                    'type'  => $model ? $model::class : null,
                    'pk'    => $model?->getKey(),
                ];
            });
        }

        $items = collect(session()->get($this->guestKey(), []))->take($limit)->map(function ($it) use ($conv) {
            $model = class_exists($it['itemable_type'] ?? '') 
                ? ($it['itemable_type'])::query()->find($it['itemable_id']) 
                : null;

            $thumb = ($model && method_exists($model, 'getFirstMediaUrl'))
                ? $model->getFirstMediaUrl($conv)
                : null;

            return [
                'id'    => $it['itemable_id'],
                'name'  => $model->name ?? ($it['itemable_type'] ?? 'Item').' #'.($it['itemable_id']),
                'qty'   => $it['quantity'] ?? 1,
                'price' => $it['price'] ?? null,
                'thumb' => $thumb,
                'type'  => $it['itemable_type'] ?? null,
                'pk'    => $it['itemable_id'] ?? null,
            ];
        });

        return $items;
    }

    public function mergeIntoUser(int $userId): void
    {
        $guest = collect(session()->get($this->guestKey(), []));
        if ($guest->isEmpty()) return;

        foreach ($guest as $it) {
            if (!isset($it['itemable_type'], $it['itemable_id'])) continue;
            $model = ($it['itemable_type'])::query()->find($it['itemable_id']);
            if (!$model) continue;

            LaravelCart::driver('database')->storeItem($model);
            $qty = max(1, (int) ($it['quantity'] ?? 1));
            if ($qty > 1) LaravelCart::driver('database')->increaseQuantity($model, $qty - 1);
        }

        session()->forget($this->guestKey());
    }

    protected function guestKey(): string
    {
        return 'guest_cart:'.session()->getId();
    }
}