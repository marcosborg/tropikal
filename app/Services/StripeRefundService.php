<?php
namespace App\Services;
use App\Models\ReturnRequest;
class StripeRefundService {
 public function refund(ReturnRequest $return,float $amount):void{if(!$return->order->stripe_payment_intent_id)throw new \RuntimeException('A encomenda não tem pagamento Stripe associado.');$max=(float)$return->order->total-(float)$return->order->returns()->where('status','refunded')->sum('refunded_amount');if($amount<=0||$amount>$max)throw new \InvalidArgumentException('O valor de reembolso é inválido.');$refund=(new StripeCheckoutService)->client()->refunds->create(['payment_intent'=>$return->order->stripe_payment_intent_id,'amount'=>(int)round($amount*100),'metadata'=>['return_reference'=>$return->reference]],['idempotency_key'=>'refund-'.$return->reference]);$return->update(['status'=>'refunded','refunded_amount'=>$amount,'stripe_refund_id'=>$refund->id,'resolved_at'=>now()]);$totalRefunded=(float)$return->order->returns()->where('status','refunded')->sum('refunded_amount');$return->order->update(['payment_status'=>$totalRefunded>=(float)$return->order->total?'refunded':'partially_refunded']);}
}
