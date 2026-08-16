<?php

namespace App\Http\Controllers;

use App\Mail\OrderRequestConfirmation;
use App\Models\{OrderRequest, SiteSetting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Mail};
use Illuminate\Support\Str;

class OrderRequestController extends Controller
{
    public function store(Request $request, CartController $cartController)
    {
        $cart = $cartController->hydrate($request);
        abort_if($cart->isEmpty(), 422, 'O pedido está vazio.');
        $data = $request->validate([
            'customer_type' => 'required|in:private,professional,reseller', 'name' => 'required|string|max:120',
            'email' => 'required|email|max:160', 'phone' => 'required|string|max:40', 'company' => 'nullable|string|max:160',
            'tax_number' => 'nullable|string|max:32', 'region' => 'required|string|max:120', 'fulfilment_type' => 'required|in:delivery,installation,both',
            'notes' => 'nullable|string|max:3000', 'terms' => 'accepted', 'website' => 'nullable|max:0',
        ]);
        unset($data['terms'], $data['website']);
        $order = DB::transaction(function () use ($data, $cart) {
            $order = OrderRequest::create($data + ['reference' => 'TRP-'.now()->format('ymd').'-'.strtoupper(Str::random(5))]);
            foreach ($cart as $line) $order->items()->create([
                'product_id' => $line['product']->id, 'product_variant_id' => $line['variant']?->id,
                'product_name' => $line['product']->name, 'variant_name' => $line['variant']?->name,
                'quantity' => $line['quantity'], 'notes' => $line['notes'],
            ]);
            return $order->load('items');
        });
        try {
            Mail::to($order->email)->send(new OrderRequestConfirmation($order, false));
            $to = SiteSetting::where('key', 'email')->value('value') ?: config('mail.from.address');
            Mail::to($to)->send(new OrderRequestConfirmation($order, true));
            $order->update(['notified_at' => now()]);
        } catch (\Throwable $e) { report($e); }
        $request->session()->forget('order_cart');
        return redirect()->route('order.success', $order->reference);
    }

    public function success(string $reference)
    {
        return view('order-success', ['order' => OrderRequest::where('reference', $reference)->firstOrFail()]);
    }
}
