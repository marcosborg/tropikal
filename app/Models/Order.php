<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
 protected $guarded=[]; protected $casts=['shipping_address'=>'array','billing_address'=>'array','subtotal'=>'decimal:2','discount_total'=>'decimal:2','shipping_total'=>'decimal:2','tax_total'=>'decimal:2','total'=>'decimal:2','paid_at'=>'datetime','cancelled_at'=>'datetime','fulfilled_at'=>'datetime','confirmation_sent_at'=>'datetime'];
 public function getRouteKeyName(){return 'public_id';} public function user(){return $this->belongsTo(User::class);} public function items(){return $this->hasMany(OrderItem::class);} public function returns(){return $this->hasMany(ReturnRequest::class);} public function discountCode(){return $this->belongsTo(DiscountCode::class);}
}
