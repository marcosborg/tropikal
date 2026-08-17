<?php
namespace App\Services;
use App\Mail\OrderPaid; use App\Models\{Order,ProcessedWebhook,SiteSetting}; use Illuminate\Support\Facades\{DB,Mail}; use Stripe\StripeClient;
class StripeCheckoutService {
 public function client():StripeClient { if(!config('services.stripe.secret'))throw new \RuntimeException('Os pagamentos ainda não estão configurados.');return new StripeClient(config('services.stripe.secret')); }
 public function create(Order $order):object {
  $order->load('items'); $items=$order->items->map(fn($i)=>['price_data'=>['currency'=>strtolower($order->currency),'unit_amount'=>(int)round($i->unit_price*100),'product_data'=>['name'=>$i->name.($i->variant_name?' — '.$i->variant_name:''),'metadata'=>['sku'=>$i->sku??'']]],'quantity'=>$i->quantity])->values()->all();
  if($order->shipping_total>0)$items[]=['price_data'=>['currency'=>'eur','unit_amount'=>(int)round($order->shipping_total*100),'product_data'=>['name'=>'Entrega']], 'quantity'=>1];
  $params=['mode'=>'payment','line_items'=>$items,'customer_email'=>$order->email,'client_reference_id'=>$order->public_id,'metadata'=>['order_id'=>(string)$order->id,'order_number'=>$order->number],'payment_intent_data'=>['metadata'=>['order_id'=>(string)$order->id,'order_number'=>$order->number]],'success_url'=>route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}','cancel_url'=>route('checkout.cancel',$order),'locale'=>'pt','billing_address_collection'=>'required','phone_number_collection'=>['enabled'=>true],'allow_promotion_codes'=>false];
  if($order->discount_total>0)$params['discounts']=[['coupon'=>$this->client()->coupons->create(['amount_off'=>(int)round($order->discount_total*100),'currency'=>'eur','duration'=>'once','name'=>'Desconto '.$order->number])->id]];
  $session=$this->client()->checkout->sessions->create($params,['idempotency_key'=>'checkout-'.$order->public_id]); $order->update(['stripe_checkout_session_id'=>$session->id]); return $session;
 }
 public function fulfill(string $sessionId):?Order {
  $session=$this->client()->checkout->sessions->retrieve($sessionId,[]); if($session->payment_status==='unpaid')return null;
  $order=DB::transaction(function()use($session){$order=Order::where('stripe_checkout_session_id',$session->id)->lockForUpdate()->firstOrFail();if($order->payment_status==='paid')return $order;$order->update(['payment_status'=>'paid','status'=>'processing','stripe_payment_intent_id'=>(string)$session->payment_intent,'paid_at'=>now()]);foreach($order->items()->with(['product','variant'])->get() as $item){$stock=$item->variant&&$item->variant->track_stock?$item->variant:($item->product?->track_stock?$item->product:null);if($stock)$stock->decrement('stock_quantity',min($item->quantity,$stock->stock_quantity));}$order->discountCode?->increment('uses');return $order;});
  if(!$order->confirmation_sent_at)try{Mail::to($order->email)->send(new OrderPaid($order->load('items')));$shop=SiteSetting::where('key','email')->value('value');if($shop)Mail::to($shop)->send(new OrderPaid($order));$order->update(['confirmation_sent_at'=>now()]);}catch(\Throwable $e){report($e);}return $order;
 }
}
