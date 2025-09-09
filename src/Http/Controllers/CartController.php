<?php

namespace Richardmaximun\CartGuestUi\Http\Controllers;

use Illuminate\Http\Request;
use Richardmaximun\CartGuestUi\Support\CartGateway;

class CartController
{
    public function __construct(protected CartGateway $cart) {}

    public function index()
    {
        $items = $this->cart->items();
        return view('cart-guest-ui::cart.index', compact('items'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'type' => ['required','string'],
            'id'   => ['required','integer'],
            'qty'  => ['nullable','integer','min:1'],
            'price'=> ['nullable','integer'],
        ]);

        $this->cart->add($data['type'], (int) $data['id'], (int) ($data['qty'] ?? 1), $data['price'] ?? null);
        return back()->with('status', 'Agregado al carrito');
    }

    public function update(Request $r, string $type, int $id)
    {
        $qty = max(0, (int) $r->integer('qty', 1));
        $this->cart->setQuantity($type, $id, $qty);
        return back()->with('status', 'Carrito actualizado');
    }

    public function destroy(string $type, int $id)
    {
        $this->cart->remove($type, $id);
        return back()->with('status', 'Ítem eliminado');
    }
}