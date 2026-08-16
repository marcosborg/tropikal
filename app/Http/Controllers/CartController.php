<?php

namespace App\Http\Controllers;

use App\Models\{Product, ProductVariant};
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request) { return view('cart', ['cart' => $this->hydrate($request)]); }

    public function add(Request $request, Product $product)
    {
        abort_unless($product->is_published, 404);
        $data = $request->validate(['variant_id' => 'nullable|integer', 'quantity' => 'required|integer|min:1|max:99', 'notes' => 'nullable|string|max:500']);
        $variant = isset($data['variant_id']) ? ProductVariant::whereBelongsTo($product)->whereKey($data['variant_id'])->where('is_available', true)->firstOrFail() : null;
        $key = $product->id.':'.($variant?->id ?? 0);
        $cart = $request->session()->get('order_cart', []);
        $cart[$key] = ['product_id' => $product->id, 'variant_id' => $variant?->id, 'quantity' => min(99, ($cart[$key]['quantity'] ?? 0) + $data['quantity']), 'notes' => $data['notes'] ?? ($cart[$key]['notes'] ?? null)];
        $request->session()->put('order_cart', $cart);
        return back()->with('success', 'Produto adicionado ao pedido.');
    }

    public function update(Request $request)
    {
        $data = $request->validate(['items' => 'required|array', 'items.*.quantity' => 'required|integer|min:0|max:99', 'items.*.notes' => 'nullable|string|max:500']);
        $cart = $request->session()->get('order_cart', []);
        foreach ($data['items'] as $key => $item) {
            if (!isset($cart[$key])) continue;
            if ((int) $item['quantity'] === 0) unset($cart[$key]);
            else { $cart[$key]['quantity'] = (int) $item['quantity']; $cart[$key]['notes'] = $item['notes'] ?? null; }
        }
        $request->session()->put('order_cart', $cart);
        return back()->with('success', 'Pedido atualizado.');
    }

    public function checkout(Request $request)
    {
        $cart = $this->hydrate($request);
        return $cart->isEmpty() ? redirect()->route('cart.show')->withErrors(['cart' => 'Adicione pelo menos um produto.']) : view('checkout', compact('cart'));
    }

    public function hydrate(Request $request)
    {
        return collect($request->session()->get('order_cart', []))->map(function ($item, $key) {
            $product = Product::with('images')->where('is_published', true)->find($item['product_id']);
            if (!$product) return null;
            $variant = $item['variant_id'] ? ProductVariant::whereBelongsTo($product)->find($item['variant_id']) : null;
            return compact('key', 'product', 'variant') + ['quantity' => $item['quantity'], 'notes' => $item['notes'] ?? null];
        })->filter()->values();
    }
}
