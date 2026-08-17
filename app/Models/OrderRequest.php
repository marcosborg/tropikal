<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    protected $guarded = [];
    protected $casts = ['notified_at' => 'datetime', 'shipping_address' => 'array', 'subtotal' => 'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderRequestItem::class); }
}
