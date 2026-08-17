<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DiscountCode extends Model {
 protected $attributes=['is_active'=>true,'uses'=>0,'per_customer_limit'=>1];
 protected $guarded=[]; protected $casts=['starts_at'=>'datetime','ends_at'=>'datetime','is_active'=>'boolean','value'=>'decimal:2','minimum_amount'=>'decimal:2','maximum_discount'=>'decimal:2'];
 public function isValidFor(float $subtotal):bool{return $this->is_active&&(!$this->starts_at||$this->starts_at->isPast())&&(!$this->ends_at||$this->ends_at->isFuture())&&(!$this->usage_limit||$this->uses<$this->usage_limit)&&(!$this->minimum_amount||$subtotal>=(float)$this->minimum_amount);}
 public function discount(float $subtotal):float{$amount=$this->type==='percentage'?$subtotal*((float)$this->value/100):min($subtotal,(float)$this->value);if($this->maximum_discount)$amount=min($amount,(float)$this->maximum_discount);return round($amount,2);}
}
